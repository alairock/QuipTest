<?php

namespace Quip;


class Test {

	public $redis;

	protected $prefix;

	protected $dataPool;

	protected $case;

	protected $stats;

	public function __construct($prefix, array $datapool) {
		$this->setPrefix($prefix);
		$this->setDataPool($datapool);
		$this->redis = new \Predis\Client();
	}

	public function flushAll() {
		$this->redis->flushall();
		exit;
	}

	public function getStats() {
		$this->_calculateStats();
		return $this->stats;
	}

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
				'success_rate' =>  $cases[$case],
			];
			$this->stats['tests_performed'] = $numberOfTestsPerformed;
		}
		return $cases;
	}

	protected  function getSuccessesRate($path) {
		$successes = $this->getSuccesses($path);
		$testsRunForCase = $this->redis->get($path . ':tests');
		$successes = $successes / $testsRunForCase;
		$successes = $successes * 100;
		return $successes;
	}

	protected function getSuccesses($path) {
		return (empty($this->redis->get($path . ':passes'))) ? 0 : $this->redis->get($path . ':passes');
	}

	public function setPrefix($prefix) {
		$this->prefix = $prefix;
		return;
	}

	public function getPrefix() {
		if (!empty($this->prefix)) {
			return $this->prefix;
		}
		return false;
	}

	public function getKeys($prefix) {
		return $this->redis->keys($prefix . ":*");
	}

	public function setDataPool(Array $array) {
		$this->dataPool = $array;
		return;
	}

	public function setSuccess($successVar) {
		if (!in_array($successVar, $this->dataPool)) {
			return false;
		}
		$this->redis->incr($this->prefix .':' . $successVar . ':passes');
		return true;
	}

	public function getTestVar() {
		if (mt_rand(1,10) == 1 || ($this->redis->get($this->prefix . ':total') < 20)) {
		$case = $this->dataPool[array_rand($this->dataPool)];
		} else {
			$cases = $this->_calculateStats();
			$case = array_keys($cases, max($cases))[0];
		}
		$this->case = $case;
		$this->incrementTotalAndTest($case);
		return $case;
	}

	protected function incrementTotalAndTest($case) {
		$this->redis->incr($this->prefix . ":" . $case . ':tests');
		$this->redis->incr($this->prefix . ":" . 'total');
	}
}