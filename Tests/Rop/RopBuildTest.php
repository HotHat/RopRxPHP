<?php
/**
 * Created by cncn.com.
 * User: lyhux
 * Date: 2018/10/23
 * Time: 16:15
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
use Rop\Result;
use function Rop\bind;
use Rop\RopBuild;

class RopBuildTest extends TestCase
{
    /**
     * @var \Closure
     */
    private $v1;
    private $v2;
    private $v3;


    public function setUp()
    {
        $this->v1 =  function($input) {
            if (empty($input['name'])) {
                return  Result::failure("Name must not be blank");
            }

            return Result::success($input);
        };

        $this->v2 =  function($input) {
            if (mb_strlen($input['name'] ) > 10) {
                return  Result::failure("Name must not be longer than 10 chars");
            }

            return Result::success($input);
        };

        $this->v3 =  function($input) {
            if (empty($input['email'] )) {
                return  Result::failure("Email must not be blank");
            }

            return Result::success($input);
        };

    }

    // phpunit5 --bootstrap vendor/autoload.php
    public function test_right_track()
    {
        $input = ['name' => 'aaa', "email" => 'bbb'];
        $this->assertEquals(
            Result::success($input),
            RopBuild::of(Result::success($input))
                ->pipe($this->v1)
                ->pipe($this->v2)
                ->pipe($this->v3)
                ->build()
        );

    }
    public function test_left_track()
    {
        $input = ['name' => '', "email" => 'bbb'];

        $this->assertEquals(
            Result::failure('Name must not be blank'),
            RopBuild::of(Result::success($input))
                ->pipe($this->v1)
                ->pipe($this->v2)
                ->pipe($this->v3)
                ->build()
        );
    }

}