<?php


namespace App\Http\Controllers\Base;

use App\Exceptions\ValidatorException;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Repository;
use App\Models\UserCenter\Models\AdminModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    private $repository;
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
//        $this->middleware('auth:api', ['except' => ['register', 'login']]);
    }

    public function index(Request $request)
    {
        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3RcL3VzZXJDZW50ZXJcL2FkbWluXC9sb2dpbiIsImlhdCI6MTYyMDI5NDc2OSwiZXhwIjoxNjIwMjk4MzY5LCJuYmYiOjE2MjAyOTQ3NjksImp0aSI6InVPbkRGa1hJMUNzczN4aFciLCJzdWIiOjEsInBydiI6Ijg3ZTBhZjFlZjlmZDE1ODEyZmRlYzk3MTUzYTE0ZTBiMDQ3NTQ2YWEifQ.yYm2EflhBZOMWFCn7lbDJv1qJYEWnFy8EYXjwLoKrWQ";
        $info = JWTAuth::parseToken()->user();
        dd($info);
        return ;
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
