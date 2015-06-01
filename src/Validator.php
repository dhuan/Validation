<?php

/*
 * This file is part of Respect\Validation.
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

namespace Respect\Validation;

use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Rules\AllOf;

/**
 * Main validator class.
 */
class Validator extends AllOf
{
    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var Factory
     */
    protected static $defaultFactory;

    /**
     * Creates a new validator.
     *
     * @param Factory $factory
     */
    public function __construct(Factory $factory = null)
    {
        $this->factory = $factory ?: static::getDefaultFactory();
    }

    /**
     * Returns the default factory.
     *
     * @return Factory
     */
    public static function getDefaultFactory()
    {
        if (null === static::$defaultFactory) {
            static::$defaultFactory = new Factory();
        }

        return static::$defaultFactory;
    }

    /**
     * Defines the name of the current validation chain.
     *
     * @param string $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = (string) $name;

        return $this;
    }

    /**
     * Returns the name of the current validation chain.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Creates a new validator chain with the called validation rule.
     *
     * @param string $ruleName
     * @param array  $arguments
     *
     * @return Validator
     */
    public static function __callStatic($ruleName, array $arguments)
    {
        $validator = new static();
        $validator->__call($ruleName, $arguments);

        return $validator;
    }

    /**
     * Creates and append a new validation rule to the chain using its name.
     *
     * @param string $ruleName
     * @param array  $arguments
     *
     * @return self
     */
    public function __call($ruleName, array $arguments)
    {
        $rule = $this->factory->rule($ruleName, $arguments);

        $this->addRule($rule);

        return $this;
    }

    /**
     * @param mixed $input
     *
     * @return bool
     */
    public function validate($input)
    {
        $result = $this->factory->result($this, ['value' => $input, 'name' => $this->getName()]);
        $result->applyRule();

        return $result->isValid();
    }

    /**
     * @param mixed $input
     *
     * @throws ValidationException
     */
    public function check($input)
    {
        foreach ($this->getRules() as $childRule) {
            $childResult = $this->factory->result($childRule, ['value' => $input, 'name' => $this->getName()]);
            $childResult->applyRule();

            if ($childResult->isValid()) {
                continue;
            }

            throw $this->factory->exception($childResult);
        }
    }

    /**
     * @param mixed $input
     *
     * @throws AbstractCompositeException
     */
    public function assert($input)
    {
        $result = $this->factory->result($this, ['value' => $input, 'name' => $this->getName()]);
        $result->applyRule();

        if ($result->isValid()) {
            return;
        }

        throw $this->factory->exception($result);
    }

    /**
     * Creates a new validator.
     *
     * @return Validator
     */
    public static function create()
    {
        return new static();
    }
}
