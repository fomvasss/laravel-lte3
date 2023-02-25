<?php

namespace Fomvasss\Lte3;

use Exception;

class Lte
{
    protected $model = null;
    protected array $componentsViews;
    protected array $fieldAttrs;

    public function __construct()
    {
        $this->componentsViews = config('lte3.view.components', []);
        $this->fieldAttrs = config('lte3.view.field_attrs', []);
    }

    public function __call($name, $attrs)
    {
        if (isset($this->componentsViews[$name]['blade'])) {
            $res['attrs'] = [];
            $i = 0;
            foreach($this->componentsViews[$name]['vars'] as $key) {
                $res[$key] = $attrs[$i++] ?? null;
            }

            if (empty($res['model'])) {
                $res['model'] = $this->model;
            }

            $defaultAttrs = $this->componentsViews[$name]['default'] ?? [];
            $res['attrs'] = array_merge($defaultAttrs, $res['attrs'] ?? []);

            $res['field_attrs'] = $this->fieldAttrs;

            return view($this->componentsViews[$name]['blade'], $res)->render();
        }

        throw new Exception("Lte3 method or component '{$name}' not found!");
    }

    public function formOpen(array $attrs = [])
    {
        $this->model = $attrs['model'] ?? null;

        $defaultAttrs = $this->componentsViews['form']['default'] ?? [];
        $attrs = array_merge($defaultAttrs, $attrs);

        return view($this->componentsViews['form']['blade'], ['attrs' => $attrs])->render();
    }

    public function formClose()
    {
        $this->model =  null;

        return '</form>';
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
}
