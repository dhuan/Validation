<?php

/*
 * This file is part of Respect\Validation.
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

namespace Respect\Validation;

use RecursiveIteratorIterator;
use ReflectionClass;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Iterators\ResultIterator;
use Respect\Validation\Rules\RuleInterface;

class Factory
{
    /**
     * @var array
     */
    protected $namespaces = array('Respect\\Validation');

    /**
     * @return array
     */
    public function getNamespaces()
    {
        return $this->namespaces;
    }

    /**
     * @param string $namespace
     */
    public function appendNamespace($namespace)
    {
        array_push($this->namespaces, $namespace);
    }

    /**
     * @param string $namespace
     */
    public function prependNamespace($namespace)
    {
        array_unshift($this->namespaces, $namespace);
    }

    /**
     * @param string $rule
     * @param array  $settings
     *
     * @return RuleInterface
     */
    public function rule($rule, array $settings = array())
    {
        if ($rule instanceof RuleInterface) {
            return $rule;
        }

        foreach ($this->getNamespaces() as $namespace) {
            $className = $namespace.'\\Rules\\'.ucfirst($rule);
            if (!class_exists($className)) {
                continue;
            }

            $reflection = new ReflectionClass($className);
            if (!$reflection->isSubclassOf('Respect\\Validation\\Rules\\RuleInterface')) {
                throw new ComponentException(sprintf('"%s" is not a valid respect rule', $className));
            }

            return $reflection->newInstanceArgs($settings);
        }

        throw new ComponentException(sprintf('"%s" is not a valid rule name', $rule));
    }

    /**
     * @param Result $result
     *
     * @return ValidationException
     */
    public function exception(Result $result)
    {
        $resultIterator = new ResultIterator($result);
        $recursiveIterator = new RecursiveIteratorIterator($resultIterator);
        foreach ($recursiveIterator as $childResult) {
            if ($childResult->hasChildren()) {
                continue;
            }

            $result = $childResult;
            break;
        }

        $exceptionName = str_replace('\\Rules\\', '\\Exceptions\\', get_class($result->getRule()));
        $exceptionName .= 'Exception';

        return new $exceptionName($result);
    }

    /**
     * @return Result
     */
    public function result(RuleInterface $rule, array $properties)
    {
        return new Result($rule, $properties, $this);
    }
}
