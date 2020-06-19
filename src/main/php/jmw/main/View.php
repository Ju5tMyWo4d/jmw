<?php


namespace jmw\main;



use jmw\config\Config;

class View {
    protected $data;
    protected $localDir;

    /**
     * View constructor.
     */
    public function __construct() {}

    /**
     * @param string $view
     * @param mixed|null $data
     */
    public function render(string $view, $data = null) {
        $this->data = $data;

        include Config::JMW()['dirs']['views'].$view.'.php';
    }

    /**
     * @param string $template
     */
    public function renderTemplate(string $template) {
        $data = &$this->data;

        include Config::JMW()['dirs']['templates'].$template.'.php';
    }

    /**
     * @param mixed $localDir
     */
    public function setLocalDir($localDir) {
        $this->localDir = $localDir;
    }
}