<?php

declare(strict_types=1);

namespace core;

class Assets
{
    private static $instance;
    private $assets = [];

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Add a new asset to the assets array
     *
     * @param string $path The path to the asset
     * @param string $type Example: css, js, header_js
     * @param bool $before Whether to add the asset before or after the other assets
     * @return void
     */
    public function add($path, $type = 'js', $before = false)
    {
        // if before is true, add to the beginning of the array
        if ($before) {
            array_unshift($this->assets[$type], [
                'path' => $path,
            ]);
        } else {
            $this->assets[$type][] = [
                'path' => $path,
            ];
        }
    }

    /**
     * @param string $type
     * @return array
     */
    public function getByType($type)
    {
        $list = [];

        if (isset($this->assets[$type])) {
            foreach ($this->assets[$type] as $asset) {
                $list[] = $asset['path'];
            }
        }

        return array_unique(array_filter($list));
    }

}