<?php

namespace App\Http;

use GuzzleHttp\Psr7\Response as GuzzleResponse;

/**
 * A response from a request from our App\Http class
 */
class Response
{
    /**
     * Response body
     *
     * @var string
     */
    protected string $response;

    /**
     * Constructor
     *
     * @param string|mixed $response
     */
    public function __construct($response)
    {
        $this->response = (string) $response;
    }

    /**
     * Return the response body as a string
     *
     * @return string
     */
    public function body(): string
    {
        return $this->response;
    }

    /**
     * Parse the response body as JSON & return it
     *
     * @return mixed
     */
    public function json()
    {
        return json_decode($this->response);
    }
}