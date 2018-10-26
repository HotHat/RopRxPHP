<?php
declare(strict_types=1);

namespace Acacia\Rop;

class RopBuild
{
    private $val;

    public function __construct(Result $val)
    {
        $this->val = $val;
    }

    static function of(Result $result) {
        $b = new RopBuild($result);
        return $b;
    }

    public function pipe(callable $oneTrack) {
        $this->val = pipe($this->val, $oneTrack);
        return $this;
    }

    public function build() {
        return $this->val;
    }

}
