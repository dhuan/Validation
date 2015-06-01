--TEST--
Must validate when there is no rules in the chain
--FILE--
<?php
require 'vendor/autoload.php';

var_dump(
    Respect\Validation\Validator::create()
        ->validate(null)
);
?>
--EXPECTF--
bool(true)
