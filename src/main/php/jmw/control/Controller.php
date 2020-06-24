<?php


namespace jmw\control;


use jmw\config\Config;
use jmw\main\View;
use jmw\routing\Router;
use ReflectionException;
use ReflectionMethod;

abstract class Controller {
    /**
     * @var Router
     */
    protected $router;
    /**
     * @var string
     */
    protected $controllerRelativePath;

    public function __construct(Router $router) {
        $this->controllerRelativePath = strtolower(str_replace(Config::JMW()['controller']['default_namespace'], '', get_class($this))).DIRECTORY_SEPARATOR;
        $this->router = $router;
    }

    protected function checkPageExists($page): bool {
        if(method_exists($this, $page)) {
            try {
                $reflection = new ReflectionMethod($this, $page);
                if($reflection->isPublic()) {
                    return true;
                }
            } catch(ReflectionException $e) {
                return false;
            }
        }

        return false;
    }

    protected function renderView($view, $data = null) {
        View::Render($view, $data, implode(DIRECTORY_SEPARATOR, explode('/', $this->controllerRelativePath)));
    }
}