<?php

use App\View;
use Symfony\Component\Dotenv\Dotenv;
use Tightenco\Collect\Support\Collection;
use Symfony\Component\VarDumper\VarDumper;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/../.env');

if (!function_exists('collect')) {
    /**
     * Shortcut to creating a new Collection instance
     *
     * @param mixed $parameters one or more parameters
     * @return \Tightenco\Collect\Support\Collection
     */
    function collect(...$parameters)
    {
        return new Collection(...$parameters);
    }
}

if (!function_exists('dd')) {
    /**
     * Die & dump the passed parameter
     *
     * @param mixed $dump
     * @return never
     */
    function dd($dump)
    {
        VarDumper::dump($dump);
        exit();
    }
}

if (!function_exists('view')) {
    /**
     * Creates & renders a view
     *
     * @param string $name
     * @param array $data
     * @return string
     */
    function view($name, $data)
    {
        return (new View($name))
            ->render($data);
    }
}

if (!function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    function env($key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }
}

if (!function_exists('encode')) {
    /**
     * Encode HTML special characters in a string.
     *
     * @param string $value
     * @return string
     */
    function encode($value)
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('tap')) {
    /**
     * Call the given Closure with the given value then return the value.
     *
     * @param  mixed  $value
     * @param  callable  $callback
     * @return mixed
     */
    function tap($value, $callback)
    {
        $callback($value);

        return $value;
    }
}
