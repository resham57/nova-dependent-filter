<?php

namespace Resham\NovaDependentFilter;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Filters\Filter;

class DependentFilter extends Filter
{
    /**
     * @var string
     */
    public $key;

    /**
     * @var string
     */
    public $component = 'dependent-filter';

    /**
     * @var array
     */
    public $parentOf = [];

    /**
     * Apply the filter to the given query.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  mixed $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(NovaRequest $request, $query, $value)
    {
        return $query->whereIn($this->key, (array)$value);
    }

    /**
     * Get the key for the filter.
     *
     * @return string
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * Get the children for the filter.
     *
     * @return array
     */
    public function parentOf()
    {
        return $this->parentOf;
    }

    /**
     * Get the filter options.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param  array $filters
     * @return array
     */
    final public function getOptions(NovaRequest $request, array $filters = [])
    {
        return collect($this->options($request, $filters))->map(function ($label, $value) {
                        return is_array($value) ? array_merge(['value' => $label], $value)
                                    : (is_string($label) ? ['label' => $label, 'value' => $value]
                                    : ['label' => $value, 'value' => $value]);
                    })->values()->all();
    }

    /**
     * Prepare the filter for JSON serialization.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return array_merge([
            'class' => $this->key(),
            'name' => $this->name(),
            'component' => $this->component(),
            'options' => $this->getOptions(app(NovaRequest::class)),
            'currentValue' => $this->default() ?? '',
            'children' => $this->parentOf(),
        ], $this->meta());
    }
}
