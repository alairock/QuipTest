<?php

namespace Quip;


class Test {

	/**
	 *	The redis object
	 * @var \Redis
	 */
	public $redis;

	/**
	 *	The prefix, or test name. This is the key for redis
	 * @var $prefix
	 */
	protected $prefix;

	/**
	 * The tags you want to track in your tests
	 * @var $dataPool
	 */
	protected $dataPool;

	/**
	 * The specific test case currently running
	 * @var $case
	 */
	protected $case;

	/**
	 * Any stats we retrieve
	 * @var $stats
	 */
	protected $stats;

	/**
	 * Setup our test. We need the "Test Name" and "Tags"
	 * Know also as "Prefix" and "DataPool" respectively.
	 * @param $prefix
	 * @param array $tags
	 */
	public function __construct($uri = 'tcp://127.0.0.1:6379', $prefix = null, array $tags = [], ) {
		$this->redis = new \Predis\Client($uri);
		if (!is_null($prefix)) {
			$this->setPrefix($prefix);
		}
		if (!empty($tags)) {
			$this->setDataPool($tags);
		}
	}

	public function getRedisClient()
	{
		return $this->redis;
	}

	/**
	 * flushAll tests from redis.
	 * WARNING: This will revert all your tests.
	 * @return void
	 */
	public function flushAll() {
		$this->redis->flushAll();
		exit;
	}

	/**
	 * getStats of test
	 *
	 * @return mixed
	 */
	public function getStats() {
		$this->_calculateStats();
		return $this->stats;
	}

	/**
	 * _calculateStats
	 * Based on the prefix, lets get and calculate stats
	 * @return array
	 */
	protected function _calculateStats() {
		$cases = [];
		foreach ($this->redis->keys($this->prefix . ":*") as $redisPath) {
			$endOfPath = substr($redisPath, strrpos($redisPath, ':') + 1);
			$beginningOfPath = substr($redisPath, 0, strrpos($redisPath, ':'));
			if ($redisPath == $this->prefix . ':total' || $endOfPath == 'passes') {
				continue;
			}
			$case = substr(strstr($beginningOfPath, ':', false), 1);
			$cases[$case] = $this->getSuccessesRate($beginningOfPath);
			$numberOfTestsPerformed = $this->redis->get($this->prefix . ':total');
			$this->stats['cases'][$case] = [
				'tests_run_for_case' => $this->redis->get($beginningOfPath . ':tests'),
				'success_rate' =>	$cases[$case],
			];
			$this->stats['tests_performed'] = $numberOfTestsPerformed;
		}
		return $cases;
	}

	/**
	 * getSuccessesRate
	 *
	 * get success rate of test.
	 *
	 * @param $path
	 * @return bool|float|int|string
	 */
	protected function getSuccessesRate($path) {
		$successes = $this->getSuccesses($path);
		$testsRunForCase = $this->redis->get($path . ':tests');
		$successes = $successes / $testsRunForCase;
		$successes = $successes * 100;
		return $successes;
	}

	/**
	 * getSuccesses
	 *
	 * Get successes from test tag
	 *
	 * @param $path
	 * @return bool|int|string
	 */
	protected function getSuccesses($path) {
		return (empty($this->redis->get($path . ':passes'))) ? 0 : $this->redis->get($path . ':passes');
	}

	/**
	 * setPrefix
	 *
	 * set the prefix / testName
	 *
	 * @param $prefix
	 */
	public function setPrefix($prefix) {
		$this->prefix = $prefix;
		return;
	}

	/**
	 * getPrefix
	 * get the prefix / testname
	 *
	 * @return bool
	 */
	public function getPrefix() {
		if (!empty($this->prefix)) {
			return $this->prefix;
		}
		return false;
	}

	/**
	 * getKeys
	 *
	 * Get the keys from predis.
	 *
	 * @param $prefix
	 * @return array
	 */
	public function getKeys($prefix) {
		return $this->redis->keys($prefix . ":*");
	}

	/**
	 * setDataPool
	 *
	 * setDataPool / tags
	 *
	 * @param array $array
	 */
	public function setDataPool(Array $array) {
		$this->dataPool = $array;
		return;
	}

	/**
	 * markSuccess
	 * Mark tag as successful
	 * @param $successVar
	 * @return bool
	 */
	public function markSuccess($successVar) {
		if (!in_array($successVar, $this->dataPool)) {
			return false;
		}
		$this->redis->incr($this->prefix .':' . $successVar . ':passes');
		return true;
	}

	/**
	 * getTag
	 *
	 * get tag of current test
	 *
	 * @return mixed
	 */
	public function getTag() {
		if (mt_rand(1,10) == 1 || ($this->redis->get($this->prefix . ':total') < 20)) {
			$case = $this->dataPool[array_rand($this->dataPool)];
		} else {
			$cases = $this->_calculateStats();
			$case = array_keys($cases, max($cases))[0];
		}
		$this->case = $case;
		$this->_incrementTotalAndTest($case);
		return $case;
	}

	/**
	 * _incrementTotalAndTest
	 *
	 * @param $case
	 */
	protected function _incrementTotalAndTest($case) {
		$this->redis->incr($this->prefix . ":" . $case . ':tests');
		$this->redis->incr($this->prefix . ":" . 'total');
	}
}
