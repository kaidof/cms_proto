<?php

declare(strict_types=1);

namespace core;

class Template
{
    /**
     * Template file
     *
     * @var
     */
    private $tpl;

    /**
     * Template data
     *
     * @var array
     */
    private static $data = [];

    /**
     * Is dev mode?
     *
     * @var bool
     */
    private $isDevMode = true;

    /**
     * Global template data
     *
     * @var array
     */
    private static $globalTplData = [];

    /**
     * Stack of layout files
     *
     * @var array
     */
    private static $layoutStack = [];

    /**
     * Is this a partial template?
     *
     * @var false|mixed
     */
    private $isPartial = false;

    public function __construct($tpl, $data = [], $isPartial = false)
    {
        $this->tpl = $tpl;
        $this->isPartial = $isPartial;

        if (!$this->isPartial) {
            self::$data = array_merge(self::$data, $data);
        }

        $this->isDevMode = !app()->environment('production');
    }

    /**
     * Set the layout of the template
     *
     * @param string $layoutFile
     * @param array $data
     * @return void
     */
    public static function setLayout($layoutFile, $data = [])
    {
        // FIXME: Do we need stack of layouts?
        // Push the layout file onto the stack
        self::$layoutStack[] = [
            'tpl' => $layoutFile,
            'data' => $data,
        ];
    }

    /**
     * Outputs the partial template
     *
     * @param string $tpl
     * @param array $data
     * @return void
     * @throws \Exception
     */
    public static function renderPartial($tpl, $data = [])
    {
        $view = new self($tpl, [], true);
        echo $view->render($tpl, $data);
    }

    /**
     * Render a template file and return the contents as a string
     *
     * @param $tpl
     * @param $data
     * @return string
     * @throws \Exception
     */
    private function processTemplate($tpl, $data = [])
    {
        if (!$this->isDevMode) {
            // turn off error reporting, get current level
            $errorReporting = error_reporting();
            error_reporting(0);
        }

        $originalTpl = $tpl;

        // is this a module template?
        $module = explode('::', $tpl, 2);
        if (count($module) > 1) {
            $tpl = 'modules' . DIRECTORY_SEPARATOR . $module[0] . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $module[1];
        } else {
            $tpl = 'core' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $module[0];
        }

        $path = constant('ROOT_DIR') . DIRECTORY_SEPARATOR . $tpl . '.php';

        // turn on output buffering
        ob_start();

        // extract variables to local scope
        extract(array_merge(self::$data, $data));

        try {
            if (file_exists($path) && is_readable($path)) {
                // include the template file
                include $path;
            } else {
                logger()->error('Template file does not exist', [ 'path' => $path ]);

                if ($this->isDevMode) {
                    throw new \RuntimeException("Template file [$originalTpl] is not readable");
                }
            }
        } catch (\Throwable $e) {
            // clear the buffer
            ob_end_clean();

            throw $e;
        }

        // get the contents of the buffer
        $content = ob_get_clean();

        if (!$this->isDevMode) {
            // restore the error reporting level
            error_reporting($errorReporting);
        }

        return $content ?: '';
    }

    /**
     * Render a template and return the contents as a string
     *
     * @param $tpl
     * @param $data
     *
     * @return string
     * @throws \Exception
     */
    public function render($tpl, $data = [])
    {
        try {
            $content = $this->processTemplate($tpl, array_merge(...array_filter([self::$globalTplData, $data])));

            // layout logic if in template we have set a layout
            if (!$this->isPartial && !empty(self::$layoutStack)) {

                // Get the top layout file from the stack and remove
                $layoutData = array_pop(self::$layoutStack);

                // Render the layout file and pass the content of the template
                $content = $this->processTemplate($layoutData['tpl'], array_merge(...array_filter([$layoutData['data'], self::$globalTplData, $data, [ 'content' => $content ]])));
            }

            return $content ?: '';
        } catch (\Throwable $e) {
            // clear the layout stack
            self::$layoutStack = [];

            throw $e;
        }
    }

    /**
     * Return a rendered template as a string
     *
     * @return string
     * @throws \Exception
     */
    public function __toString()
    {
        return $this->render($this->tpl, self::$data);
    }

    /**
     * Return a rendered template as a string
     *
     * @return string
     * @throws \Exception
     */
    public function fetch()
    {
        return $this->__toString();
    }

    /**
     * Echo a rendered template
     *
     * @return string
     * @throws \Exception
     */
    public function echo()
    {
        echo $this->__toString();
    }

    /**
     * Set template/layout variables
     *
     * @param mixed $name
     * @param mixed $value
     *
     * @return void
     */
    public static function setVariable($name, $value = null)
    {
        if (is_array($name)) {
            foreach ($name as $key => $value) {
                self::setVariable($key, $value);
            }
        } else {
            self::$globalTplData[$name] = $value;
        }
    }
}
