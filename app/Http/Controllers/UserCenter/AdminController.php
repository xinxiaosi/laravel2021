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

    public function login(Request $request)
    {
        //验证规则
        $rules = [
            'name' => 'required_without:email|string',
            'email' => 'required_without:name|string',
            'password' => 'required|string',
        ];
        //验证错误信息自定义
        $messages = [

        ];
        //验证结果 错误就抛出异常
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            throw new ValidatorException($validator->errors()->all());
        }

        return $this->repository->login($validator->getData());
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
            'role_id' => 'nullable|int',
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

    public function deleteData(Request $request)
    {
        //验证规则
        $rules = [
            'ids' => 'required',
        ];
        //验证错误信息自定义
        $messages = [

        ];
        //验证结果 错误就抛出异常
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            throw new ValidatorException($validator->errors()->all());
        }

        return $this->repository->deleteData($validator->getData());
    }

    public function editData(Request $request)
    {
        //验证规则
        $rules = [
            'id' => 'int|required_without:uid',
            'uid' => 'string|required_without:id',
            'name' => 'nullable|string',
            'password' => 'nullable|string',
            'email' => 'nullable|string',
            'phone' => 'nullable|string',
            'status' => 'nullable|int',
            'role_id' => 'nullable|int',
        ];
        //验证错误信息自定义
        $messages = [

        ];
        //验证结果 错误就抛出异常
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            throw new ValidatorException($validator->errors()->all());
        }

        return $this->repository->editData($validator->getData());
    }

    public function getList(Request $request)
    {
        //验证规则
        $rules = [
            'id' => 'nullable|string',
            'uid' => 'nullable|string',
            'name' => 'nullable|string',
            'email' => 'nullable|string',
            'phone' => 'nullable|string',
            'status' => 'nullable|int',
            'role_id' => 'nullable|int',
        ];
        //验证错误信息自定义
        $messages = [

        ];
        //验证结果 错误就抛出异常
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            throw new ValidatorException($validator->errors()->all());
        }

        return $this->repository->getList($validator->getData());
    }

    public function getInfo(Request $request)
    {
        //验证规则
        $rules = [
            'id' => 'int|required_without:uid',
            'uid' => 'string|required_without:id',
        ];
        //验证错误信息自定义
        $messages = [

        ];
        //验证结果 错误就抛出异常
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            throw new ValidatorException($validator->errors()->all());
        }

        return $this->repository->getInfo($validator->getData());
    }

}
