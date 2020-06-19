<?php


namespace jmw\routing;


final class RouterElement {
    /**
     * @var string
     */
    private $value;

    /**
     * RouterElement constructor.
     * @param string $value
     */
    public function __construct(string $value) {
        $this->value = $value;
    }

    /**
     * @return bool
     */
    public function isClass(): bool {
        return strpos($this->value, '\\') !== false;
    }

    /**
     * @return string
     */
    public function getValue(): string {
        return $this->value;
    }
}