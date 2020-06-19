<?php


namespace jmw\control;


use jmw\config\Config;
use jmw\main\View;
use jmw\routing\Router;
use ReflectionException;
use ReflectionMethod;

abstract class Controller {
    /**
     * @var View
     */
    protected $view;
    /**
     * @var Router
     */
    protected $router;
    /**
     * @var string
     */
    protected $controllerNestedDir;

    public function __construct(Router $router, View $view) {
        $this->setControllerDir();
        $this->router = $router;
        $this->view = $view;
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
        $this->view->render(implode(DIRECTORY_SEPARATOR, explode('/', $this->controllerNestedDir)).$view, $data);
    }

    private function setControllerDir() {
        $this->controllerNestedDir = strtolower(str_replace(Config::JMW()['controller']['default_namespace'], '', get_class($this))).DIRECTORY_SEPARATOR;
    }
}