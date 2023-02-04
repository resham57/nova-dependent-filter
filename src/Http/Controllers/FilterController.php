<?php

namespace Resham\NovaDependentFilter\Http\Controllers;

use App\Http\Controllers\Controller;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Http\Requests\NovaRequest;

class FilterController extends Controller
{
    /**
     * Get the filter and return its options as a JSON response.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest|\Laravel\Nova\Http\Requests\LensRequest $request
     * @param  \Resham\NovaDependentFilter\DependentFilter $filter
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getOptions($request, $filter)
    {
        return $filter ?
                    response()->json(
                        $filter->getOptions(
                            $request,
                            json_decode(base64_decode($request->filters), true)
                        )
                    ) : abort(404);
    }

    /**
     * Get the filter request and return its options as a JSON response.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFilterOptions(NovaRequest $request)
    {
        $filter = $request->newResource()
                        ->availableFilters($request)
                        ->first(function ($filter) use ($request) {
                            return $request->filter === $filter->key();
                        });
        return $this->getOptions($request, $filter);
    }

    /**
     * Get the filter request and return its options as a JSON response.
     *
     * @param  \Laravel\Nova\Http\Requests\LensRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLensFilterOptions(LensRequest $request)
    {
        $filter = $request->lens()
                        ->availableFilters($request)
                        ->first(function ($filter) use ($request) {
                            return $request->filter === $filter->key();
                        });
        return $this->getOptions($request, $filter);
    }
}
