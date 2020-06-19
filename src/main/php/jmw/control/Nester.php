<?php


namespace jmw\control;


use jmw\config\Config;
use jmw\main\HTTPError;
use jmw\routing\Router;
use jmw\main\View;

abstract class Nester extends Controller {

    /**
     * Nester constructor.
     * @param Router $router
     * @param View $view
     * @param string $namespace
     * @throws HTTPError
     */
    public function __construct(Router $router, View $view, ?string $namespace = null) {
        parent::__construct($router, $view);

        if($namespace == null) {
            $namespace = strtolower(get_class($this)).'\\';
        } else {
            $namespace .= '\\';
        }

        $routerElement = $router->getRoute();
        if($routerElement === null) {
            $this->{Config::JMW()['controller']['default_method']}();
        } else if($routerElement->isClass()) {
            if(class_exists($routerElement->getValue())) {
                new ${$routerElement->getValue()}($router, $view);
            } else {
                throw new HTTPError(404);
            }
        } else {
            if($this->checkPageExists(lcfirst($routerElement->getValue()))) {
                $this->{$routerElement->getValue()}();
            } else if(class_exists($namespace.$routerElement->getValue())) {
                $tmpController = $namespace.$routerElement->getValue();
                new $tmpController($router, $view);
            } else {
                throw new HTTPError(404);
            }
        }
    }
}