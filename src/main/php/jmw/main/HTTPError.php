<?php


namespace jmw\main;


use Exception;
use Throwable;

class HTTPError extends Exception {

    /**
     * MainException constructor.
     * @param int $code
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct(int $code, $message = '', Throwable $previous = null) {
        if(!$message) {
            switch ($code) {
                case 403:
                    header('HTTP/1.0 403 Forbidden');
                    $message = 'Forbidden';
                    break;
                case 404:
                    header('HTTP/1.0 404 Not Found');
                    $message = 'Not Found';
                    break;
                case 503:
                    header('HTTP/1.1 503 Service Unavailable');
                    $message = 'Service Unavailable';
                    break;
                default:
                    header("HTTP/1.1 $code");
                    break;
            }
        }

        parent::__construct($message, $code, $previous);
    }
}