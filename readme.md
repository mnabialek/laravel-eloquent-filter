Laravel Eloquent Filter
===

This module lets you filter Eloquent data using query filters. You can apply WHERE filters and also set order of results

## Installation

1. Run
   ```php   
   composer require mnabialek/laravel-eloquent-filter
   ```     
   in console to install this module
   
2. That's it! Installation is complete. You don't need to adjust any config or install service providers.

## Getting started

Let's assume you want to allow to filter users data. By default you use `User` Eloquent model to get users. To enable filtering, open User model (by default `app/User.php` file) and add into it the following trait:

```php
use Mnabialek\LaravelEloquentFilter\Traits\Filterable;
```
   
just bellow opening class definition, so it should look something like this:

```php
class User extends Authenticatable
{
    use Mnabialek\LaravelEloquentFilter\Traits\Filterable;
```    

To allow filtering for this class, you need to also create implementation of `Mnabialek\LaravelEloquentFilter\Contracts\QueryFilter` interface. To do that create min `app/Filters` directory file with the following content:

```php
<?php

namespace App\Filters;

use Mnabialek\LaravelEloquentFilter\Filters\SimpleQueryFilter;

class UserFilter extends SimpleQueryFilter
{
    protected $simpleFilters = ['id','created_at'];
    
    protected $simpleSorts = ['id','email','created_at'];
}
```

As you see, you don't implement here the whole contract but you only extend `SimpleQueryFilter` class that does it for you.

Now you need to go to place where you get users data. Let's assume in your controller you get your users using:

```php
$users = User::get();
```

All you need to do now, is changing it into:

```php
$users = User::filtered(\App::make(\App\Filters\UserFilter::class))->get();
```

Obviously you can use method dependency injection for that instead of using `App::make` here but it's only simple example that should work in all places of your app.

Now, let's assume you display your users using `http://localhost/users` url.
 
After those changes running:
 
- `http://localhost/users?id=2` - should display user with id = 2 only
- `http://localhost/users?id[]=2&id=5` - should display user with id = 2 and user with id = 5
- `http://localhost/users?sort=created_at,-email` - should display users with created_at ascending and email descending order.
 
 
## Simple query parser and simple query filter

Although you can create custom parsers and filters, some default ones are provided.

By default you can pass to your url conditions using field with value for example `id=5` and you can apply sorting using `sort` parameter with names of fields separated by comma. If you precede field by `-` sign, it will assume you want to sort by this field in descending order.

When implementing your filter class when you extend `SimpleQueryParser` in `$simpleFilters` and `$simpleSorts` you can specify any fields that might be filtered and sorted without any custom implementation. For those fields simple `=` comparison will be used and in case of array usage (for example `id[]=2&id[]=5` it will be assumed you want to get data with logical `OR` operator.

However in real life you might want to specify custom filter or sort method. To do that, you need to implement your custom method for such field for example:

For `created_at` filter you can use

```php
protected function applyCreatedAt($value)
{
    $this->query->whereRaw('DATE(created_at) = ? ', [$value]);
}
```

For `id` sort you can use for example:
 
```php 
protected function applySortId($order)
{
   $this->query->orderBy('id',$order)->orderBy('email','asc');
}
```

You can also implement default filters and default sorting implementing `applyDefaultFilters` and `applyDefaultSorts` methods where you can check whether any filters or sorts were already applied.

## Customization

Although this module provides some default implementations, you can create your own. You can change they way data is passed to QueryFilter. By default `SimpleQueryParser` is used that parses Request input in very basic way. However you might want to create your own implementation of `Mnabialek\LaravelEloquentFilter\Contracts\InputParser` to fully adjust it to your needs. 

You might also change they way filters and sorts are applied to query. To do this, you need to create your own implementation of `Mnabialek\LaravelEloquentFilter\Contracts\QueryFilter` but again default implementation was given.
 
In case you want to only create implementation of QueryFilter, it might be convenient to create custom filter class, that will pass this specific QueryFilter implementation to QueryFilter instead of creating your own constructor in multiple filters classes. You can look at `SimpleQueryFilter` which does it for `SimpleQueryParser` to remove need of defining this constructor over and over in multiple filter classes (assuming you want to use SimpleQueryParser for them).     

## Licence

This package is licenced under the [MIT license](http://opensource.org/licenses/MIT)
