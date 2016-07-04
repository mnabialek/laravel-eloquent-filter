<?php

namespace Mnabialek\LaravelEloquentFilter\Tests;

use Illuminate\Support\Collection;
use Mnabialek\LaravelEloquentFilter\Objects\Filter;
use Mnabialek\LaravelEloquentFilter\Parsers\DataTablesQueryParser;
use Mnabialek\LaravelEloquentFilter\Objects\Sort;
use Mockery as m;

class DataTablesQueryParserTest extends UnitTestCase
{
    /** @test */
    public function it_returns_empty_filters_when_empty_request()
    {
        $request = m::mock('Illuminate\Http\Request');
        $request->shouldReceive('input')->once()->with('columns')
            ->andReturn([]);
        $request->shouldReceive('except')->once()->with([
            'sort',
            'columns',
            'order',
        ])
            ->andReturn([]);

        $parser = new DataTablesQueryParser($request, new Collection());
        $this->assertEquals(new Collection(), $parser->getFilters());
    }

    /** @test */
    public function it_returns_valid_filters_when_not_empty_request()
    {
        $request = m::mock('Illuminate\Http\Request');
        $request->shouldReceive('input')->once()->with('columns')->andReturn([
            [
                'data' => 'dt_filter',
                'search' => [
                    'value' => 'abc',
                ],
            ],
            [
                'data' => 'dt_filter2',
                'search' => [
                    'value' => 'abc2',
                ],
            ],

        ]);

        $request->shouldReceive('except')->once()->with([
            'sort',
            'columns',
            'order',
        ])->andReturn([
            'id' => 5,
            'email' => '  test@example.com ',
            'something' => ['  foo  ', 'bar', 'baz'],
        ]);

        $filters = new Collection([
            new Filter('dt_filter', 'abc', 'LIKE'),
            new Filter('dt_filter2', 'abc2', 'LIKE'),
            new Filter('id', 5, '='),
            new Filter('email', '  test@example.com ', '='),
            new Filter('something', ['  foo  ', 'bar', 'baz'], '='),
        ]);

        $parser = new DataTablesQueryParser($request, new Collection());
        $this->assertEquals($filters, $parser->getFilters());
    }

    /** @test */
    public function it_include_filter_with_empty_value_when_empty_value()
    {
        $request = m::mock('Illuminate\Http\Request');

        $request->shouldReceive('input')->once()->with('columns')->andReturn([
            [
                'data' => 'dt_filter',
                'search' => [
                    'value' => '',
                ],
            ],
            [
                'data' => 'dt_filter2',
                'search' => [
                    'value' => 'abc2',
                ],
            ],
        ]);

        $request->shouldReceive('except')->once()->with([
            'sort',
            'columns',
            'order',
        ])->andReturn([
            'id' => '',
            'email' => '  test@example.com ',
            'something' => ['  foo  ', 'bar', 'baz'],
        ]);

        $filters = new Collection([
            new Filter('dt_filter', '', 'LIKE'),
            new Filter('dt_filter2', 'abc2', 'LIKE'),
            new Filter('id', '', '='),
            new Filter('email', '  test@example.com ', '='),
            new Filter('something', ['  foo  ', 'bar', 'baz'], '='),
        ]);

        $parser = new DataTablesQueryParser($request, new Collection());
        $this->assertEquals($filters, $parser->getFilters());
    }

    /** @test */
    public function it_doest_not_include_filter_with_empty_value_when_empty_value_with_property_set()
    {
        $request = m::mock('Illuminate\Http\Request');

        $request->shouldReceive('input')->once()->with('columns')->andReturn([
            [
                'data' => 'dt_filter',
                'search' => [
                    'value' => '',
                ],
            ],
            [
                'data' => 'dt_filter2',
                'search' => [
                    'value' => 'abc2',
                ],
            ],
        ]);

        $request->shouldReceive('except')->once()->with([
            'sort',
            'columns',
            'order',
        ])->andReturn([
            'id' => '',
            'email' => '  test@example.com ',
            'something' => ['  foo  ', 'bar', 'baz'],
        ]);

        $filters = new Collection([
            new Filter('dt_filter2', 'abc2', 'LIKE'),
            new Filter('email', '  test@example.com ', '='),
            new Filter('something', ['  foo  ', 'bar', 'baz'], '='),
        ]);

        $parser =
            new IgnoreEmptyDataTablesQueryParser($request, new Collection());
        $this->assertEquals($filters, $parser->getFilters());
    }

    /** @test */
    public function it_returns_empty_sorts_when_empty_request()
    {
        $request = m::mock('Illuminate\Http\Request');

        $request->shouldReceive('input')->once()->with('columns')
            ->andReturn([]);

        $request->shouldReceive('input')->once()->with('order')
            ->andReturn([]);

        $request->shouldReceive('input')->once()->with('sort', '')
            ->andReturn('');

        $parser = new DataTablesQueryParser($request, new Collection());
        $this->assertEquals(new Collection(), $parser->getSorts());
    }

    /** @test */
    public function it_returns_valid_sorts_when_not_empty_request()
    {
        $request = m::mock('Illuminate\Http\Request');

        $request->shouldReceive('input')->once()->with('columns')->andReturn([
            [
                'data' => 'dt_filter',
                'search' => [
                    'value' => '',
                ],
            ],
            [
                'data' => 'dt_filter2',
                'search' => [
                    'value' => 'abc2',
                ],
            ],
            [
                'data' => 'dt_filter3',
                'search' => [
                    'value' => 'abc3',
                ],
            ],
            [
                'search' => [
                    'value' => 'abc4',
                ],
            ],
        ]);

        $request->shouldReceive('input')->once()->with('order')
            ->andReturn([
                [
                    'column' => 1,
                    'dir' => 'desc',
                ],
                [
                    'column' => 0,
                    'dir' => 'asc',
                ],
                [
                    'column' => 2,
                    'dir' => 'invalid',
                ],
                [
                    'column' => 3,
                    'dir' => 'asc',
                ],
                [
                    'column' => 4,
                    'dir' => 'desc',
                ],
            ]);

        $request->shouldReceive('input')->once()
            ->with('sort', '')->andReturn('id,-email,foo,bar,baz');

        $sorts = new Collection([
            new Sort('dt_filter2', 'DESC'),
            new Sort('dt_filter', 'ASC'),
            new Sort('id', 'ASC'),
            new Sort('email', 'DESC'),
            new Sort('foo', 'ASC'),
            new Sort('bar', 'ASC'),
            new Sort('baz', 'ASC'),
        ]);

        $parser = new DataTablesQueryParser($request, new Collection());
        $this->assertEquals($sorts, $parser->getSorts());
    }

    /** @test */
    public function it_sets_new_data_tables_filter_operator()
    {
        $request = m::mock('Illuminate\Http\Request');

        $parser =
            new DataTablesQueryParserWithGetter($request, new Collection());

        $this->assertEquals('LIKE', $parser->getDataTablesFilterOperator());
        $parser->setDataTablesFilterOperator('=');
        $this->assertEquals('=', $parser->getDataTablesFilterOperator());
    }
}

// stubs

class IgnoreEmptyDataTablesQueryParser extends DataTablesQueryParser
{
    protected $ignoreEmptyFilters = true;
}

class DataTablesQueryParserWithGetter extends DataTablesQueryParser
{
    public function getDataTablesFilterOperator()
    {
        return $this->dataTablesFilterOperator;
    }
}
