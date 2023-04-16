<?php

declare(strict_types=1);

namespace core;

class HookManager
{
    private static $hooks = [];
    private static $processedHooks = [];
    private static $instance;

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Add a hook
     *
     * @param string $hook The hook name
     * @param callable $callback The callback function
     * @param int $priority The priority of the hook, default is 10
     * @return void
     */
    public function add($hook, $callback, $priority = 10)
    {
        // add empty array for hook if not exists
        if (!isset(self::$hooks[$hook])) {
            self::$hooks[$hook] = [];
        }

        self::$hooks[$hook][$priority][] = $callback;
    }

    public function run($hook, $params = [])
    {
        if (isset(self::$hooks[$hook])) {
            // Sort the callbacks based on priority
            ksort(self::$hooks[$hook]);

            // init processed hooks array if not exists
            if (!isset(self::$processedHooks[$hook])) {
                // start from 0
                self::$processedHooks[$hook] = 0;
            }

            foreach (self::$hooks[$hook] as $priorityCallbacks) {
                foreach ($priorityCallbacks as $callback) {
                    // var_dump('HookManager: run BEFORE', $hook, $params);
                    // $params = call_user_func_array($callback, $params);
                    $params = call_user_func($callback, $params);
                    // var_dump('HookManager: run AFTER', $hook, $params);
                    self::$processedHooks[$hook]++;
                }
            }
        }

        return $params;
    }

    public function runOnce($hook, $params = [])
    {
        if (!isset(self::$processedHooks[$hook]) || self::$processedHooks[$hook] === 0) {
            $params = $this->run($hook, $params);
        }

        return $params;
    }

    /**
     * Remove hook
     *
     * @param string $hook
     * @param $callback
     * @return void
     */
    public function remove($hook, $callback)
    {
        if (isset(self::$hooks[$hook])) {
            foreach (self::$hooks[$hook] as $priority => $callbacks) {
                foreach ($callbacks as $key => $cb) {
                    if ($cb === $callback) {
                        unset(self::$hooks[$hook][$priority][$key]);
                    }
                }
            }
        }
    }

    /**
     * Is hook processed, at least once
     *
     * @param $hook
     * @return bool
     */
    public function isHookProcessed($hook)
    {
        return isset(self::$processedHooks[$hook]) && self::$processedHooks[$hook] > 0;
    }

    /**
     * How many times hook was processed
     *
     * @param string $hook
     * @return int
     */
    public function howManyTimesHookProcessed($hook)
    {
        return self::$processedHooks[$hook] ?? 0;
    }

    /**
     * Has hook been registered
     *
     * @param string $hook
     * @return bool
     */
    public function hasHook($hook)
    {
        return isset(self::$hooks[$hook]);
    }



    /*
    public static function add($hook, $callback)
    {
        if (!isset(self::$hooks[$hook])) {
            self::$hooks[$hook] = [];
        }

        self::$hooks[$hook][] = $callback;
    }

    public static function run($hook, $params = [])
    {
        if (isset(self::$hooks[$hook])) {
            foreach (self::$hooks[$hook] as $callback) {
                $params = call_user_func($callback, $params);
            }
        }

        return $params;
    }
    */
}


/*
hook()->add('hook_name', function ($params) {
    // do something
    return $params;
}, 99);

hook()->run('hook_name', ['param1', 'param2']);

hook()->runOnce('hook_name', ['param1', 'param2']);
hook()->hasHook('hook_name');
*/