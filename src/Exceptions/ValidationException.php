<?php

namespace Respect\Validation\Exceptions;

use Exception;
use Respect\Validation\Result;

class ValidationException extends Exception implements ExceptionInterface
{
    const MESSAGE_STANDARD = 0;

    const MODE_AFFIRMATIVE = 1;
    const MODE_NEGATIVE = 0;

    /**
     * @var array
     */
    protected $defaultTemplates = [
        self::MODE_AFFIRMATIVE => [
            self::MESSAGE_STANDARD => '{{label}} must be valid',
        ],
        self::MODE_NEGATIVE => [
            self::MESSAGE_STANDARD => '{{label}} must not be valid',
        ],
    ];

    /**
     * @var Result
     */
    protected $result;

    /**
     * @param Result $result
     */
    public function __construct(Result $result)
    {
        $this->result = $result;

        parent::__construct($this->getMainMessage());
    }

    public function getResult()
    {
        return  $this->result;
    }

    public function getMode()
    {
        return $this->getResult()->getProperty(Result::MODE_KEY);
    }

    protected function getFactory()
    {
        return $this->getResult()->getFactory();
    }

    public function getTemplate()
    {
        $mode = $this->getMode();
        $template = $this->getResult()->getProperty('template') ?: self::MESSAGE_STANDARD;
        if (is_string($template)) {
            return $template;
        }

        if (isset($this->defaultTemplates[$mode][$template])) {
            return $this->defaultTemplates[$mode][$template];
        }

        return $this->defaultTemplates[self::MODE_AFFIRMATIVE][self::MESSAGE_STANDARD];
    }

    private function formatMessage($template, array $params)
    {
        return preg_replace_callback(
            '/{{(\w+)}}/',
            function ($match) use ($params) {
                return isset($params[$match[1]]) ? $params[$match[1]] : $match[0];
            },
            $template
        );
    }

    public function getMainMessage()
    {
        $params = $this->result->getProperties();
        $message = $this->getTemplate();

        if (isset($params['name']) && !empty($params['name'])) {
            return $this->formatMessage($message, ['label' => $params['name']] + $params);
        }

        $format = '"%s"';

        if (is_array($params['value'])) {
            return $this->formatMessage($message, ['label' => sprintf($format, 'Array')] + $params);
        }

        if (is_object($params['value'])) {
            return $this->formatMessage($message, ['label' => sprintf($format, get_class($params['value']))] + $params);
        }

        return $this->formatMessage($message, ['label' => sprintf($format, $params['value'])] + $params);
    }
}
