<?php

if (! function_exists('treeview')) {
    /**
     * Построение массива для вывода на фронт (компонент treeview)
     *
     * @param array $tree
     * @param array $selected
     * @return array
     */
    function treeview(array $tree, array $selected = [])
    {
        $res = [];

        foreach ($tree as $branch) {
            if(count($branch['children']) < 1) {
                if($selected && in_array($branch['id'], $selected)) {
                    $res[] = ['id' => $branch['id'], 'text' => $branch['name'], 'selectable' => false, 'state' => ['expanded' => true, 'checked' => true]];
                } else {
                    $res[] = ['id' => $branch['id'], 'text' => $branch['name'], 'selectable' => false, 'state' => ['expanded' => true]];
                }
            } else {
                if($selected && in_array($branch['id'], $selected)) {
                    $res[] = ['id' => $branch['id'], 'text' => $branch['name'], 'selectable' => false, 'state'=>['expanded' => true, 'checked' => true], 'nodes' => treeview($branch['children'], $selected)];
                } else {
                    $res[] = ['id' => $branch['id'], 'text' => $branch['name'], 'selectable' => false, 'state' => ['expanded' => true], 'nodes' => treeview($branch['children'], $selected)];
                }
            }
        }

        return $res;
    }
}

if (! function_exists('build_linear_array_sort')) {
    /**
     * Построить линейный масив с дерева, для записи упорядоченных сущностей.
     *
     * @param array $tree_entities
     * @param int|null $parent_id
     * @param bool $use_parent
     * @return array
     */
    function build_linear_array_sort(array $tree_entities, $parent_id = null, bool $use_parent = true)
    {
        $result = [];

        foreach ($tree_entities ?? [] as $key => $entity) {
            $data = [];
            if (! empty($entity['id'])) {
                $data['id'] = $entity['id'];
                $data['weight'] = $key;
                $use_parent ? $data['parent_id'] = $parent_id : null;
                $result[] = $data;
                if (! empty($entity['children'])) {
                    $result = array_merge($result, build_linear_array_sort($entity['children'], $entity['id'], $use_parent));
                }
            }
        }

        return $result;
    }
}

if (! function_exists('human_filesize')) {
    /**
     * Показать обьем информации в удобном для человека виде.
     *
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    function human_filesize(int $bytes, int $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        // Uncomment one of the following alternatives
        $bytes /= pow(1024, $pow);

        // $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision).' '.$units[$pow];
    }
}

if (! function_exists('pagination_row_number')) {
    /**
     * Вывод номера сущности (например в таблице).
     *
     * @param \Illuminate\Pagination\LengthAwarePaginator $items
     * @param int $loopIndex
     * @return float|int
     */
    function pagination_row_number(\Illuminate\Pagination\LengthAwarePaginator $items, int $loopIndex)
    {
        $total = $items->total();

        return ($total - $items->perPage() * ($items->currentPage()-1)) - $loopIndex;
    }
}

if (! function_exists('explode_assoc')) {
    /**
     * Перевод строки в асоциативный массив.
     *
     * @param string $string (ex: 1:106;2:110;3:112;4:108;5:235;7:239;8:237)
     * @return array (ex: [1 => 106, 2 => 210, ...])
     */
    function explode_assoc(string $string = '') {
        $res = [];

        foreach (explode(';', $string) as $item) {
            $el = explode(':', $item);
            if (isset($el[0]) && isset($el[1])) {
                $res[$el[0]] = $el[1];
            }
        }

        return $res;
    }
}

if (! function_exists('array_values_recursive')) {
    /**
     * @param array $ary
     * @return array
     */
    function array_values_recursive(array $ary)
    {
        $lst = [];
        foreach (array_keys($ary) as $k) {
            $v = $ary[$k];
            if (is_scalar($v)) {
                $lst[] = $v;
            } elseif (is_array($v)) {
                $lst = array_merge($lst, array_values_recursive($v));
            }
        }

        return $lst;
    }
}

if (! function_exists('string_to_color_code')) {
    /**
     * @param string $str
     * @return string
     */
    function string_to_color_code(string $str): string
    {
        $code = dechex(crc32($str));

        return substr($code, 0, 6);
    }
}

if (! function_exists('old_contain')) {
    /**
     * @param null $key
     * @param null $needle
     * @return bool|void
     */
    function old_contain($key = null, $needle = null)
    {
        $old = old($key);

        if (is_array($old)) {
            return in_array($needle, $old);
        }
        elseif (is_scalar($old)) {
            return $needle == $old;
        }

        return false;
    }
}

if (! function_exists('old_request')) {
    /**
     * @param $key
     * @param $default
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\Request|mixed|string|null
     */
    function old_request($key, $default = null)
    {
       return request($key, old($key, $default));
    }
}

if (! function_exists('comparison_bool')) {
    /**
     * @param $value
     * @return bool
     */
    function comparison_bool($value)
    {
        return $value === "true" || $value === "1" || $value === true || $value === 1;
    }
}

if (! function_exists('is_array_assoc')) {
    /**
     * @param array $arr
     * @return bool
     */
    function is_array_assoc(array $arr)
    {
        if ($arr === []) {
            return false;
        }

        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
