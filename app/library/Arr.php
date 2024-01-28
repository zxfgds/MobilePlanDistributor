<?php

namespace app\library;


use ArrayAccess;
use Exception;

class Arr
{
    
    /**
     * Determine whether the given value is array accessible.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public static function accessible(mixed $value): bool
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }
    
    /**
     * Add an element to an array using "dot" notation if it doesn't exist.
     *
     * @param array            $array
     * @param float|int|string $key
     * @param mixed            $value
     *
     * @return array
     */
    public static function add(array $array, float|int|string $key, mixed $value): array
    {
        if (is_null(static::get($array, $key))) {
            static::set($array, $key, $value);
        }
        
        return $array;
    }
    
    
    /**
     * Cross join the given arrays, returning all possible permutations.
     *
     * @param iterable ...$arrays
     *
     * @return array
     */
    public static function crossJoin(...$arrays): array
    {
        $results = [[]];
        
        foreach ($arrays as $index => $array) {
            $append = [];
            
            foreach ($results as $product) {
                foreach ($array as $item) {
                    $product[$index] = $item;
                    
                    $append[] = $product;
                }
            }
            
            $results = $append;
        }
        
        return $results;
    }
    
    /**
     * Divide an array into two arrays. One with keys and the other with values.
     *
     * @param array $array
     *
     * @return array
     */
    public static function divide(array $array): array
    {
        return [array_keys($array), array_values($array)];
    }
    
    /**
     * Flatten a multi-dimensional associative array with dots.
     *
     * @param iterable $array
     * @param string   $prepend
     *
     * @return array
     */
    public static function dot(iterable $array, string $prepend = ''): array
    {
        $results = [];
        
        foreach ($array as $key => $value) {
            if (is_array($value) && !empty($value)) {
                $results = array_merge($results, static::dot($value, $prepend . $key . '.'));
            } else {
                $results[$prepend . $key] = $value;
            }
        }
        
        return $results;
    }
    
    /**
     * Get all of the given array except for a specified array of keys.
     *
     * @param array                  $array
     * @param float|array|int|string $keys
     *
     * @return array
     */
    public static function except(array $array, float|array|int|string $keys): array
    {
        static::forget($array, $keys);
        
        return $array;
    }
    
