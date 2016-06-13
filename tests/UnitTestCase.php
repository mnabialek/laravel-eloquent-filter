<?php

namespace Mnabialek\LaravelEloquentFilter\Tests;

use Mockery as m;

class UnitTestCase extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }
}
