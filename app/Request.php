<?php

namespace App;

/**
 * Handles an incoming request, provides access to querystring variables
 */
class Request
{
    /**
     * Fake incoming querystring arguments
     */
    protected static ?array $fake = null;

    /**
     * Retrieve a querystring parameter
     *
     * @param string $name
     * @return string|null
     */
    public function query(string $name): ?string
    {
        return is_null(self::$fake) ? $_REQUEST[$name] : self::$fake[$name];
    }

    /**
     * Fake the querystring parameters to this page
     *
     * @param array $querystring
     * @return void
     */
    public static function fake($querystring): void
    {
        self::$fake = $querystring;
    }

    /**
     * Reset all fakes
     *
     * @return void
     */
    public static function reset(): void
    {
        self::$fake = null;
    }
}
