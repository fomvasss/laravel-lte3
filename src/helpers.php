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

        foreach ($tree_entities[0] ?? [] as $key => $entity) {
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
     * Показати обєм інформації в зручному вигляді.
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

if (! function_exists('human_duration')) {
    /**
     * Показать тривалість в зручному вигляді.
     *
     * @param int $seconds
     * @param string $format
     * @return string
     */
    function human_duration(int $seconds, string $format = 'H:i:s')
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;

        return sprintf('%d:%02d:%02d', $hours, $minutes, $secs);
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


if (! function_exists('array_search_assoc')) {
    /**
     * Знайти асоціативний елемент в асоц. масиві.
     *
     * @param array $needle
     * @param array $haystack
     * @param bool $returnIndex
     * @return false|int|mixed|string
     */
    function array_search_assoc(array $needle, array $haystack, $returnItem = false)
    {
        $keys = array_keys($needle);
        foreach ($haystack as $n => $item) {
            $break = false;
            foreach ($keys as $key) {
                if ($item[$key] != $needle[$key]) {
                    $break = true;
                    break;
                }
            }
            if (!$break) {
                return $returnItem ? $item : $n;
            }
        }

        return false;
    }
}

if(!function_exists('rand_color'))
{
    /**
     * @return string
     */
    function rand_color(): string
    {
        return '#' . substr(md5(mt_rand()), 0, 6);
    }
}

if (! function_exists('url_add_params')) {
    /**
     * @param string $url
     * @param array $paramsToAdd
     * @return string
     */
    function url_add_params(string $url, array $paramsToAdd = [])
    {
        if ($paramsToAdd === []) {
            return $url;
        }

        // Розділяємо URL на шлях та GET-параметри
        $urlParts = parse_url($url);

        // Визначаємо протокол (якщо відсутній)
        $protocol = isset($urlParts['scheme']) ? $urlParts['scheme'] . '://' : 'https://';

        // Визначаємо шлях
        $path = isset($urlParts['path']) ? $urlParts['path'] : '';

        // Визначаємо GET-параметри
        $query = isset($urlParts['query']) ? $urlParts['query'] : '';

        // Розбиваємо наявні GET-параметри на масив
        parse_str($query, $existingParams);

        // Об'єднуємо наявні та додаткові GET-параметри
        $combinedParams = array_merge($existingParams, $paramsToAdd);

        // Перетворюємо масив параметрів в рядок
        $newQuery = http_build_query($combinedParams);

        // Збираємо оновлений URL
        $newUrl = $protocol . $urlParts['host'] . $path . '?' . $newQuery;

        return $newUrl;
    }
}
