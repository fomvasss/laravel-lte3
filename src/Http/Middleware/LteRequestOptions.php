<?php

namespace Fomvasss\Lte3\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LteRequestOptions
{

    /** @var array */
    protected $enabledOptionKeys = [
        'per_page' => 16,
    ];

    /**
     * First element of array - is default, max elements in array - two
     * @var array
     */
    protected $toggleKeysValues = [
        'product_group_collapse' => ['in', 'on'],
        'lte_sidebar_collapse' => [0, 1],
        'show_products_type_list' => [0, 1],
    ];

    /** @var array */
    protected $indexPageRouteNamesForBackAction = [
        //'admin.products.index',
        '*.index'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->beforeHandle($request);

        // set Destination URL
        $destinationKey = config('lte3.view.next_destination_key', '_destination');
        if ($request->get($destinationKey)) {
            $request->session()->put($destinationKey, $request->get($destinationKey));
        }

        // set Back URL
        if ($request->method() === 'GET' && $request->route() && !$request->expectsJson()) {
            $this->putIndexPageRouteNamesForBackAction($request);

            $this->putEnabledOptionKeys($request);

            if ($response = $this->toggleKeysValues($request)) {
                return $response;
            }
        }

        // set next open modal
        $modalKey = config('lte3.view.modal_key', '_modal');
        if ($modal = $request->get($modalKey)) {
            session()->flash('_modal', $modal);
        }

        $response = $next($request);

        $this->afterHandle($response);

        return $response;
    }

    /**
     * @param $request
     */
    protected function putIndexPageRouteNamesForBackAction(Request $request)
    {
        if (Str::is($this->indexPageRouteNamesForBackAction, $request->route()->getName())) {
            $request->session()->put($request->route()->getName(), $request->fullUrl());
        }
    }

    /**
     * @param $request
     */
    protected function putEnabledOptionKeys(Request $request)
    {
        foreach ($this->enabledOptionKeys as $key => $defaultValue) {
            if ($request->has($key)) {
                $request->session()->put($key, $request->get($key));
            }

            if (! $request->session()->has($key) && isset($defaultValue)) {
                $request->session()->put($key, $defaultValue);
            }
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|null
     */
    protected function toggleKeysValues(Request $request)
    {
        foreach ($this->toggleKeysValues as $key => $toggles) {
            if (is_array($toggles) && count($toggles) > 1) {
                if ($request->has($key) ) {
                    $oldToggleKey = array_search($request->session()->get($key), $toggles);
                    $newToggleValue = $oldToggleKey == 0 ? $toggles[1] : $toggles[0];

                    $request->session()->put($key, $newToggleValue);

                    return redirect($request->fullUrlWithQuery([
                        $key => null,
                    ]));
                } elseif (! $request->session()->has($key)) {
                    $newToggleValue = $toggles[0];

                    $request->session()->put($key, $newToggleValue);
                }
            }
        }

        return null;
    }

    /**
     * Set custom configs.
     *
     * @param Request $request
     */
    protected function beforeHandle(Request $request)
    {
        // \Config::set('key', 'value');
    }

    protected function afterHandle($request)
    {
        //...
    }
}
