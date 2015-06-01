<?php

namespace Respect\Validation\Rules;

use Respect\Validation\Result;

/**
 * Validates if the given input is not optional.
 */
final class NotOptional implements RuleRequiredInterface
{
    /**
     * {@inheritDoc}
     */
    public function apply($input, Result $result)
    {
        $result->setProperty(Result::VALIDATION_KEY, $input !== null);
    }
}
