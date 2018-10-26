<?php

include '../vendor/autoload.php';


use function Rop\bind;
use function Rop\pipe;
use function Rop\map;
use Rop\Result;

 function validate1($input) {
    if (empty($input['name'])) {
        return  Result::failure("Name must not be blank");
    }

    return Result::success($input);

}
 function validate2($input) {
    if (mb_strlen($input['name'] ) > 10) {
        return  Result::failure("Name must not be longer than 10 chars");
    }

    return Result::success($input);
}
 function validate3($input) {
    if (empty($input['email'] )) {
        return  Result::failure("Email must not be blank");
    }

    return Result::success($input);
}

$example = ['name' => 'aaaaaaaaaaaa', 'email' => ''];


$f1 = bind('validate1');
$f2 = bind('validate2');
$f3 = bind('validate3');

// $r = Result::success($example);
// $b = $f2($f1($r));
// var_dump($b);
// $v1 = Closure::fromCallable('validate1');
// $v2 = Closure::fromCallable('validate2');
// $v3 = Closure::fromCallable('validate3');

/*
 * let combinedValidation x =
    x
    |> validate1   // normal pipe because validate1 has a one-track input
                   // but validate1 results in a two track output...
    >>= validate2  // ... so use "bind pipe". Again the result is a two track output
    >>= validate3   // ... so use "bind pipe" again.

 either $v1 fail $r
 */

// $p = pipe(pipe($r, $v1), $v2);


// var_dump($p);

function a($a1) {
    return $a1;
}

$params = [123, 4566];

echo a(...$params);