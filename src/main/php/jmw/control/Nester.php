<?php


namespace jmw\control;


use jmw\config\Config;
use jmw\main\HTTPError;
use jmw\routing\Router;

abstract class Nester extends Controller {

    /**
     * Nester constructor.
     * @param Router $router
     * @param string $namespace
     * @throws HTTPError
     */
    public function __construct(Router $router, ?string $namespace = null) {
        parent::__construct($router);

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
                new ${$routerElement->getValue()}($router);
            } else {
                throw new HTTPError(404);
            }
        } else {
            if($this->checkPageExists(lcfirst($routerElement->getValue()))) {
                $this->{$routerElement->getValue()}();
            } else if(class_exists($namespace.$routerElement->getValue())) {
                $tmpController = $namespace.$routerElement->getValue();
                new $tmpController($router);
            } else {
                throw new HTTPError(404);
            }
        }
    }
}