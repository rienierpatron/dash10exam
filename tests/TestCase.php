<?php

namespace Tests;

use App\Request;
use App\Testing\TestResponse;
use PHPUnit\Framework\TestCase as PhpUnitTestCase;

class TestCase extends PhpUnitTestCase
{
    /**
     * Helper function to execute a requested route & retrieve the results
     *
     * @param string $route
     * @param array|null $querystring optional array of querystring parameters
     */
    public function get(string $route, ?array $querystring = null)
    {
        Request::fake($querystring ?? []);

        ob_start();

        include(__DIR__ . '/../' . $route);

        $content = ob_get_contents();
        ob_end_clean();

        return new TestResponse($content);
    }
}
