--TEST--
Type "NULL" must be considered as optional
--FILE--
<?php
require 'vendor/autoload.php';

var_dump(
    Respect\Validation\Validator::create()
        ->regex('/[a-z]/')
        ->validate(null)
);
?>
--EXPECTF--
bool(true)
