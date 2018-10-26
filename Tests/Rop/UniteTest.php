<?php
/**
 * Created by cncn.com.
 * User: lyhux
 * Date: 2018/10/23
 * Time: 15:41
 *　　　　　　　　┏┓　　　┏┓+ +
 *　　　　　　　┏┛┻━━━┛┻┓ + +
 *　　　　　　　┃　　　　　　　┃ 　
 *　　　　　　　┃　　　━　　　┃ ++ + + +
 *　　　　　　 ████━████ ┃+
 *　　　　　　　┃　　　　　　　┃ +
 *　　　　　　　┃　　　┻　　　┃
 *　　　　　　　┃　　　　　　　┃ + +
 *　　　　　　　┗━┓　　　┏━┛
 *　　　　　　　　　┃　　　┃　　　　　　　　　　　
 *　　　　　　　　　┃　　　┃ + + + +
 *　　　　　　　　　┃　　　┃　　　　Code is far away from bug with the animal protecting　　　　　　　
 *　　　　　　　　　┃　　　┃ + 　　　　神兽保佑,代码无bug　　
 *　　　　　　　　　┃　　　┃
 *　　　　　　　　　┃　　　┃　　+　　　　　　　　　
 *　　　　　　　　　┃　 　　┗━━━┓ + +
 *　　　　　　　　　┃ 　　　　　　　┣┓
 *　　　　　　　　　┃ 　　　　　　　┏┛
 *　　　　　　　　　┗┓┓┏━┳┓┏┛ + + + +
 *　　　　　　　　　　┃┫┫　┃┫┫
 *　　　　　　　　　　┗┻┛　┗┻┛+ + + +
 */

namespace Rop\Test;
use PHPUnit\Framework\TestCase;
use function Rop\lift;
use Rop\Result;
use function Rop\unite;

class UniteTest extends TestCase
{
    /**
     * @var \Closure
     */
    private $increment1;
    /**
     * @var \Closure
     */
    private $increment2;
    /**
     * @var \Closure
     */
    private $failure;

    public function setUp()
    {
        $this->increment1 = function ($int) {
            return Result::success($int + 1);
        };
        $this->increment2 = function ($int) {
            return Result::success($int + 2);
        };
        $this->failure = function () {
            return Result::failure('error');
        };
    }
    public function test_right_track()
    {
        $this->assertEquals(
            Result::success(4),
            unite($this->increment1, $this->increment2)(1)
        );
    }
    public function test_left_track()
    {
        $this->assertEquals(
            Result::failure('error'),
            unite($this->failure, $this->increment2)(1)
        );
        $this->assertEquals(
            Result::failure('error'),
            unite($this->increment2, $this->failure)(1)
        );
    }

}