<?php


namespace App\Http\Controllers\Base;

use App\Exceptions\ValidatorException;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IndexController extends Controller
{
    private $repository;
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
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

        return $this->repository->index($validator->getData());
    }
}
