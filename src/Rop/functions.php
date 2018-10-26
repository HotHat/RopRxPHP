<?php
declare(strict_types=1);

namespace Acacia\Rop;

// convert a normal function into a switch
// // switch =>  input ->  Result
function lift(callable $oneTrack) : callable {
    return function(...$params) use ($oneTrack) {
       return Result::success($oneTrack(...$params));
    };
}

// apply either a success function or failure function
function either(callable $successFunc, callable $failureFunc, Result $twoTrackInput) : Result {

    if ($twoTrackInput->isFailure()) {
        return $failureFunc($twoTrackInput->getValue());
    }

    return $successFunc($twoTrackInput->getValue());

}

// convert a one track function into a switch
// ('a -> 'a) -> (Result -> Result)
function bind($oneTrack) {
    return function ($twoTrack) use ($oneTrack) {
        return either($oneTrack, function ($x) {
                                return Result::failure($x);
                            }
                      ,$twoTrack);
    };
}

// convert a one-track function into a two-track function
function map($oneTrack) {
    return function (Result $twoTrackInput) use ($oneTrack) {
        return either(lift($oneTrack), function ($x) {return Result::failure($x);}, $twoTrackInput);
    };
}

// convert two one track function into a two track function
function doubleMap(callable $successFun, callable $failureFun) {
    return function (Result $twoTrackInput) use ($successFun, $failureFun) {
        return either(lift($successFun), lift($failureFun), $twoTrackInput);
    };
}


// convert a dead-end function into a one-track function
function tee(callable $fun, ...$params) {
    $fun(...$params);

    return $params;
}

// convert a one-track function into a switch with exception handling
function tryCatch(callable $oneTrack, callable $exHandler, ...$params) {
    try {
        return Result::success($oneTrack(...$params));
    } catch (\Exception $e) {
        return Result::failure($exHandler($e));
    }
}

// add two switches in parallel
function plus(callable $addSuccess, callable $addFailure, callable $switch1, callable $switch2, ...$params) {
    $res1 = $switch1(...$params);
    $res2 = $switch2(...$params);

    if ($res1->isFailure() == false && $res2->isFailure() == false) {
        return Result::success($addSuccess($res1->getValue(), $res1->getValue()));
    } else if ($res1->isFailure() && $res2->isFailure() == false) {
        return Result::failure($res1->getValue());
    } else if ($res1->isFailure() == false && $res2->isFailure()) {
        return Result::failure($res2->getValue());
    } else {
        return Result::failure($addFailure($res1->getValue(), $res2->getValue()));
    }
}

// pipe a two-track value into a switch function
function pipe($twoTrack1, $twoTrack2) {
    return (bind($twoTrack2))($twoTrack1);
}

// compose two switches into another switch
function unite(callable $s1, callable $s2) {
    return function(...$params) use ($s1, $s2){
        return (bind($s2))($s1(...$params));
    };
}

// switch => one input ->  Result
// two-track function => Result -> Result
// normal function => function return others

// two track input => Result