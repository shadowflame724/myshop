<?php

function foo($x, $n)
{
    echo "function is run<br>";
    if($n == 0){
        return 1;
    }
    $result = $x * foo($x, $n - 1);

    return $result;
}

$res = foo(2, 6);

echo $res;