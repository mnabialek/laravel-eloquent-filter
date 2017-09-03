<?php

namespace Mnabialek\LaravelEloquentFilter\Tests;

use Mockery as m;
use Illuminate\Database\Eloquent\Builder;
use Mnabialek\LaravelEloquentFilter\Traits\Filterable;
use Mnabialek\LaravelEloquentFilter\Filters\SimpleQueryFilter;

class FilterableTest extends UnitTestCase
{
    /** @test */
    public function it_calls_filter_apply_and_return_its_result()
    {
        $class = new class() {
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
