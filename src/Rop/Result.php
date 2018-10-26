<?php
declare(strict_types=1);

namespace Acacia\Rop;


class Result
{
    private $success;
    private $failure;

    public function __construct($success = null, $failure = null)
    {
        $this->success = $success;
        $this->failure = $failure;
    }

    static function failure($error) {
        return new static(null, $error);
    }

    static function success($fun) {
        return new static($fun);
    }

    public function getValue() {
        if ($this->isFailure()) {
            return $this->failure;
        }

        return $this->success;
    }

    public function isFailure() {
        if ($this->failure != null) {
            return true;
        }
        return false;
    }
}
