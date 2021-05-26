<?php


namespace App\Http\Controllers\Base;

use App\Exceptions\ValidatorException;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Repository;
use App\Models\UserCenter\Models\AdminModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
//        $this->middleware('auth');
    }

    public function index(Request $request)
    {
//        $arr = [1,2,3,4,5,6,7,8,9];
//        dump((end($arr)));
//        dd(key(array_slice($arr, -1, 1, true)));


        $key = 'zAdd';
        $redis = Redis::connection('cache');

//        for ($i = 1; $i<100; $i++) {
//            $redis->zAdd($key, rand(1,100), $i);
//        }


        $a = $redis->zRank($key, 24);
        dump($a);
        return ($redis->zRange($key, 99, 555));

        dd(1);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
