<?php

if (!function_exists('sort_link')) {
    function sort_link(string $field)
    {
        return filter_query_string(function ($query) use ($field) {
            $order = $query['o'] ?? '';
            if (strpos($order, "-{$field}") !== false) {
                $order = str_replace("-{$field}", $field, $order);
            } elseif (strpos($order, $field) !== false) {
                $order = str_replace($field, "-{$field}", $order);
            } else {
                $order .= ".{$field}";
            }
            $query['o'] = trim($order, '.');
            return $query;
        });
    }
}

if (!function_exists('sort_position')) {
    function sort_position(string $field): ?int
    {
        foreach (explode('.', request()->get('o', '')) as $key => $order) {
            if (strpos($order, $field) !== false) {
                return $key + 1;
            }
        }
        return null;
    }
}


if (!function_exists('sort_type')) {
    function sort_type(string $field): ?string
    {
        foreach (explode('.', request()->get('o', '')) as $order) {
            if (strpos($order, $field) !== false) {
                return strpos($order, '-') === 0 ? 'desc' : 'asc';
            }
        }
        return null;
    }
}

if (!function_exists('sort_removed_url')) {
    function sort_removed_url(): string
    {
        return filter_query_string(function ($query) {
            unset($query['o']);
            return $query;
        });
    }
}

if (!function_exists('query_removed_url')) {
    function query_removed_url()
    {
        return filter_query_string(function ($query) {
            unset($query['q']);
            return $query;
        });
    }
}

if (!function_exists('show_filter_count')) {
    function show_filter_count()
    {
        return filter_query_string(function ($query) {
            $query['_facts'] = 'true';
            return $query;
        });
    }
}

if (!function_exists('hide_filter_count')) {
    function hide_filter_count()
    {
        return filter_query_string(function ($query) {
            unset($query['_facts']);
            return $query;
        });
    }
}

if (!function_exists('get_filter_url')) {
    function get_filter_url(string $field, $value)
    {
        return filter_query_string(function ($query) use ($field, $value) {
            if (false === $value) {
                unset($query['filter__' . $field]);
            } else {
                $query['filter__' . $field] = $value;
            }
            return $query;
        });
    }
}

if (!function_exists('clear_filtered_url')) {
    function clear_filtered_url()
    {
        return filter_query_string(function ($query) {
            foreach (array_keys($query) as $k) {
                if (strpos($k, 'filter__') !== false) {
                    unset($query[$k]);
                }
            }
            return $query;
        });
    }
}

if (!function_exists('get_filters')) {
    function get_filters(): array
    {
        $filters = [];
        foreach (request()->queryParams as $k => $v) {
            if (strpos($k, 'filter__') !== false) {
                $filters[str_replace('filter__', '', $k)] = $v;
            }
        }
        return $filters;
    }
}

if (!function_exists('filter_query_string')) {
    function filter_query_string(callable $filter): string
    {
        $url = request_url();
        $query = [];
        if (strpos($url, '?') !== false) {
            $query = explode('?', $url);
            parse_str(end($query), $query);
        }
        $query = $filter($query);
        return (strpos($url, '?') !== false ? substr($url, 0, strpos($url, '?')) : $url) . (!empty($query) ? '?' . http_build_query($query) : '');
    }
}
