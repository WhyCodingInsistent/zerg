<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2019/3/19
 * Time: 12:07
 */

namespace app\api\controller;

use app\api\service\UserToken;
use think\Controller;

class BaseController extends Controller
{
    protected function checkPrimaryScope() {
        UserToken::checkPrimaryScope();
    }

    protected function checkSuperScope() {
        UserToken::checkSuperScope();
    }
}