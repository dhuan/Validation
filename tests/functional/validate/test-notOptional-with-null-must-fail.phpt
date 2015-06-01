--TEST--
NotOptional rule must must not consider "NULL" as optional
--FILE--
<?php
require 'vendor/autoload.php';

var_dump(
    Respect\Validation\Validator::create()
        ->regex('/[a-z]/')
        ->notOptional()
        ->validate(null)
);
?>
--EXPECTF--
bool(false)
