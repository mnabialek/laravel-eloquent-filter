<?php

namespace Mnabialek\LaravelEloquentFilter\Tests;

use Illuminate\Database\Eloquent\Builder;
use Mnabialek\LaravelEloquentFilter\Filters\SimpleQueryFilter;
use Mnabialek\LaravelEloquentFilter\Traits\Filterable;
use Mockery as m;

class FilterableTest extends UnitTestCase
{
    /** @test */
    public function it_calls_filter_apply_and_return_its_result()
    {
        $class = new class
        {
            use Filterable;
        };

        $result = 'anything';

        $query = m::mock(Builder::class);
        $filter = m::mock(SimpleQueryFilter::class);
        $filter->shouldReceive('apply')->once()->with($query)
            ->andReturn($result);

        $this->assertEquals($result, $class->scopeFiltered($query, $filter));
    }
}
