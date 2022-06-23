<?php

namespace App\Testing;

use Closure;
use App\View;
use App\Testing\Constraints\SeeInOrder;
use PHPUnit\Framework\Assert as PHPUnit;
use Tightenco\Collect\Support\Collection;
use PHPUnit\Framework\TestCase as PhpUnitTestCase;

class TestResponse extends PhpUnitTestCase
{
    /**
     * Response content
     *
     * @var string
     */
    protected string $response;

    /**
     * Constructor
     *
     * @param string $response
     */
    public function __construct(string $response)
    {
        $this->response = $response;
    }

    /**
     * Assert that the given string or array of strings are contained within the response.
     *
     * @param  string|array  $value
     * @param  bool  $escape
     * @return self
     */
    public function assertSee($value, $escape = true)
    {
        $value = $this->toArray($value);

        $values = $escape ? array_map('encode', ($value)) : $value;

        foreach ($values as $value) {
            PHPUnit::assertStringContainsString((string) $value, $this->getContent());
        }

        return $this;
    }

    /**
     * Assert that the given strings are contained in order within the response.
     *
     * @param  array  $values
     * @param  bool  $escape
     * @return self
     */
    public function assertSeeInOrder(array $values, $escape = true)
    {
        $values = $escape ? array_map('encode', ($values)) : $values;

        PHPUnit::assertThat($values, new SeeInOrder($this->getContent()));

        return $this;
    }


    /**
     * Assert that the given string or array of strings are contained within the response text.
     *
     * @param  string|array  $value
     * @param  bool  $escape
     * @return $this
     */
    public function assertSeeText($value, $escape = true)
    {
        $value = $this->toArray($value);

        $values = $escape ? array_map('encode', ($value)) : $value;

        tap(strip_tags($this->getContent()), function ($content) use ($values) {
            foreach ($values as $value) {
                PHPUnit::assertStringContainsString((string) $value, $content);
            }
        });

        return $this;
    }

    /**
     * Assert that the given strings are contained in order within the response text.
     *
     * @param  array  $values
     * @param  bool  $escape
     * @return $this
     */
    public function assertSeeTextInOrder(array $values, $escape = true)
    {
        $values = $escape ? array_map('encode', ($values)) : $values;

        PHPUnit::assertThat($values, new SeeInOrder(strip_tags($this->getContent())));

        return $this;
    }


    /**
     * Assert that the given string or array of strings are not contained within the response.
     *
     * @param  string|array  $value
     * @param  bool  $escape
     * @return self
     */
    public function assertDontSee($value, $escape = true)
    {
        $value = $this->toArray($value);

        $values = $escape ? array_map('encode', ($value)) : $value;

        foreach ($values as $value) {
            PHPUnit::assertStringNotContainsString((string) $value, $this->getContent());
        }

        return $this;
    }

    /**
     * Assert that the response view has a given piece of bound data.
     *
     * @param  string|array  $key
     * @param  mixed  $value
     * @return $this
     */
    public function assertViewHas($key, $value = null)
    {
        if (is_array($key)) {
            return $this->assertViewHasAll($key);
        }

        $viewData = $this->viewData();
        if (is_null($value)) {
            PHPUnit::assertTrue($viewData->has($key));
        } elseif ($value instanceof Closure) {
            PHPUnit::assertTrue($value($viewData->get($key)));
        } else {
            PHPUnit::assertEquals($value, $viewData->get($key));
        }

        return $this;
    }

    /**
     * Assert that the response view has a given list of bound data.
     *
     * @param  array  $bindings
     * @return $this
     */
    public function assertViewHasAll(array $bindings)
    {
        foreach ($bindings as $key => $value) {
            if (is_int($key)) {
                $this->assertViewHas($value);
            } else {
                $this->assertViewHas($key, $value);
            }
        }

        return $this;
    }

    /**
     * Return the content of the response
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->response;
    }

    /**
     * Ensure the passed parameter is an array
     *
     * @param mixed|array $value
     * @return array
     */
    protected function toArray($value): array
    {
        if (!is_array($value)) {
            $value = [$value];
        }

        return $value;
    }

    /**
     * Retrieve views that were rendered
     *
     * @return array
     */
    public function rendered(): array
    {
        return View::spied();
    }

    /**
     * Retrieve a Collection of data passed to the last rendered view
     *
     * @return \Tightenco\Collect\Support\Collection
     */
    public function viewData(): Collection
    {
        $rendered = collect($this->rendered() ?: [])
            ->pop();

        $data = collect($rendered)->get('data') ?: [];

        return collect($data);
    }
}
