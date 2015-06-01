--TEST--
Must validate when there is no rules in the chain
--FILE--
<?php
require 'vendor/autoload.php';

use Respect\Validation\Validator as v;

try {
    v::create()
        ->regex('/^[a-z]{3}$/')
        ->check('123');
} catch (Exception $exception) {
    echo get_class($exception).':'.$exception->getMessage().PHP_EOL;
}
?>
--EXPECTF--
Respect\Validation\Exceptions\RegexException:"123" must match /^[a-z]{3}$/
