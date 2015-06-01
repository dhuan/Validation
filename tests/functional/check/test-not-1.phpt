--TEST--
Must validate when there is no rules in the chain
--FILE--
<?php
require 'vendor/autoload.php';

use Respect\Validation\Validator as v;

try {
    v::create()
        ->not(v::regex('/^[a-z]{3}$/'))
        ->check('abc');
} catch (Exception $exception) {
    echo get_class($exception).':'.$exception->getMessage().PHP_EOL;
}
?>
--EXPECTF--
Respect\Validation\Exceptions\RegexException:"abc" must not match /^[a-z]{3}$/
