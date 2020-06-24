<?php


namespace jmw\main;



use jmw\config\Config;

class View {
    /**
     * @var null
     */
    protected $data;
    /**
     * @var string
     */
    protected $rootPath;

    /**
     * View constructor.
     * @param string $viewPath
     * @param null $data
     * @param string $additionalPath
     */
    private function __construct(string $viewPath, $data = null, string $additionalPath = '') {
        $this->data = $data;
        $this->rootPath = $additionalPath;

        $this->renderTemplate($viewPath, true);
    }

    /**
     * @param string $templatePath
     * @param bool $local
     */
    public function renderTemplate(string $templatePath, bool $local = false) {
        $data = &$this->data;

        if($local) {
            include Config::JMW()['dirs']['templates'].$this->rootPath.$templatePath.'.php';
        } else {
            include Config::JMW()['dirs']['templates'].$templatePath.'.php';
        }
    }

    /**
     * @param string $viewPath
     * @param null $data
     * @param string $rootPath
     */
    public static function Render(string $viewPath, $data = null, string $rootPath = '') {
        new static($viewPath, $data, $rootPath);
    }
}