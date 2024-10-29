<?php

/**
 * This file contains some helpers functions to perform browser operations.
 *
 * The functions here are used in the context of a "bread" view, which is a
 * list view of a particular model. The functions are used to generate URLs
 * that will sort the list, filter the list, or reset the current query.
 *
 * @package admin
 * @since 1.0.0
 * @author Shahin Moyshan <shahin.moyshan2@gmail.com>
 * @license MIT
 */


if (!function_exists('sort_link')) {
    /**
     * Generates a query string for sorting a given field.
     *
     * This function toggles the sorting order of a specified field within a query string.
     * If the field is currently sorted in descending order, it will be updated to ascending,
     * and vice versa. If the field is not present in the current order, it will be added
     * in ascending order.
     *
     * @param string $field The field name to toggle in the sorting order.
     * @return string Modified query string with updated sorting order for the field.
     */
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
    /**
     * Get the position of the given field in the current sorting order.
     *
     * Given a field name, this function will return the position of that field in the current sorting order
     * (as specified by the 'o' query string parameter). The position is 1-indexed. If the field is not present
     * in the current sorting order, null is returned.
     *
     * @param string $field The field name to find the position of.
     * @return ?int The position of the field in the current sorting order, or null if not present.
     */
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
    /**
     * Get the sort type of the given field in the current sorting order.
     *
     * Given a field name, this function will return the sort type of that field in the current sorting order
     * (as specified by the 'o' query string parameter). The sort type is either 'asc' or 'desc'. If the field is not present
     * in the current sorting order, null is returned.
     *
     * @param string $field The field name to find the sort type of.
     * @return ?string The sort type of the field in the current sorting order, or null if not present.
     */
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
    /**
     * Return the current URL with the 'o' query string parameter removed.
     *
     * This function is useful for generating a URL that will reset the sorting order when clicked.
     * It is intended to be used in the context of a "bread" view, where the user is provided with
     * sorting controls.
     *
     * @return string The current URL with the 'o' query string parameter removed.
     */
    function sort_removed_url(): string
    {
        return filter_query_string(function ($query) {
            unset($query['o']);
            return $query;
        });
    }
}

if (!function_exists('query_removed_url')) {
    /**
     * Return the current URL with the 'q' query string parameter removed.
     *
     * This function is useful for generating a URL that will reset the current query when clicked.
     * It is intended to be used in the context of a "bread" view, where the user is provided with
     * query input controls.
     *
     * @return string The current URL with the 'q' query string parameter removed.
     */
    function query_removed_url()
    {
        return filter_query_string(function ($query) {
            unset($query['q']);
            return $query;
        });
    }
}

if (!function_exists('show_filter_count')) {
    /**
     * Add a filter count indicator to the query string.
     *
     * This function modifies the current query string to include a filter count indicator,
     * represented by the '_facts' parameter set to 'true'. This can be used to signal that
     * filters are currently being applied in the view.
     *
     * @return string Modified query string with the filter count indicator.
     */
    function show_filter_count()
    {
        return filter_query_string(function ($query) {
            $query['_facts'] = 'true';
            return $query;
        });
    }
}

if (!function_exists('hide_filter_count')) {
    /**
     * Remove the filter count indicator from the query string.
     *
     * This function removes the '_facts' parameter from the query string, which is used
     * to signal that filters are currently being applied in the view.
     *
     * @return string Modified query string with the filter count indicator removed.
     */
    function hide_filter_count()
    {
        return filter_query_string(function ($query) {
            unset($query['_facts']);
            return $query;
        });
    }
}

if (!function_exists('get_filter_url')) {
    /**
     * Generates a query string for filtering a given field.
     *
     * This function modifies the current query string to filter for the specified
     * field with the given value. If the value is boolean false, the filter is
     * removed from the query string.
     *
     * @param string $field The field to filter on.
     * @param mixed  $value The value to filter by.
     * @return string Modified query string with the filter applied.
     */
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
    /**
     * Clears all filters from the current query string.
     *
     * This function removes all query parameters that start with 'filter__',
     * effectively clearing any filters that have been applied to the view.
     *
     * @return string The modified query string with all filters removed.
     */
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
    /**
     * Get the current filters from the query string.
     *
     * This function loops over the current query string parameters and looks for
     * any that start with 'filter__', which are used to indicate filters in the
     * views. The 'filter__' prefix is removed from the parameter key to generate
     * a filter name.
     *
     * @return array The current filters, where each key is a filter name and each
     * value is the filter value.
     */
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
    /**
     * Modify the current query string with a callback.
     *
     * This function takes a callback and passes the current query string to it.
     * The callback should modify the query string and return it. The modified
     * query string is then used to generate a URL.
     *
     * @param callable $filter The callback to use to modify the query string.
     * @return string The modified URL.
     */
    function filter_query_string(callable $filter): string
    {
        $url = request_url();
        $query = [];
        if (strpos($url, '?') !== false) {
            $query = explode('?', $url);
            parse_str(end($query), $query);
        }

        $query = $filter($query);
        return (strpos($url, '?') !== false ? substr($url, 0, strpos($url, '?')) : $url) .
            (!empty($query) ? '?' . http_build_query($query) : '');
    }
}
