<?php


namespace Kuroneko\DataProvider\Traits;

/**
 * Trait PrintLogTrait
 * @package Kuroneko\DataProvider\Traits
 */
trait PrintLogTrait
{
    /**
     * Print log message if app is run in console
     * @param $message
     * @param $full
     */
    public function printMessage($message, $full = true)
    {
        if (app()->runningInConsole()) {
            if (is_bool($message)) {
                $message = $message ? 'true' : 'false';
            }
            if (is_null($message)) {
                $message = 'null';
            }
            if ($full)
                echo "\n" . date('d/m/Y H:i:s', time()) . ' [m] Class ' . get_called_class() . ' - Message: ' . strval($message) . "\n";
            else
                echo "\n" . strval($message) . "\n";
        }
    }

    /**
     * Print log message error if app is run in console
     * @param $error
     * @param $full
     */
    public function printError($error, $full = true)
    {
        if (app()->runningInConsole()) {
            if (is_bool($error)) {
                $error = $error ? 'true' : 'false';
            }
            if (is_null($error)) {
                $error = 'null';
            }
            if ($full)
                echo "\n" . date('d/m/Y H:i:s', time()) . ' [e] Class ' . get_called_class() . ' - Error: ' . strval($error) . "\n";
            else
                echo "\n" . strval($error) . "\n";
        }
    }

    /**
     *
     */
    public function printBreakLine()
    {
        echo "\n\n";
    }

    /**
     * Print log exception if app is run in console
     * @param \Exception $exception
     * @param $full
     */
    public function printException(\Exception $exception, $full = true)
    {
        if (app()->runningInConsole()) {
            if ($full) {
                echo "\n" . date('d/m/Y H:i:s', time()) . " [Exception] Class " . get_called_class() . " - Message:" . $exception->getMessage() . " - Line: " . $exception->getLine() . " - File: " . $exception->getFile() . "\n";
                echo "Stack trace: {$exception->getTraceAsString()}\n\n";
            } else {
                echo "\n" . date('d/m/Y H:i:s', time()) . " [Exception] Class " . get_called_class() . " - Message:" . $exception->getMessage() . " - Line: " . $exception->getLine() . " - File: " . $exception->getFile() . "\n";
            }
        }
    }
}