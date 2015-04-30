# metabor/statemachine

Statemachine in PHP 5.3

### Support

#### Gitter
[![Gitter chat](https://badges.gitter.im/Metabor/Statemachine.png)](https://gitter.im/Metabor/Statemachine)

### Continous Integration/Deployment

#### TravisCI
[![Build Status](http://img.shields.io/travis/Metabor/Statemachine.svg)](https://travis-ci.org/Metabor/Statemachine)

#### Open Issues
[![Open Issues](http://img.shields.io/github/issues/Metabor/Statemachine.svg)](https://github.com/Metabor/Statemachine/issues?state=open)

### Package Information

#### Packagist
[![Packagist](http://img.shields.io/packagist/v/Metabor/Statemachine.svg)](https://packagist.org/packages/metabor/statemachine)
[![Packagist](http://img.shields.io/packagist/dt/Metabor/Statemachine.svg)](https://packagist.org/packages/metabor/statemachine)
[![Packagist](http://img.shields.io/packagist/dm/Metabor/Statemachine.svg)](https://packagist.org/packages/metabor/statemachine)
[![Packagist](http://img.shields.io/packagist/dd/Metabor/Statemachine.svg)](https://packagist.org/packages/metabor/statemachine)

#### Compatibility
[![MetaborStd Version](http://img.shields.io/badge/MetaborStd-1.1-green.svg)](https://github.com/Metabor/MetaborStd)
[![HHVM Status](http://hhvm.h4cc.de/badge/metabor/statemachine.png)](http://hhvm.h4cc.de/package/metabor/statemachine)

### Dependency Status

#### VersionEye
[![Dependency Status](https://www.versioneye.com/php/metabor:statemachine/badge.svg)](https://www.versioneye.com/php/metabor:statemachine)

### Test Coverage

#### Scrutinizer
[![Code Coverage](https://scrutinizer-ci.com/g/Metabor/Statemachine/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Metabor/Statemachine/?branch=master)

### Code Quality

#### Codacy
[![Codacy](https://www.codacy.com/project/badge/c83d65fc6188425d92c6b7de57f201eb)](https://www.codacy.com/public/Metabor/Statemachine.git)

#### Code Climate
[![Code Climate](http://img.shields.io/codeclimate/github/Metabor/Statemachine.svg)](https://codeclimate.com/github/Metabor/Statemachine)

#### Scrutinizer
[![Scrutinizer Code Quality](http://img.shields.io/scrutinizer/g/Metabor/Statemachine.svg)](https://scrutinizer-ci.com/g/Metabor/Statemachine/?branch=master)

#### SensioLabsInsight
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/ac1d76c8-e9e1-4780-b21f-a0d01f582a21/big.png)](https://insight.sensiolabs.com/projects/ac1d76c8-e9e1-4780-b21f-a0d01f582a21)

### Other

[![License](http://img.shields.io/packagist/l/Metabor/Statemachine.svg)](http://opensource.org/licenses/MIT)
[![Gittip](http://img.shields.io/gittip/metabor.svg)](https://www.gittip.com/metabor/)

## Quickstart examples

Once [installed](#install), let's use a sample statemachine:

````phpex
<?php
require_once 'vendor/autoload.php';

use Metabor\Statemachine\Process;
use Metabor\Statemachine\State;
use Metabor\Statemachine\Statemachine;
use Metabor\Statemachine\Transition;

$closed = new State('closed');
$opened = new State('opened');

$eventOpen = 'open';
$eventClose = 'close';
$closed->addTransition(new Transition($opened, $eventOpen));
$closed->addTransition(new Transition($closed, $eventClose));
$opened->addTransition(new Transition($opened, $eventOpen));
$opened->addTransition(new Transition($closed, $eventClose));

// adding some action to events
// the parts of observing the event and executing the command are separated in this example
// normaly it could be put all together into your own command (base)class
$openCommand = new \Metabor\Callback\Callback(
        function ()
        {
            echo 'motor is opening door' . PHP_EOL;
        });
$observerForOpenEvent = new \Metabor\Observer\Callback($openCommand);
$closed->getEvent($eventOpen)->attach($observerForOpenEvent);

$closeCommand = new \Metabor\Callback\Callback(
        function ()
        {
            echo 'motor is closing door' . PHP_EOL;
        });
$observerForCloseEvent = new \Metabor\Observer\Callback($closeCommand);
$opened->getEvent($eventClose)->attach($observerForCloseEvent);

// stateful subject that belongs to this statemachine
$subject = new stdClass();

// start process with closed status;
$initialState = $closed;
$process = new Process('process name', $initialState);

$statemachine = new Statemachine($subject, $process);

echo 'Status:' . $statemachine->getCurrentState()->getName() . PHP_EOL;

echo 'Event:' . $eventOpen . PHP_EOL;
$statemachine->triggerEvent($eventOpen);
echo 'Status:' . $statemachine->getCurrentState()->getName() . PHP_EOL;

// opening an open door would not activate the motor
echo 'Event:' . $eventOpen . PHP_EOL;
$statemachine->triggerEvent($eventOpen);
echo 'Status:' . $statemachine->getCurrentState()->getName() . PHP_EOL;

echo 'Event:' . $eventClose . PHP_EOL;
$statemachine->triggerEvent($eventClose);
echo 'Status:' . $statemachine->getCurrentState()->getName() . PHP_EOL;
````

## Features

This library implements a [finite-state machine](http://en.wikipedia.org/wiki/Finite-state_machine) in PHP 5.3.

It was first developed for a talk at a conference. The example from my talk is available on Github and Packagist as [metabor/statemachine-example](https://github.com/Metabor/Statemachine-Example).

In the namespace MetaborStd are abstract types defined that are exemplified implemented in this project.
If you have to implement or use a statemachine in your project, feel free to either use this libary at all or replace the parts that didn't fit your needs by using the MetaborStd Interfaces.


### Process Graph Drawing

The library supports visualizing of the process graph by using clue/graph and [GraphViz](http://www.graphviz.org/) "Graph Visualization Software".

## Install

The recommended way to install this library is [through composer](http://getcomposer.org). [New to composer?](http://getcomposer.org/doc/00-intro.md)

```JSON
{
    "require": {
        "metabor/statemachine": "~1.1"
    }
}
```

Optional recommendation:
In order to be able to use the [process graph drawing feature](#process-graph-drawing) you'll have to
install GraphViz (`dot` executable). Users of Debian/Ubuntu-based distributions may simply
invoke `sudo apt-get install graphviz`, Windows users have to
[download GraphViZ for Windows](http://www.graphviz.org/Download_windows.php) and remaining
users should install from [GraphViz homepage](http://www.graphviz.org/Download.php).
To use this feature you also have to add this to your composer.json:
```JSON
{
    "require": {
        "graphp/graphviz": "*",
        "clue/graph": "*",
        "metabor/statemachine": "~1.1"
    }
}
```
An [example how to draw and display the graph](https://github.com/Metabor/Statemachine-Example/blob/master/graph.php), can be found in [metabor/statemachine-example](https://github.com/Metabor/Statemachine-Example).

## Tests

This library uses phpunit for its extensive testsuite.
You can either use a global installation or rely on the one composer installs
when you first run `$ composer install`.
This sets up the developer environment, so that you
can now run it from the project root directory:

```bash
$ php vendor/bin/phpunit`
```

## Contributing

If you encounter any issues, please don't hesitate to drop us a line, file a bug report or even best provide us with a patch / pull request and/or unit test to reproduce your problem.

Besides directly working with the code, any additional documentation, additions to our readme or even fixing simple typos are appreciated just as well.

Any feedback and/or contribution is welcome!

## License

Released under the terms of the permissive [MIT license](http://opensource.org/licenses/MIT).
