<?php

namespace App;

/**
 * A super-simple template engine for us to use as a view
 */
class View
{
    /**
     * Name of the view file
     *
     * @var string
     */
    protected string $name;

    /**
     * Spy on rendered views
     *
     * @var array
     */
    protected static array $spy = [];

    /**
     * Constructor
     *
     * @param string $name name of the view template file
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Render the template
     *
     * @param array|\Tightenco\Collect\Support\Collection $data data to make
     *     available to the view
     * @return string parsed template
     */
    public function render($data = [])
    {
        // spy on what is being rendered
        self::$spy[] = [
            'name' => $this->name,
            'data' => $data,
        ];

        ob_start();

        if (!is_array($data)) {
            $data = $data->all();
        }

        // make template variables availabe
        extract($data);

        $name = preg_replace('/[\.\/]/', '', $this->name);
        include('views/' . $name . '.view.php');

        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * Reset any spies
     *
     * @return void
     */
    public static function reset(): void
    {
        self::$spy = [];
    }

    /**
     * Return anything that has been spied
     *
     * @return array
     */
    public static function spied(): array
    {
        return self::$spy;
    }
}
