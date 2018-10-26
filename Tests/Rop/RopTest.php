<?php
namespace Rop\Test;
use PHPUnit\Framework\TestCase;

use function Rop\bind;
use function Rop\either;
use Rop\Failure;
use function Rop\lift;
use function Rop\pipe;
use Rop\Result;
use Rop\RopBuild;


class RopTest extends TestCase
{
    private $example;

    public function setUp() {
        $this->example = ['name' => 'aaaaaaaaaaaa', 'email' => ''];
    }

    public function validate1($input) {
        if (empty($input['name'])) {
            return  Result::failure("Name must not be blank");
        }

        return Result::success($input);

    }
    public function validate2($input) {
        if (mb_strlen($input['name'] ) > 10) {
            return  Result::failure("Name must not be longer than 10 chars");
        }

        return Result::success($input);
    }
    public function validate3($input) {
        if (empty($input['email'] )) {
            return  Result::failure("Email must not be blank");
        }

        return Result::success($input);
    }



    public function testRun() {

        $f1 = bind([$this, 'validate1']);
        $f2 = bind([$this, 'validate2']);
        $f3 = bind([$this, 'validate3']);

        $r = Result::success($this->example);
        // $b = $f3($f2($f1($r)));

        $p1 = pipe([$this, 'validate1'], $r);

        var_dump($p1($r));
    }

}