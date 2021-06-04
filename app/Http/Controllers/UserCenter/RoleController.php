<?php


namespace App\Http\Controllers\UserCenter;


use App\Exceptions\ValidatorException;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function addRole(Request $request)
    {
        //验证规则
        $rules = [
            'parent_id' => 'required|int',
            'ids_url' => 'nullable|string',
            'name' => 'required|string',
            'remarks' => 'nullable|string',
            'status' => 'nullable|int',
            'main_id' => 'nullable|int',
            'auth' => 'required|array',
        ];
        //验证错误信息自定义
        $messages = [

        ];
        //验证结果 错误就抛出异常
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            throw new ValidatorException($validator->errors()->all());
        }

        return $this->repository->addRole($validator->getData());
    }

    public function deleteRole(Request $request)
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

        return $this->repository->deleteRole($validator->getData());
    }

    public function editRole(Request $request)
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

        return $this->repository->editRole($validator->getData());
    }

    public function getRoleList(Request $request)
    {
        //验证规则
        $rules = [
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

        return $this->repository->getRoleList($validator->getData());
    }

    public function getRoleInfo(Request $request)
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

        return $this->repository->getRoleInfo($validator->getData());
    }

}
