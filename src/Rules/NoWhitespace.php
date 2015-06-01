<?php

namespace Respect\Validation\Rules;

use Respect\Validation\Result;

/**
 * Validates if a string contains no whitespace (spaces, tabs and line breaks);.
 */
final class NoWhitespace implements RuleRequiredInterface
{
    /**
     * {@inheritDoc}
     */
    public function apply($input, Result $result)
    {
        $result->setValid(is_string($input) && !preg_match('/\s/', $input));
    }
}
