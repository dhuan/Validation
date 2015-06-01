<?php

namespace Respect\Validation\Rules;

use Respect\Validation\Result;
use SplObjectStorage;

/**
 * Will validate if all inner validators validates.
 */
class AllOf implements RuleInterface
{
    /**
     * @var SplObjectStorage
     */
    private $rules;

    public function __construct()
    {
        $this->addRules(func_get_args());
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->getRules()->count();
    }

    /**
     * @param RuleInterface $rule
     * @param string        $ruleName
     *
     * @return self
     */
    public function addRule(RuleInterface $rule)
    {
        $this->getRules()->attach($rule);

        return $this;
    }

    public function addRules(array $rules)
    {
        foreach ($rules as $rule) {
            $this->addRule($rule);
        }
    }

    /**
     * @return SplObjectStorage
     */
    public function getRules()
    {
        if (!$this->rules instanceof SplObjectStorage) {
            $this->rules = new SplObjectStorage();
        }

        return $this->rules;
    }

    /**
     * {@inheritDoc}
     */
    public function apply($input, Result $result)
    {
        foreach ($this->getRules() as $childRule) {
            $childResult = $result->createChild($childRule);
            $childResult->applyRule();

            $result->setProperty(Result::VALIDATION_KEY, $result->isValid() && $childResult->isValid());
        }
    }
}
