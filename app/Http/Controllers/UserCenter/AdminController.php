<?php


namespace App\Http\Controllers\UserCenter;


use App\Exceptions\ApiException;
use App\Exceptions\ValidatorException;
use App\Http\Controllers\Controller;
use App\Http\Repositories\UserCenter\AdminRepository as Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 登录
     * @param Request $request
     * @return mixed
     * @throws ValidatorException
     */
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

    /**
     * 添加
     * @param Request $request
     * @return mixed
     * @throws ValidatorException
     * @throws ApiException
     */
    public function addAdmin(Request $request)
    {
        //验证规则
        $rules = [
            //账号信息
            'name'     => 'required|string',
            'password' => 'required|string',
            'email'    => 'nullable|string',
            'phone'    => 'nullable|string',
            'status'   => 'nullable|int',
            //角色信息
            'role'     => 'nullable|array',
            //组织信息
            'group'    => 'nullable|array',
        ];
        //验证错误信息自定义
        $messages = [

        ];
        //验证结果 错误就抛出异常
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            throw new ValidatorException($validator->errors()->all());
        }

        return $this->repository->addAdmin($validator->getData());
    }

    /**
     * 删除
     * @param Request $request
     * @return mixed
     * @throws ValidatorException
     */
    public function deleteAdmin(Request $request)
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

        return $this->repository->deleteAdmin($validator->getData());
    }

    /**
     * 编辑
     * @param Request $request
     * @return mixed
     * @throws ValidatorException
     */
    public function editAdmin(Request $request)
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

        return $this->repository->editAdmin($validator->getData());
    }

    /**
     * 获取列表
     * @param Request $request
     * @return mixed
     * @throws ValidatorException
     */
    public function getAdminList(Request $request)
    {
        //验证规则
        $rules = [
            'id' => 'nullable|string',
            'uid' => 'nullable|string',
            'name' => 'nullable|string',
            'email' => 'nullable|string',
            'phone' => 'nullable|string',
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

        return $this->repository->getAdminList($validator->getData());
    }

    /**
     * 获取详情
     * @param Request $request
     * @return mixed
     * @throws ValidatorException
     */
    public function getAdminInfo(Request $request)
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

        return $this->repository->getAdminInfo($validator->getData());
    }

}
