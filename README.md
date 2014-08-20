# QuipTest

The multi armed bandit as a PHP Component
<a href="http://stevehanov.ca/blog/index.php?id=132" target="_blank">(See this article for background)</a>



**Better** testing than A/B testing!

## Background

There is a fundemental flaw with standard A/B testing. The biggest thing is that tests are final. Compare **A**. Compare **B**. After 1000 tests **A** might be proven to be more successful with 300/500. **B** failed with 240/500. 

You go with option **A**, and that is the end of the story.

Had you done 10,000 tests, the results might have been different. **A** fails 3,000/5,000 to **B** 3,500/5,000. And 6 months down the road, it might flop back to A. 

QuipTest will let you plug in dynamic, evolving tests, to any part of your application.

## Installation

#### Composer
```
$ composer require "alairock/quip-test":"dev-master"
```

#### Other Requirements
- Redis Server (<a href="http://redis.io/topics/quickstart" target="_blank">Installation</a>)
- PHPRedis PHP Extension (<a href="https://github.com/nicolasff/phpredis#installingconfiguring" target="_blank">Installation</a>)


## Documentation

For a basic example of a working test, see the <a href="https://github.com/alairock/QuipTest/blob/master/index.php" target="_blank">index</a>.

#### Autoload
Be sure to include your composer autoload file, if you are not already, or your framework is not supporting it. Ideally you would put the autoload file in a place that cascades into all areas of your application that will require a component. (<a href="https://getcomposer.org/doc/01-basic-usage.md" target="_blank">Composer</a>)

#### Initation the test.
```
<?php
	$testName = "button"; 
	$tags = ['orange', 'green', 'white']; // test tags
	$buttonTest = new Quip\Test($testName, $tags);
```

#### Get tag
This method has multiple functions. It increments the total number of tests run, as well as the total number of tests run for a specific tag. Use this method to flag your test, and track successes.

```
	$tagName = $buttonTest->getTag();
``` 


#### Flag test as successful
If a test is successful, make sure you mark it! QuipTests do not mark failures. Failures are assumed automatically when not marked successful. 

```
	$buttonTest->markSuccess($tagName);
``` 

#### Get stats
```
	var_dump($buttonTest->getStats());
```

Example output:
>     array (size=2)
>     'cases' => 
>         array (size=3)
>       'cat' => 
>         array (size=2)
>           'tests_run_for_case' => string '41' (length=2)
>           'success_rate' => float 17.073170731707
>       'bird' => 
>         array (size=2)
>           'tests_run_for_case' => string '28' (length=2)
>           'success_rate' => float 10.714285714286
>       'dog' => 
>         array (size=2)
>           'tests_run_for_case' => string '62' (length=2)
>           'success_rate' => float 12.903225806452
>     'tests_performed' => string '131' (length=3)

#### Reset/Flush test
```
	$buttonTest->flushAll();
``` 
