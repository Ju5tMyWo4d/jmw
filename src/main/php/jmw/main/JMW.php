<?php


namespace jmw\main;


use jmw\config\Config;
use jmw\config\ConfigAlreadySetException;
use jmw\routing\EntryPointNotFoundException;
use jmw\routing\Router;

final class JMW {
    /**
     * @var array
     */
    private $routing;

    /**
     * JMW constructor.
     * @param string $path
     * @param array $routing
     * @throws EntryPointNotFoundException
     */
    public function __construct(string $path, array $routing = []) {
        $this->routing = $routing;

        $this->checkSetUp();

        $router = new Router($path, $routing);
        $routerElement = $router->getRoute();

        if(!$routerElement->isClass()) {
            $controller = Config::JMW()['controller']['default_namespace'].$routerElement->getValue();
        } else {
            $controller = $routerElement->getValue();
        }


        ob_start();

        try {
            new $controller($router);
        } catch(HTTPError $e) {
            ob_clean();

            if(Config::JMW()['controller']['error']) {
                $controller = Config::JMW()['controller']['error'];
                new $controller($e);
            } else {
                echo $e->getMessage();
            }
        }

        ob_end_flush();
    }

    /**
     * @throws EntryPointNotFoundException
     */
    private function checkSetUp() {
        if($this->routing[Config::JMW()['path']['separator']] === null && $this->routing[Config::JMW()['router']['match_all_wildcard']] === null) {
            if((strpos(Config::JMW()['router']['default_start'], '\\') === false && Config::JMW()['controller']['default_namespace']  === null)
                || Config::JMW()['router']['default_start'] === null) {
                throw new EntryPointNotFoundException("Could not find entry point, please set up config or routing");
            }
        }
    }

    public static function SetConfig(array $config = []) {
        try {
            Config::SetInstance($config);
        } catch(ConfigAlreadySetException $e) {}
    }
}