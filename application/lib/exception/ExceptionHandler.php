<?php

/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2019/3/16
 * Time: 16:09
 */
namespace app\lib\exception;
use think\exception\Handle;

class ExceptionHandler extends Handle
{
    protected $msg;

    protected $errorCode;

    protected $code;

    public function render(\Exception $e)
    {
        if($e instanceof BaseException) {
            $this->msg = $e->message;
            $this->errorCode = $e->errorCode;
            $this->code = $e->code;
        } else {
            $this->msg = $e->getMessage();
            $this->errorCode = 999;
            $this->code = 500;
            $this->recordErrorLog($e);
        }
        $request = \think\Request::instance();
        $result = [
            'url' => $request->url(),
            'errorCode' => $this->errorCode,
            'msg' => $this->msg
        ];
        return json($result, $this->code);
    }

    //记录日志
    private function recordErrorLog(\Exception $e) {
        \think\Log::init([
            'type'  => 'file',
            'path'  => LOG_PATH,
            'level' => 'error'
        ]);
        \think\Log::record($e->getMessage(), 'error');
    }

}