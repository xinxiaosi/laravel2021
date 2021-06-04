<?php


namespace App\Http\Controllers\UserCenter;


use App\Exceptions\ValidatorException;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function addAuth(Request $request)
    {
        //验证规则
        $rules = [
            'parent_id' => 'required|int',
            'ids_url' => 'nullable|string',
            'name' => 'required|string',
            'remarks' => 'nullable|string',
            'status' => 'nullable|int',
            'main_id' => 'nullable|int',
        ];
        //验证错误信息自定义
        $messages = [

        ];
        //验证结果 错误就抛出异常
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            throw new ValidatorException($validator->errors()->all());
        }

        return $this->repository->addAuth($validator->getData());
    }

    public function deleteAuth(Request $request)
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

        return $this->repository->deleteAuth($validator->getData());
    }

    public function editAuth(Request $request)
    {
        //验证规则
        $rules = [
            'id' => 'required|int',
            'parent_id' => 'nullable|int',
            'ids_url' => 'nullable|string',
            'name' => 'nullable|string',
            'remarks' => 'nullable|string',
            'status' => 'nullable|int',
        ];
        //验证错误信息自定义
        $messages = [

        ];
        //验证结果 错误就抛出异常
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            throw new ValidatorException($validator->errors()->all());
        }

        return $this->repository->editAuth($validator->getData());
    }

    public function getAuthList(Request $request)
    {
        //验证规则
        $rules = [
            'type' => 'required|int',
            'id' => 'nullable|int',
            'name' => 'nullable|string',
        ];
        //验证错误信息自定义
        $messages = [

        ];
        //验证结果 错误就抛出异常
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            throw new ValidatorException($validator->errors()->all());
        }

        return $this->repository->getAuthList($validator->getData());
    }

    public function getAuthInfo(Request $request)
    {
        //验证规则
        $rules = [
            'id' => 'required|int',
        ];
        //验证错误信息自定义
        $messages = [

        ];
        //验证结果 错误就抛出异常
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            throw new ValidatorException($validator->errors()->all());
        }

        return $this->repository->getAuthInfo($validator->getData());
    }

}
