<?php

$msg = "Hi butler!";

exec("python ../../src/watson.py '$msg'", $output);
var_dump($output);
