<?php

namespace Respect\Validation\Exceptions;

use Respect\Validation\Iterators\ResultIterator;

class AllOfException extends ValidationException
{
    private function getPrefix($level)
    {
        if (0 === $level) {
            return '';
        }

        return str_repeat(' ', $level);
    }

    public function getFullMessage()
    {
        $resultIterator = new ResultIterator($this->getResult());
        $messages = array($this->getPrefix($this->level).$this->getMessage());
        foreach ($resultIterator as $result) {
            if ($result->isValid()) {
                continue;
            }
            $exception = $this->getResult()->getFactory()->exception($result);
            $exception->setLevel($this->level + 1);
            $messages[] = $this->getPrefix($this->level + 1).$exception->getMainMessage();
        }

        return implode(PHP_EOL, array_filter($messages));
    }
}
