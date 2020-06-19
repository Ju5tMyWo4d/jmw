<?php


namespace jmw\control;


use jmw\config\Config;
use jmw\main\HTTPError;
use jmw\routing\Router;
use jmw\main\View;

abstract class Page extends Controller {

    /**
     * Page constructor.
     * @param Router $router
     * @param View $view
     * @throws HTTPError
     */
    public function __construct(Router $router, View $view) {
        parent::__construct($router, $view);

        $routerElement = $router->getRoute();
        $method = $routerElement === null ? Config::JMW()['controller']['default_method'] : lcfirst($routerElement->getValue());
        if($this->checkPageExists($method)) {
            $this->{$method}();
        } else {
            throw new HTTPError(404);
        }
    }
}