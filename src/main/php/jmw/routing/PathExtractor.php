<?php


namespace jmw\routing;


use jmw\config\Config;

final class PathExtractor {
    /**
     * @var string[]
     */
    private $pathParts;
    /**
     * @var int
     */
    private $i;
    /**
     * @var string
     */
    private $currentPath = '';

    /**
     * PathExtractor constructor.
     * @param string $path
     */
    public function __construct(string $path) {
        $this->pathParts = explode(Config::JMW()['path']['separator'], $path);
        $this->i = 0;
    }

    /**
     * @return string|null
     */
    public function getNext(): ?string {
        if($this->pathParts[$this->i] !== null) {
            if($this->pathParts[$this->i] !== '') {
                $this->currentPath .= $this->pathParts[$this->i].Config::JMW()['path']['separator'];
            }
            return $this->pathParts[$this->i++];
        }

        return null;
    }

    /**
     * @return string[]
     */
    public function getPathParts(): array {
        return $this->pathParts;
    }

    /**
     * @return string
     */
    public function getCurrentPath(): string {
        return $this->currentPath;
    }

    /**
     * @return int
     */
    public function getCurrentIndex(): int {
        return $this->i;
    }
}