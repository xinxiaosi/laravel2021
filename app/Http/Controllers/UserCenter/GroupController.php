<?php


namespace App\Http\Controllers\UserCenter;


use App\Exceptions\ValidatorException;
use App\Http\Controllers\Controller;
use App\Http\Repositories\UserCenter\GroupRepository as Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 添加
     * @param Request $request
     * @return mixed
     * @throws ValidatorException
     */
    public function addGroup(Request $request)
    {
        //验证规则
        $rules = [
            'parent_id' => 'required|int',
            'ids_url' => 'nullable|string',
            'name' => 'required|string',
            'remarks' => 'required|string',
            'status' => 'required|int',
        ];
        //验证错误信息自定义
        $messages = [

        ];
        //验证结果 错误就抛出异常
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            throw new ValidatorException($validator->errors()->all());
        }

        return $this->repository->addGroup($validator->getData());
    }

    /**
     * 删除
     * @param Request $request
     * @return array|string[]
     * @throws ValidatorException
     */
    public function deleteGroup(Request $request)
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

        return $this->repository->deleteGroup($validator->getData());
    }

    /**
     * 编辑
     * @param Request $request
     * @return array
     * @throws ValidatorException
     */
    public function editGroup(Request $request)
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

        return $this->repository->editGroup($validator->getData());
    }

    /**
     * 获取列表
     * @param Request $request
     * @return mixed
     * @throws ValidatorException
     */
    public function getGroupList(Request $request)
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

        return $this->repository->getGroupList($validator->getData());
    }

    /**
     * 获取详情
     * @param Request $request
     * @return mixed
     * @throws ValidatorException
     */
    public function getGroupInfo(Request $request)
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

        return $this->repository->getGroupInfo($validator->getData());
    }

}
