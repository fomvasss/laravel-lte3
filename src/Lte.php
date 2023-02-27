<?php

namespace Fomvasss\Lte3;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;

class Lte
{
    protected $model = null;

    public function __call($name, $attrs)
    {
        $componentParams = config("lte3.view.components.{$name}");
        $fieldAttrs = config('lte3.view.field_attrs', []);

        if ($componentParams['blade'] ?? '') {
            $res['attrs'] = [];
            $i = 0;
            foreach($componentParams['vars'] ?? [] as $key) {
                $res[$key] = $attrs[$i++] ?? null;
            }

            if (isset($res['name']) && array_key_exists('value', $res)) {
                $res['value'] = $this->getValueAttribute($res['name'], $res['value']);
            }

            if (empty($res['model'])) {
                $res['model'] = $this->model;
            }

            $defaultAttrs = $componentParams['default'] ?? [];
            $res['attrs'] = array_merge($defaultAttrs, $res['attrs'] ?? []);

            $res['field_attrs'] = $fieldAttrs;

            return view($componentParams['blade'], $res)->render();
        }

        throw new Exception("Lte3 method or component '{$name}' not found!");
    }

    public function formOpen(array $attrs = [])
    {
        $form = config('lte3.view.components.form', []);

        $this->model = $attrs['model'] ?? null;

        $defaultAttrs = $form['default'] ?? [];
        $attrs = array_merge($defaultAttrs, $attrs);

        return view($form['blade'], ['attrs' => $attrs])->render();
    }

    public function formClose()
    {
        $this->model =  null;

        return '</form>';
    }

    public function pagination($models)
    {
        if ($models instanceof LengthAwarePaginator) {
            $params = config("lte3.view.pagination");

            Paginator::defaultView($params['view']);
            Paginator::defaultSimpleView($params['simple_view']);

            return $models->appends(\Request::except('page'))->render();
        }

        Log::error(__METHOD__ . "Argument #1 must be of type Illuminate\Contracts\Pagination\LengthAwarePaginator");

        return '';
    }

    public function user($column = null)
    {
        if ($user = auth()->user()) {

            if ($column) {
                return $user->{$column};
            }

            return $user;
        }

        return null;
    }

    public function getValueAttribute($name, $value = null)
    {
        if (is_null($name)) {
            return $value;
        }

        $old = old($this->transformKey($name));

        if (! is_null($old) && $name !== '_method') {
            return $old;
        }

        if (function_exists('app')) {
            $hasNullMiddleware = app("Illuminate\Contracts\Http\Kernel")
                ->hasMiddleware(ConvertEmptyStringsToNull::class);

            if ($hasNullMiddleware
                && is_null($old)
                && is_null($value)
                && !is_null(view()->shared('errors'))
                && count(is_countable(view()->shared('errors')) ? view()->shared('errors') : []) > 0
            ) {
                return null;
            }
        }

        $request = request($this->transformKey($name));

        if (! is_null($request) && $name != '_method') {
            return $request;
        }

        if (! is_null($value)) {
            return $value;
        }

        if (isset($this->model)) {
            return $this->getModelValueAttribute($name);
        }
    }

    protected function getModelValueAttribute($name)
    {
        $key = $this->transformKey($name);

        if ((is_string($this->model) || is_object($this->model)) && method_exists($this->model, 'getFormValue')) {
            return $this->model->getFormValue($key);
        }

        return data_get($this->model, $key);
    }

    protected function transformKey($key)
    {
        return str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $key);
    }
}
