<?php

namespace Respect\Validation\Exceptions;

class NotOptionalException extends ValidationException
{
    /**
     * @var array
     */
    protected $defaultTemplates = array(
        self::MODE_AFFIRMATIVE => array(
            self::MESSAGE_STANDARD => '{{label}} is required',
        ),
        self::MODE_NEGATIVE => array(
            self::MESSAGE_STANDARD => '{{label}} is not required',
        ),
    );
}
