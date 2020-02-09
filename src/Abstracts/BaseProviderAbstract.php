<?php

namespace Kuroneko\DataProvider\Abstracts;

use Kuroneko\DataProvider\Exceptions\MapMethodIsInvalidException;
use Kuroneko\DataProvider\Exceptions\MethodIsInvalidException;
use Kuroneko\DataProvider\Traits\PrintLogTrait;
use Kuroneko\DataProvider\Traits\WriteLogTrait;
use Prophecy\Exception\Doubler\ClassNotFoundException;

/**
 * Class BaseDataProviderAbstract
 * @package Kuroneko\DataProvider\Abstracts
 */
abstract class BaseDataProviderAbstract
{
    use PrintLogTrait;

    /**
     * @var BaseLayerAbstract
     */
    private $dataLayer;

    /**
     * @var BaseLayerAbstract
     */
    private $secondDataLayer;

    /**
     * BaseDataProviderAbstract constructor.
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * @inheritDoc}
     */
    public function init()
    {
        try {
            if (empty($this->method()) || !in_array($this->method(), array_keys($this->mapMethod()))) {
                throw new MethodIsInvalidException('Method is invalid. please provide array of method to get data.');
            }

            if (empty($this->mapMethod()) || !is_array($this->mapMethod())) {
                throw new MapMethodIsInvalidException('Map method is invalid, please provide array of map method to get data.');
            }

            if (!class_exists($this->mapMethod()[$this->method()])) {
                throw new ClassNotFoundException('Class not found', $this->mapMethod()[$this->method()]);
            }

            $class = $this->mapMethod()[$this->method()];
            $this->dataLayer = new $class;
        } catch (\Exception $exception) {
            $this->printException($exception);
        }
    }

    /**
     *  If you has some function that get data from other method
     *  Eg: you define elastic but you want some method get data from redis
     *  Just define that in except() function and then send that function name as param to this
     *
     * @param string $callMethod
     * @return LayerAbstract|object
     */
    protected function getLayer($callMethod = '')
    {
        try {
            if (!empty($callMethod) && in_array($callMethod, array_keys($this->except()))) {
                if (empty($this->method()) || !in_array($this->method(), array_keys($this->mapMethod()))) {
                    throw new MethodIsInvalidException('Method is invalid');
                }
                if (!class_exists($this->mapMethod()[$this->except()[$callMethod]])) {
                    throw new ClassNotFoundException('Class not found', $this->mapMethod()[$this->method()]);
                }
                $class = $this->mapMethod()[$this->except()[$callMethod]];
                $this->secondDataLayer = new $class;
                return $this->secondDataLayer;
            } else {
                return $this->dataLayer;
            }
        } catch (\Exception $exception) {
            $this->printException($exception);
        }
    }

    /**
     * @param $method
     * @param mixed ...$args
     * @return array
     */
    protected function call($method, ...$args)
    {
        try {
            if (!empty($this->getLayer()) && method_exists($this->getLayer(), $method)) {
                $callMethod = in_array($method, array_keys($this->except())) ? $method : '';
                return $this->getLayer($callMethod)->$method(...$args);
            }
            return [];
        } catch (\Exception $exception) {
            $this->printException($exception);
            return [];
        }
    }

    /**
     * @return string
     */
    public abstract function method(): string;

    /**
     * Define Class corresponding to the method here
     * @return array
     */
    public abstract function mapMethod(): array;

    /**
     * @return array
     */
    public function except(): array
    {
        return [];
    }
}