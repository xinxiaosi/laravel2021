<?php


namespace App\Http\Middleware;

use App\Exceptions\ApiException;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Cache;
use JWTAuth;
use Namshi\JOSE\JWT;

class JwtMiddleware
{
    const CACHE_KEY = 'userCenter:mediation:token:';
    const RULES_CACHE_KEY = 'userCenter:mediation:rules:';

    /**
     * 授权中间件
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws ApiException
     */
    public function handle(Request $request, Closure $next)
    {
        if (!JWTAuth::parseToken()->check()) {
            throw new ApiException(ApiException::AUTHORIZATION_EXPIRED);
        }

        //先从缓存 获取 token 的用户信息 如果不存在 再调用 获取用户信息
        $token = JWTAuth::parseToken()->authenticate()->toArray();
        app('config')->set(['userInfo' => $token]);
//        //权限验证
//        env('AUTH') && $this->rulesValidator($token);

        app('config')->set(['pageSize'=> $request->input("pageSize", config('pageSize'))]);
        app('config')->set(['token'=> $token]);

        return $next($request);
    }

    /**
     * 权限验证
     * @param $token
     * @throws ApiException
     */
    public function rulesValidator($token)
    {
        $rules = json_decode(Cache::get(self::RULES_CACHE_KEY.$token), true);
        if (is_null($rules))
            throw new ApiException(ApiException::ERROR_AUTHORIZATION);

        $url = substr(\Request::url(), strlen(env('API_URL')));
        if (!array_key_exists($url, $rules))
            throw new ApiException(ApiException::AUTH_RULES_NOT_EXISTS);
    }
}
