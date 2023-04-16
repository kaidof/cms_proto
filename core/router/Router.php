<?php

declare(strict_types=1);

namespace core\router;

class Router
{
    /**
     * Holds all routes
     *
     * @var array
     */
    public static $routes = [];

    /**
     * Holds all groups of routes when using group() method
     *
     * @var array
     */
    private static $groupStack = [];

    /**
     * Holds current route array
     *
     * @var array
     */
    private static $currentRouteArray = null;

    /**
     * @param string $method HTTP method
     * @param string $path URL path
     *
     * @return void
     */
    public static function dispatch($method, $path)
    {
        try {
            logger()->debug('ROUTER: start ' . $method . ' ' . $path);

            $response = self::handleRequest($method, $path);

            if ($response instanceof \core\Template) {
                // response is view
                echo $response;
            } elseif ($response instanceof \core\router\Response) {
                // response is response object
                $response->send();
            } elseif ($response instanceof \core\router\Redirect) {
                // response is redirect object

                logger()->debug('ROUTER: redirect to ' . $response->getPath());
                $response->to($response->getPath());
            } else {
                // response is string
                echo $response;
            }
        } catch (\core\router\NoRouteException $e) {
            // no route found
            logger()->debug('ROUTER: No route found for ' . $method . ' ' . $path);

            response()->setStatusCode(404)->send();
        } catch (\core\router\NoAuthException $e) {
            logger()->debug('ROUTER: No auth for ' . $method . ' ' . $path);

            if (request()->isJsonRequest()) {
                response()->json([
                    'error' => $e->getMessage(),
                ], 401)->send();
            } else {
                // FIXME: is this the right way to do this?
                redirect()->route('admin.login.index')->doAction();
            }
        } catch (\Throwable $e) {
            logger()->error('ROUTER ERROR: ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            // is server request json format?
            if (request()->isJsonRequest()) {
                // send json response

                $payload = [
                    'error' => $e->getMessage(),
                ];

                if (app()->environment('development', 'testing')) {
                    $payload['trace'] = $e->getTraceAsString();
                }

                // send json response
                response()->json($payload, 500)->send();
            } else {
                // send html response
                echo view('error', [
                    'error' => $e->getMessage(),
                    'trace' => app()->environment('development', 'testing') ? $e->getTraceAsString() : null,
                ])->parent(null);
            }
        }
    }

    /**
     * Returns controller response
     *
     * @param string $method
     * @param string $uri
     * @return mixed
     * @throws \Exception
     */
    public static function handleRequest($method, $path)
    {
        // only path, without query string
        $path = explode('?', $path)[0];
        $path = rtrim($path, '/');

        if (empty($path)) {
            $path = '/';
        }

        $method = strtoupper($method);

        if (isset(self::$routes[$method])) {
            foreach (self::$routes[$method] as $routePath => $callback) {
                $matches = [];
                if (preg_match('#^' . $routePath . '$#', $path, $matches)) {
                    $params = [];
                    foreach ($matches as $key => $value) {
                        if (is_string($key)) {
                            $params[$key] = $value;
                        }
                    }

                    self::$currentRouteArray = $callback;

                    // process middleware(s)
                    if (isset($callback['middleware'])) {
                        foreach ($callback['middleware'] as $middleware) {
                            // FIXME: add user role middleware - role:admin,customer,guest

                            if (class_exists($middleware)) {
                                $middlewareClassName = $middleware;
                            } else {
                                // FIXME: check if class exists, if not, throw exception
                                $middlewareClassName = __NAMESPACE__ . '\\' . ucfirst($middleware) . 'Middleware';
                            }

                            // check if class exists, if not, throw exception
                            if (class_exists($middlewareClassName)) {
                                $middlewareClass = new $middlewareClassName();

                                if (is_callable([$middlewareClassName, 'handle'])) {
                                    // throw exception
                                    throw new \Exception('Middleware class ' . $middlewareClassName . ' has no handle() method');
                                }

                                $middlewareClass->handle();
                            } else {
                                // throw exception
                                throw new \Exception('Middleware class ' . $middlewareClassName . ' not found');
                            }
                        }
                    }

                    if (is_array($callback['callback']) && is_string($callback['callback'][0])) {
                        // initialize class
                        $callback['callback'][0] = new $callback['callback'][0];
                    }

                    // add params to request object
                    // request()->setParams($params);

                    return call_user_func_array($callback['callback'], $params);
                }
            }
        }

        // FIXME: or throw exception?
        //http_response_code(404);
        //echo "Page not found";

        throw new NoRouteException('No route found for ' . $path);
    }

    private static function getGroupPrefix()
    {
        $prefix = '';
        foreach (self::$groupStack as $group) {
            if (isset($group['prefix'])) {
                $prefix .= '/' . $group['prefix'];
            }
        }

        return '/' . self::trim($prefix);
    }

    /**
     * @return array
     */
    private static function getGroupMiddleware()
    {
        $middleware = [];
        foreach (self::$groupStack as $group) {
            if (isset($group['middleware'])) {
                if (is_array($group['middleware'])) {
                    $middleware = array_merge($middleware, $group['middleware']);
                } else {
                    $middleware[] = $group['middleware'];
                }
            }
        }

        return array_unique($middleware);
    }

    private static function prependGroupPrefix($path)
    {
        // add web prefix
        $prefix = config()->get('web_root', '') . '/' . self::getGroupPrefix();

        // remove duplicate slashes
        return preg_replace('#/+#', '/', $prefix . '/' . rtrim($path, '/'));
    }

    /**
     * @param string $method
     * @param string $path
     * @param callable $callback
     * @return Route
     */
    public static function add($method, $path, $callback)
    {
        $method = strtoupper($method);
        $path = self::prependGroupPrefix($path);

        if ($path !== '/') {
            $path = rtrim($path, '/');
        }

        $rawPath = $path;
        $obj = new Route();

        $path = preg_replace('/{([a-z]+)}/', '(?P<\1>[a-zA-Z0-9-]+)', $path);

        self::$routes[$method][$path] = [
            'callback' => $callback,
            'middleware' => self::getGroupMiddleware(),
            'path' => $rawPath,
            'obj' => $obj,
        ];

        return $obj;
    }

    public static function group($options, $callback)
    {
        self::$groupStack[] = $options;
        call_user_func($callback);
        array_pop(self::$groupStack);
    }

    private static function trim($path)
    {
        return trim($path, '/');
    }

    /**
     * Return URL by route name
     *
     * @param string $name
     * @param array $params
     *
     * @return int|string|void
     */
    public static function getRoute($name, $params = [])
    {
        foreach (self::$routes as $method => $routes) {
            foreach ($routes as $routePath => $callback) {
                if (isset($callback['obj']) && $callback['obj'] instanceof Route && $callback['obj']->getName() == $name) {
                    // $path = $callback['obj']->getPath();
                    $path = $callback['path'];

                    $addToPath = [];

                    // add params to URL
                    foreach ($params as $key => $value) {
                        // is param in route?
                        if (strpos($path, '{' . $key . '}') !== false) {
                            $path = str_replace('{' . $key . '}', (string)$value, $path);
                        } else {
                            $addToPath[$key] = $value;
                        }
                    }

                    if (count($addToPath)) {
                        // build query string from array
                        $path .= '?' . http_build_query($addToPath);
                    }

                    return $path;
                }
            }
        }
    }

    /*
    public function getCurrentUrl() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $uri = $_SERVER['REQUEST_URI'];
        return $protocol . '://' . $host . $uri;
    }
    */

    public static function currentRouteName()
    {
        if (self::$currentRouteArray && isset(self::$currentRouteArray['obj']) && self::$currentRouteArray['obj'] instanceof Route) {
            return self::$currentRouteArray['obj']->getName();
        }

        return null;


        /*
        $currentUrl = self::getCurrentUrl();

        foreach (self::$routes as $method => $routes) {
            foreach ($routes as $routePath => $callback) {
                if (isset($callback['obj']) && $callback['obj'] instanceof Route) {
                    $route = $callback['obj'];

                    if ($route->getFullUrl() == $currentUrl) {
                        return $route->getName();
                    }
                }
            }
        }
        */
    }
}
