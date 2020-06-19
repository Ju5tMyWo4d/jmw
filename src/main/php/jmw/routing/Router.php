<?php


namespace jmw\routing;


use jmw\config\Config;
use jmw\tools\Tools;

class Router {
    /**
     * @var array
     */
    private $routing;
    /**
     * @var array
     */
    private $currentNest;
    /**
     * @var PathExtractor
     */
    private $pathExtractor;

    public function __construct(string $path, array $routing = []) {
        $this->routing = $routing;
        $this->currentNest = $routing;
        $this->pathExtractor = new PathExtractor($path);
    }

    /**
     * @return RouterElement|null
     */
    public function getRoute(): ?RouterElement {
        $name = $this->pathExtractor->getNext();
        if($name === null) return null;

        $route = $this->checkRoute($name);
        $route = $route ?? $this->checkRoute(Config::JMW()['router']['match_all_wildcard']);
        if($route === null && $name === '') {
            $name = Config::JMW()['router']['default_start'];
        }

        if($route !== null) {
            $routerElement = new RouterElement($route);
        } else {
            $routerElement = new RouterElement(Tools::KebabCaseToPascalCase($name));
        }

        return $routerElement;
    }

    /**
     * @param string $name
     * @return string|null
     */
    private function checkRoute(string $name): ?string {
        $route = $this->currentNest[$name];

        if(is_array($route)) {
            $this->currentNest = $route[Config::JMW()['router']['elements_var_name']];
            $controller = $route[Config::JMW()['router']['controller_var_name']];
        } else if($route !== null) {
            $this->currentNest = [];
            $controller = $route;
        } else {
            $controller = null;
        }

        return $controller;
    }

    /**
     * @return PathExtractor
     */
    public function getPathExtractor(): PathExtractor {
        return $this->pathExtractor;
    }
}