<?php


namespace jmw\control;


use jmw\config\Config;
use jmw\main\HTTPError;
use jmw\routing\Router;

abstract class Page extends Controller {

    /**
     * Page constructor.
     * @param Router $router
     * @throws HTTPError
     */
    public function __construct(Router $router) {
        parent::__construct($router);

        $routerElement = $router->getRoute();
        $method = $routerElement === null ? Config::JMW()['controller']['default_method'] : lcfirst($routerElement->getValue());
        if($this->checkPageExists($method)) {
            $this->{$method}();
        } else {
            throw new HTTPError(404);
        }
    }
}