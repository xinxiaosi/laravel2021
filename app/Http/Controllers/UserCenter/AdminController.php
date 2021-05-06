<?php


namespace App\Http\Controllers\UserCenter;


use App\Exceptions\ValidatorException;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    private $repository;
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function addData(Request $request)
    {
        //验证规则
        $rules = [
            'name' => 'required|string',
            'password' => 'required|string',
            'email' => 'nullable|string',
            'phone' => 'nullable|string',
            'status' => 'nullable|int',
            'role_id' => 'nullable|string',
        ];
        //验证错误信息自定义
        $messages = [

        ];
        //验证结果 错误就抛出异常
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            throw new ValidatorException($validator->errors()->all());
        }

        return $this->repository->addData($validator->getData());
    }
}
