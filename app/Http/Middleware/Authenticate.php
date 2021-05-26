<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiException;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Cache;

class Authenticate
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
        //获取token
        $token = $request->header('token','');
        if (empty($token)) {
            $token = $request->input('token','');
            if(empty($token)){
                throw new ApiException(ApiException::ERROR_AUTHORIZATION);
            }
        }
        $token = str_replace(' ','',$token);

        //权限验证
        env('AUTH') && $this->rulesValidator($token);

        //先从缓存 获取 token 的用户信息 如果不存在 再调用 获取用户信息
        $info = json_decode(Cache::get(self::CACHE_KEY.$token), true);
        if(!$info){
            throw new ApiException(ApiException::ERROR_AUTHORIZATION);
        } else {
            app('config')->set(['userInfo' => $info]);
        }

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
//            throw new ApiException([-1, '暂无此权限!']);
    }
}
