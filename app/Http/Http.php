<?php

namespace App\Http;

use App\Http\Response;
use GuzzleHttp\Client;

/**
 * A basic Http class that wraps Guzzle & makes it a bit easier to request data
 * & test.
 */
class Http
{
    /**
     * A fake response to return instead of performing requests
     *
     * @var string|null
     */
    protected static ?string $fake = null;

    /**
     * Execute a HTTP GET request using Guzzle. Accepts an optional array of
     * querystring parameters
     *
     * @param string $endpoint
     * @param array $querystring querystring parameters to pass
     * @return mixed
     */
    public static function get(string $endpoint, array $querystring)
    {
        if (!is_null(self::$fake)) {
            return new Response(self::$fake);
        }

        $client = new Client();
        $options = [];
        if ($querystring) {
            $options['query'] = $querystring;
        }

        $response = $client->request('GET', $endpoint, $options);

        return new Response($response->getBody());
    }

    /**
     * Fake all future http response(s) to be the specified response. Accepts a
     * string, or an array (which will be treated as a JSON response)
     *
     * @param string|array $response
     * @return void
     */
    public static function fake($response): void
    {
        if (!is_string($response)) {
            $response = json_encode($response);
        }

        self::$fake = $response;
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
