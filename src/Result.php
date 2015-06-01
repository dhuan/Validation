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
use Respect\Validation\Rules\RuleInterface;
use Respect\Validation\Rules\RuleRequiredInterface;

class Result
{
    const MODE_KEY = 'mode';
    const VALIDATION_KEY = 'validation';
    const VALUE_KEY = 'value';

    /**
     * @var Result[]
     */
    protected $children = [];

    /**
     * @var RuleInterface
     */
    protected $rule;

    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var array
     */
    protected $properties = [
        self::MODE_KEY => ValidationException::MODE_AFFIRMATIVE,
        self::VALIDATION_KEY => true,
        self::VALUE_KEY => null,
    ];

    /**
     * @param RuleInterface $rule
     * @param mixed         $value
     * @param Factory       $factory
     */
    public function __construct(RuleInterface $rule, array $properties, Factory $factory)
    {
        $this->rule = $rule;

        foreach ($properties as $name => $value) {
            $this->setProperty($name, $value);
        }

        $this->factory = $factory;
    }

    /**
     * @return RuleInterface
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @return Factory
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param string $name
     * @param mixed  $defaultValue
     *
     * @return mixed
     */
    public function getProperty($name, $defaultValue = null)
    {
        if (isset($this->properties[$name])) {
            $defaultValue = $this->properties[$name];
        }

        return $defaultValue;
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function setProperty($name, $value)
    {
        switch ($name) {
            case self::MODE_KEY:
                foreach ($this->getChildren() as $childResult) {
                    $childResult->setProperty($name, $value);
                }
                break;

            case self::VALIDATION_KEY:
                $value = (boolean) $value;
                break;
        }

        $this->properties[$name] = $value;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return (bool) $this->getProperty(self::VALIDATION_KEY, true);
    }

    /**
     * Apply rule to the result.
     */
    public function applyRule()
    {
        $rule = $this->getRule();
        $value = $this->getProperty('value');

        if ($value === null
            && !$rule instanceof RuleRequiredInterface
            && !$rule instanceof AllOf) {
            $this->setProperty(self::VALIDATION_KEY, true);

            return;
        }

        $rule->apply($value, $this);
    }

    /**
     * @param RuleInterface $rule
     *
     * @return Result
     */
    public function createChild(RuleInterface $rule)
    {
        $childResult = $this->getFactory()->result($rule, $this->getProperties());
        $childResult->appendTo($this);

        return $childResult;
    }

    /**
     * @param Result $parentResult
     */
    public function appendTo(Result $parentResult)
    {
        $parentResult->appendChild($this);
    }

    /**
     * @param Result $childChild
     */
    public function appendChild(Result $childChild)
    {
        $this->children[] = $childChild;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return !empty($this->children);
    }
}