    /**
     * Return the first element in an array passing a given truth test.
     *
     * @param iterable      $array
     * @param callable|null $callback
     * @param mixed|null    $default
     *
     * @return mixed
     */
    public static function first(iterable $array, callable $callback = NULL, mixed $default = NULL): mixed
    {
        if (is_null($callback)) {
            if (empty($array)) {
                return value($default);
            }
            
            foreach ($array as $item) {
                return $item;
            }
        }
        
        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                return $value;
            }
        }
        
        return value($default);
    }
    
    /**
     * Return the last element in an array passing a given truth test.
     *
     * @param array         $array
     * @param callable|null $callback
     * @param mixed|null    $default
     *
     * @return mixed
     */
    public static function last(array $array, callable $callback = NULL, mixed $default = NULL): mixed
    {
        if (is_null($callback)) {
            return empty($array) ? value($default) : end($array);
        }
        
        return static::first(array_reverse($array, TRUE), $callback, $default);
    }
    
    
    /**
     * Remove one or many array items from a given array using "dot" notation.
     *
     * @param array                  $array
     * @param float|array|int|string $keys
     *
     * @return void
     */
    public static function forget(array &$array, float|array|int|string $keys): void
    {
        $original = &$array;
        
        $keys = (array)$keys;
        
        if (count($keys) === 0) {
            return;
        }
        
        foreach ($keys as $key) {
            // if the exact key exists in the top-level, remove it
            if (static::exists($array, $key)) {
                unset($array[$key]);
                
                continue;
            }
            
            $parts = explode('.', $key);
            
            // clean up before each pass
            $array = &$original;
            
            while (count($parts) > 1) {
                $part = array_shift($parts);
                
                if (isset($array[$part]) && static::accessible($array[$part])) {
                    $array = &$array[$part];
                } else {
                    continue 2;
                }
            }
            
            unset($array[array_shift($parts)]);
        }
    }
    
    /**
     * Get an item from an array using "dot" notation.
     *
     * @param ArrayAccess|array $array
     * @param int|string|null   $key
     * @param mixed|null        $default
     *
     * @return mixed
     */
    public static function get(ArrayAccess|array $array, int|string|null $key, mixed $default = NULL): mixed
    {
        if (!static::accessible($array)) {
            return value($default);
        }
        
        if (is_null($key)) {
            return $array;
        }
        
        if (static::exists($array, $key)) {
            return $array[$key];
        }
        
        if (!str_contains($key, '.')) {
            return $array[$key] ?? value($default);
        }
        
        foreach (explode('.', $key) as $segment) {
            if (static::accessible($array) && static::exists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return value($default);
            }
        }
        
        return $array;
    }
    
    /**
     * Check if an item or items exist in an array using "dot" notation.
     *
     * @param ArrayAccess|array $array
     * @param array|string      $keys
     *
     * @return bool
     */
    public static function has(ArrayAccess|array $array, array|string $keys): bool
    {
        $keys = (array)$keys;
        
        if (!$array || $keys === []) {
            return FALSE;
        }
        
        foreach ($keys as $key) {
            $subKeyArray = $array;
            
            if (static::exists($array, $key)) {
                continue;
            }
            
            foreach (explode('.', $key) as $segment) {
                if (static::accessible($subKeyArray) && static::exists($subKeyArray, $segment)) {
                    $subKeyArray = $subKeyArray[$segment];
                } else {
                    return FALSE;
                }
            }
        }
        
        return TRUE;
    }
    
    /**
     * Determine if any of the keys exist in an array using "dot" notation.
     *
     * @param ArrayAccess|array $array
     * @param array|string      $keys
     *
     * @return bool
     */
    public static function hasAny(ArrayAccess|array $array, array|string $keys): bool
    {
        
        $keys = (array)$keys;
        
        if (!$array) {
            return FALSE;
        }
        
        if ($keys === []) {
            return FALSE;
        }
        
        foreach ($keys as $key) {
            if (static::has($array, $key)) {
                return TRUE;
            }
        }
        
        return FALSE;
    }
    
    /**
     * Determines if an array is associative.
     *
     * An array is "associative" if it doesn't have sequential numerical keys beginning with zero.
     *
     * @param array $array
     *
     * @return bool
     */
    public static function isAssoc(array $array): bool
    {
        $keys = array_keys($array);
        
        return array_keys($keys) !== $keys;
    }
    
    /**
     * Determines if an array is a list.
     *
     * An array is a "list" if all array keys are sequential integers starting from 0 with no gaps in between.
     *
     * @param array $array
     *
     * @return bool
     */
    public static function isList($array): bool
    {
        return !self::isAssoc($array);
    }
    
    /**
     * Join all items using a string. The final items can use a separate glue string.
     *
     * @param array  $array
     * @param string $glue
     * @param string $finalGlue
     *
     * @return string
     */
    public static function join(array $array, string $glue, $finalGlue = ''): string
    {
        if ($finalGlue === '') {
            return implode($glue, $array);
        }
        
        if (count($array) === 0) {
            return '';
        }
        
        if (count($array) === 1) {
            return end($array);
        }
        
        $finalItem = array_pop($array);
        
        return implode($glue, $array) . $finalGlue . $finalItem;
    }
    
    
    /**
     * Get a subset of the items from the given array.
     *
     * @param array        $array
     * @param array|string $keys
     *
     * @return array
     */
    public static function only(array $array, array|string $keys): array
    {
        return array_intersect_key($array, array_flip((array)$keys));
    }
    
    /**
     * Pluck an array of values from an array.
     *
     * @param iterable              $array
     * @param array|int|string|null $value
     * @param array|string|null     $key
     *
     * @return array
     */
    public static function pluck(iterable $array, array|int|string|null $value, array|string $key = NULL): array
    {
        $results = [];
        
        [$value, $key] = static::explodePluckParameters($value, $key);
        
        foreach ($array as $item) {
            $itemValue = data_get($item, $value);
            
            // If the key is "null", we will just append the value to the array and keep
            // looping. Otherwise we will key the array using the value of the key we
            // received from the developer. Then we'll return the final array form.
            if (is_null($key)) {
                $results[] = $itemValue;
            } else {
                $itemKey = data_get($item, $key);
                
                if (is_object($itemKey) && method_exists($itemKey, '__toString')) {
                    $itemKey = (string)$itemKey;
                }
                
                $results[$itemKey] = $itemValue;
            }
        }
        
        return $results;
    }
    
    /**
     * Explode the "value" and "key" arguments passed to "pluck".
     *
     * @param array|string      $value
     * @param array|string|null $key
     *
     * @return array
     */
    protected static function explodePluckParameters(array|string $value, array|string|null $key): array
    {
        $value = is_string($value) ? explode('.', $value) : $value;
        
        $key = is_null($key) || is_array($key) ? $key : explode('.', $key);
        
        return [$value, $key];
    }
    
    /**
     * Run a map over each of the items in the array.
     *
     * @param array    $array
     * @param callable $callback
     *
     * @return array
     */
    public static function map(array $array, callable $callback)
    {
        $keys = array_keys($array);
        
        try {
            $items = array_map($callback, $array, $keys);
        } catch (Exception) {
            $items = array_map($callback, $array);
        }
        
        return array_combine($keys, $items);
    }
    
    /**
     * Push an item onto the beginning of an array.
     *
     * @param array $array
     * @param mixed $value
     * @param mixed $key
     *
     * @return array
     */
    public static function prepend($array, $value, $key = NULL)
    {
        if (func_num_args() == 2) {
            array_unshift($array, $value);
        } else {
            $array = [$key => $value] + $array;
        }
        
        return $array;
    }
    
    /**
     * Get a value from the array, and remove it.
     *
     * @param array      $array
     * @param int|string $key
     * @param mixed|null $default
     *
     * @return mixed
     */
    public static function pull(array &$array, int|string $key, mixed $default = NULL): mixed
    {
        $value = static::get($array, $key, $default);
        
        static::forget($array, $key);
        
        return $value;
    }
    
    /**
     * Convert the array into a query string.
     *
     * @param array $array
     *
     * @return string
     */
    public static function query(array $array): string
    {
        return http_build_query($array, '', '&', PHP_QUERY_RFC3986);
    }
    
    
    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param array           $array
     * @param int|string|null $key
     * @param mixed           $value
     *
     * @return array
     */
    public static function set(array &$array, int|string|null $key, mixed $value): array
    {
        if (is_null($key)) {
            return $array = $value;
        }
        
        $keys = explode('.', $key);
        
        foreach ($keys as $i => $key) {
            if (count($keys) === 1) {
                break;
            }
            
            unset($keys[$i]);
            
            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }
            
            $array = &$array[$key];
        }
        
        $array[array_shift($keys)] = $value;
        
        return $array;
    }
    
    /**
     * Shuffle the given array and return the result.
     *
     * @param array    $array
     * @param int|null $seed
     *
     * @return array
     */
    public static function shuffle(array $array, int|null $seed = NULL): array
    {
        if (is_null($seed)) {
            shuffle($array);
        } else {
            mt_srand($seed);
            shuffle($array);
            mt_srand();
        }
        
        return $array;
    }
    
    
    /**
     * Recursively sort an array by keys and values.
     *
     * @param array $array
     * @param int   $options
     * @param bool  $descending
     *
     * @return array
     */
    public static function sortRecursive($array, $options = SORT_REGULAR, $descending = FALSE)
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                $value = static::sortRecursive($value, $options, $descending);
            }
        }
        
        if (static::isAssoc($array)) {
            $descending
                ? krsort($array, $options)
                : ksort($array, $options);
        } else {
            $descending
                ? rsort($array, $options)
                : sort($array, $options);
        }
        
        return $array;
    }
    
    /**
     * Conditionally compile classes from an array into a CSS class list.
     *
     * @param array $array
     *
     * @return string
     */
    public static function toCssClasses(array $array): string
    {
        $classList = static::wrap($array);
        
        $classes = [];
        
        foreach ($classList as $class => $constraint) {
            if (is_numeric($class)) {
                $classes[] = $constraint;
            } else if ($constraint) {
                $classes[] = $class;
            }
        }
        
        return implode(' ', $classes);
    }
    
    /**
     * Filter the array using the given callback.
     *
     * @param array    $array
     * @param callable $callback
     *
     * @return array
     */
    public static function where(array $array, callable $callback): array
    {
        return array_filter($array, $callback, ARRAY_FILTER_USE_BOTH);
    }
    
    /**
     * Filter items where the value is not null.
     *
     * @param array $array
     *
     * @return array
     */
    public static function whereNotNull(array $array): array
    {
        return static::where($array, function ($value) {
            return !is_null($value);
        });
    }
    
    /**
     * Determine if the given key exists in the provided array.
     *
     * @param ArrayAccess|array $array
     * @param int|string        $key
     *
     * @return bool
     */
    public static function exists(ArrayAccess|array $array, int|string $key): bool
    {
        
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }
    
        return array_key_exists($key, $array);
    }
    
    /**
     * If the given value is not an array and not null, wrap it in one.
     *
     * @param mixed $value
     *
     * @return array
     */
    public static function wrap($value): array
    {
        if (is_null($value)) {
            return [];
        }
        
        return is_array($value) ? $value : [$value];
    }
}