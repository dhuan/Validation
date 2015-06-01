<?php

namespace Respect\Validation\Rules;

use Respect\Validation\Result;

final class Regex implements RuleInterface
{
    /**
     * @var string
     */
    private $pattern;

    /**
     * @param string $pattern
     */
    public function __construct($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * {@inheritDoc}
     */
    public function apply($input, Result $result)
    {
        $result->setProperty('pattern', $this->pattern);
        $result->setProperty(Result::VALIDATION_KEY, preg_match($this->pattern, $input));
    }
}
