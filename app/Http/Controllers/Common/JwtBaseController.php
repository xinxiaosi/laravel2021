<?php


namespace App\Http\Controllers\Common;

use App\Exceptions\ApiException;
use App\Exceptions\ValidatorException;
use App\Http\Controllers\Controller;
use App\Models\UserCenter\Models\AdminModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use function Symfony\Component\String\u;

class JwtBaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt', ['except' => ['register', 'login']]);
    }

    /**
     * 管理员注册
     * @param Request $request
     * @return array
     * @throws ApiException
     * @throws ValidatorException
     */
    public function register(Request $request)
    {
        //验证规则
        $rules = [
            'name' => 'required|string',
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

        $data = $validator->getData();
        $credentials['name'] = $data['name'];
        $AdminModel = new AdminModel;
        $user = $AdminModel->handleCondition($credentials)->first();
        if ($user !== null) {
            throw new ApiException([0, '用户名已存在']);
        }
        $credentials['password'] = $data['password'];
        $credentials['uid'] = uniqid();
        $credentials['token'] = md5(uniqid() . time() . $credentials['name']);


        $user = $AdminModel->create($credentials);
        if (!$user) {
            throw new ApiException([0, '注册失败']);
        }

        $token = JWTAuth::fromUser($user);
        return $this->responseWithToken($token);
    }

    /**
     * 管理员登录
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // todo 用户登录逻辑

        // jwt token
        $credentials = $request->only('name', 'password');
        $credentials['password'] = md5($credentials['password']);

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['result' => 'failed']);
        }

        return $this->responseWithToken($token);
    }

    /**
     * 刷新token
     */
    public function refresh()
    {
        return $this->responseWithToken(JWTAuth::refresh());
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        return JWTAuth::invalidate();
    }

    /**
     * @param string $token
     * @return array
     */
    private function responseWithToken(string $token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ];
    }
}
