<?php

namespace Respect\Validation\Rules;

use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Result;

/**
 * Negates any rule.
 */
final class Not implements RuleInterface
{
    /**
     * @var RuleInterface
     */
    protected $rule;

    /**
     * @param RuleInterface $rule
     */
    public function __construct(RuleInterface $rule)
    {
        $this->rule = $rule;
    }

    /**
     * @return RuleInterface
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * {@inheritDoc}
     */
    public function apply($input, Result $result)
    {
        $childResult = $result->createChild($this->getRule());
        $childResult->setProperty(Result::MODE_KEY, ValidationException::MODE_NEGATIVE);
        $childResult->applyRule();

        $result->setProperty(Result::VALIDATION_KEY, (!$childResult->isValid()));
    }
}
