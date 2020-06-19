<?php


namespace jmw\config;


use jmw\tools\Tools;

/**
 * Class Config
 * @package bsk\main
 */
final class Config {
    /**
     * @var array|null
     */
    private static $jmwConfig = null;

    /**
     * Config constructor.
     * @param array $config
     */
    private function __construct(array $config) {
        self::$jmwConfig = $config;

        $default_config = include __DIR__.DIRECTORY_SEPARATOR.'default_config.php';
        Tools::SetIfNotSet(self::$jmwConfig, $default_config);
    }

    /**
     * @param array $config
     * @throws ConfigAlreadySetException
     */
    public static function SetInstance(array $config) {
        if(Config::$jmwConfig == null) {
            new Config($config);
        } else {
            throw new ConfigAlreadySetException("");
        }
    }

    /**
     * @return array
     */
    public static function JMW(): array {
        if(Config::$jmwConfig == null) return [];

        return Config::$jmwConfig;
    }
}