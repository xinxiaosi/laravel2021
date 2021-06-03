<?php


namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use JWTAuth;

class JwtBaseController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth:api', ['except' => ['register', 'login']]);
    }

    public function register(Request $request)
    {
        // jwt token
        $credentials = [
            'name' => $request->name,
            'password' => Hash::make($request->password)
        ];
        $user = User::create($credentials);
        if ($user) {
            $token = JWTAuth::fromUser($user);
            return $this->responseWithToken($token);
        }
    }

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
