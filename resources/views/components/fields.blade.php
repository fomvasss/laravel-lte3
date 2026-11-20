@php
    /*
     * Форма з опису полів: Lte3::fields($fields, $values, ['prefix' => 'settings']).
     * Поле: name, type, label, help, placeholder, required, default, options, url, depends_on; для type=list — fields, min, max.
     * Решта ключів опису йде в атрибути компонента як є (class_wrap, rows, ...).
     */
    $prefix = (string) ($attrs['prefix'] ?? '');
    $depth = (int) ($attrs['depth'] ?? 0);
    $values = is_array($values ?? null) ? $values : [];
    $ownKeys = ['name', 'type', 'fields', 'url', 'depends_on', 'default', 'options', 'min', 'max', 'add_label', 'empty_label'];
@endphp
@foreach($fields ?? [] as $field)
    @php
        $type = $field['type'] ?? 'text';
        $name = $prefix === '' ? $field['name'] : "{$prefix}[{$field['name']}]";
        $dot = lte3_dot_name($name);
        $value = array_key_exists($field['name'], $values) ? $values[$field['name']] : ($field['default'] ?? null);
        $params = \Illuminate\Support\Arr::except($field, $ownKeys);
    @endphp

    @if($type === 'list')
        @include('lte3::components.fields-list', compact('field', 'name', 'dot', 'value', 'depth'))
    @elseif($type === 'select' || $type === 'multiselect')
        @php
            $multiple = $type === 'multiselect';
            $selected = old($dot, $value);
            $options = $field['options'] ?? [];
            // Варіанти з url приходять запитом: вибране, якого немає в options, показується хоча б значенням,
            // інакше select2 виводить його з ключем 0 замість самого значення
            if (!empty($field['url'])) {
                $options = is_array($options) ? $options : [];
                foreach (array_filter(\Illuminate\Support\Arr::wrap($selected), fn ($v) => $v !== null && $v !== '') as $v) {
                    if (!collect($options)->contains(fn ($title, $key) => (string) (is_array($title) ? ($title['id'] ?? $title['value'] ?? $key) : $key) === (string) $v)) {
                        $options[$v] = $v;
                    }
                }
            }
            $params += array_filter([
                'multiple' => $multiple,
                'url_suggest' => $field['url'] ?? null,
                'empty_value' => !$multiple && empty($field['required']) ? '' : null,
                'data' => isset($field['depends_on']) ? ['depends-on' => $field['depends_on']] : null,
            ], fn ($v) => $v !== null && $v !== false);
        @endphp
        {!! Lte3::select2($multiple ? "{$name}[]" : $name, $selected, $options, $params) !!}
    @else
        @php
            if (config("lte3.view.components.{$type}.vars") !== ['name', 'value', 'attrs']) {
                throw new \InvalidArgumentException("Lte3::fields: unsupported field type '{$type}' ({$field['name']})");
            }
        @endphp
        {!! Lte3::{$type}($name, $value, $params) !!}
    @endif
@endforeach
