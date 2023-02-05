<h1 align="center">Dependent Filter for Laravel Nova 4</h1>

<p align="center">
    <a href="https://packagist.org/packages/resham/nova-dependent-filter"><img src="https://img.shields.io/packagist/v/resham/nova-dependent-filter.svg?style=flat" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/resham/nova-dependent-filter"><img src="https://img.shields.io/packagist/l/resham/nova-dependent-filter.svg?style=flat" alt="License"></a>
</p>

This is a package to integrate a dependent filter with Laravel Nova version 4.

## Requirements
- [PHP >= 7.3](http://php.net/)
- [Laravel Framework](https://github.com/laravel/framework)
- [Laravel Nova v4](https://nova.laravel.com/)

## Installation
Require this package with composer.
```shell
composer require resham/nova-dependent-filter
```

## Usage
### Normal Usage
Once the Installation is complete, you can use it by creating a filter class.
```php
use Resham\NovaDependentFilter\DependentFilter;

class CountryFilter extends DependentFilter
{
    /**
     * Name of filter.
     *
     * @var string
     */
    public $name = 'Country';

    /**
     * The filter's attribute. Also, it is key of filter.
     *
     * @var string
     */
    public $key = 'country_code';

    /**
     * Get the filter's available options.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  array $filters
     * @return array
     */
    public function options(NovaRequest $request, array $filters = [])
    {
        return Country::pluck('name', 'country_code');
    }
}
```

### Dependent Usage
Let's have a dependent filter state that depends on the country, we can achieve the dynamic-dependent filter

#### Parent Filter
Define the parent filter(on which other filters depend)
```php
use Resham\NovaDependentFilter\DependentFilter;

class CountryFilter extends DependentFilter
{
    /**
     * Name of filter.
     *
     * @var string
     */
    public $name = 'Country';

    /**
     * The filter's attribute. Also, it is key of filter.
     *
     * @var string
     */
    public $key = 'country_code';

    /**
     * The other filters key whose are depends on this filter.
     *
     * @var string[]
     */
    public $parentOf = ['state'];

    /**
     * Get the filter's available options.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  array $filters
     * @return array
     */
    public function options(NovaRequest $request, array $filters = [])
    {
        return Country::pluck('name', 'country_code');
    }
}
```
> Note: Don't forget to define the key of the child filter on the ```$parentOf``` property.


#### Child Filter
Let's define the child filter which is depends on parent filter.
```php
use Resham\NovaDependentFilter\DependentFilter;

class StateFilter extends DependentFilter
{
    /**
     * Name of filter.
     *
     * @var string
     */
    public $name = 'State';

    /**
     * The filter's attribute. Also, it is key of filter.
     *
     * @var string
     */
    public $key = 'state';

    /**
     * Get the filter's available options.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  array $filters
     * @return array
     */
    public function options(NovaRequest $request, array $filters = [])
    {
        return State::where('country_code', $filters['country_code'] ?? '')
                        ->pluck('name', 'id');
    }
}
```

## Registering Filters
We can register the filter as we define with nova.
```php
/**
 * Get the filters available for the resource.
 *
 * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
 * @return array
 */
public function filters(NovaRequest $request)
{
    return [
        new CountryFilter
    ];
}
```
Also, you can use ```CountryFilter::make()```
```php
/**
 * Get the filters available for the resource.
 *
 * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
 * @return array
 */
public function filters(NovaRequest $request)
{
    return [
        CountryFilter::make()
    ];
}
```
## Security
If you discover any issues, please email at [reshampokhrel57@gmail.com](mailto:reshampokhrel57@gmail.com).

## Reference
- [Awesome Nova Dependent Filter](https://github.com/awesome-nova/dependent-filter)
