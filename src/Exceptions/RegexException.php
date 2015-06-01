<?php

namespace Respect\Validation\Exceptions;

class RegexException extends ValidationException
{
    /**
     * @var array
     */
    protected $defaultTemplates = array(
        self::MODE_AFFIRMATIVE => array(
            self::MESSAGE_STANDARD => '{{label}} must match {{pattern}}',
        ),
        self::MODE_NEGATIVE => array(
            self::MESSAGE_STANDARD => '{{label}} must not match {{pattern}}',
        ),
    );
}
