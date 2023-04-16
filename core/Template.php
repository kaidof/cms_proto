<?php

declare(strict_types=1);

namespace core;

/**
 * FIXME: add ability to use layouts in templates
 * FIXME: add ability to use partials in templates
 */
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
    private $data = [];

    /**
     * Is dev mode?
     *
     * @var bool
     */
    private $isDevMode = true;

    /**
     * Parent template (layout)
     *
     * @var null
     */
    private static $parentTpl = null;

    /**
     * Parent (layout) template data
     *
     * @var array
     */
    private static $parentTplData = [];

    public function __construct($tpl, $data = [])
    {
        $this->tpl = $tpl;
        $this->data = $data;

        $this->isDevMode = !app()->environment('production');
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
            $errorReporting = error_reporting(0);
            error_reporting(0);
        }

        // turn on output buffering
        ob_start();

        // extract variables to local scope
        extract($data);

        // is this a module template?
        $module = explode('::', $tpl, 2);
        if (count($module) > 1) {
            $tpl = 'modules' . DIRECTORY_SEPARATOR . $module[0] . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $module[1];
        } else {
            $tpl = 'core' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $module[0];
        }

        $path = constant('ROOT_DIR') . DIRECTORY_SEPARATOR . $tpl . '.php';

        if (file_exists($path) && is_readable($path)) {
            // include the template file
            include $path;
        } else {
            logger()->error('Template file does not exist', [ 'path' => $path ]);

            if ($this->isDevMode) {
                // throw new \InvalidArgumentException('Template file does not exist');
                throw new \RuntimeException('Template file is not readable');
            }
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
        $content = $this->processTemplate($tpl, $data);

        // layout logic if we have a parent template set
        if (self::$parentTpl) {
            $content = $this->processTemplate(self::$parentTpl, array_merge(self::$parentTplData, $this->data, [ 'content' => $content ]));
        }

        return $content ?: '';
    }

    /**
     * Output a rendered template as a string
     *
     * @param string $tpl
     * @param array $data
     *
     * @return void
     * @throws \Exception
     */
    public function display($tpl, $data = [])
    {
        echo $this->render($tpl, $data);
    }

    /**
     * Output a json response
     *
     * @return string
     * @throws \Exception
     */
    public function __toString()
    {
        return $this->render($this->tpl, $this->data);
    }

    /**
     * Return the rendered template
     *
     * @return string
     * @throws \Exception
     */
    public function fetch()
    {
        return $this->__toString();
    }

    /**
     * Add parent template or layout
     *
     * @param string|null $tpl
     * @return $this
     */
    public function parent($tpl)
    {
        self::$parentTpl = $tpl;

        return $this;
    }

    /**
     * Set layout template
     *
     * @param string $tpl
     * @param array $data
     *
     * @return void
     */
    public static function layout($tpl, $data = [])
    {
        self::$parentTpl = $tpl;
        self::$parentTplData = $data;
    }
}
