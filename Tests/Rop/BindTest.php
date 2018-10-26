<?php
namespace Acacia\Rop\Test;
use PHPUnit\Framework\TestCase;
use Rop\Result;
use function Rop\bind;

class BindTest extends TestCase
{
    /**
     * @var \Closure
     */
    private $increment;
    public function setUp()
    {
        $this->increment = function ($int) {
            return Result::success($int + 1);
        };
    }
    public function test_right_track()
    {
        $this->assertEquals(
            Result::success(2),
            bind($this->increment)(Result::success(1))
        );
    }
    public function test_left_track()
    {
        $this->assertEquals(
            Result::failure('error'),
            bind($this->increment)(Result::failure('error'))
        );
    }

}