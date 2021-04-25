<?php
/**
 * 中间键
 * 返回数据区里
 */
namespace App\Http\Middleware;

use App\Models\Base\ResponseModel;
use Illuminate\Http\Request;
use Closure;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\Cache;


class Response
{
    private $responseModel;
    public function __construct()
    {
        $this->responseModel = new ResponseModel();
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        env('IS_CLEAR') && Cache::clear();
        $request = $this->handleNull($request);
        app('config')->set(['pageSize'=> $request->input('pageSize', config('app.pageSize'))]);
        app('config')->set(['page'=> $request->input('page', config('app.page'))]);
        $response = $next($request);
        //获取 return/返回 信息
        $content = $response->getOriginalContent();

        $this->responseModel->setPage(intval($request->input("page", 1)));
        $this->responseModel->setPageSize(intval($request->input("pageSize", config('app.pageSize'))));

        if ($response->exception == null) {//没有error
            $this->responseModel->setCode(ResponseModel::DEFAULT_CODE_SUCCESS);
            $this->responseModel->setData($content);
            $this->responseModel->setMsg('success');
            $response->setContent($this->responseModel->toJson());
        } else {// 本地/开发 环境
            $code = $response->exception->getCode() ? : ResponseModel::DEFAULT_CODE_FAILURE;
            if($code == 403){//登录失败
                $this->responseModel->setCode(ResponseModel::DEFAULT_LOGIN_FAILUER);
            }else{//操作失败
                $this->responseModel->setCode($code);
            }

            if(config('app.env') == 'production'){  //开发
                $this->responseModel->setMsg(ResponseModel::DEFAULT_ERROR_MESSAGE);
                $content = '';
            }else{ //本地
                $this->responseModel->setMsg($response->exception->getMessage());
                $content = $response->exception->getFile().' Line:'.$response->exception->getLine();
            }

            $this->responseModel->setData($content);
            $response->setContent($this->responseModel->toJson());
        }
        $response->withHeaders(config('app.response_headers'));

        return $response;
    }

    private function handleNull(Request $request)
    {
        $input = $request->input();

        foreach ($input as $key => $item) {
            if (is_null($item)) {
                $request->offsetUnset($key);//不知道为什么实现不了
                $request->query->remove($key);
            }
            if (is_array($item)) {
                $request->offsetSet($key, handle_null($item, true));
            }
        }

        return $request;
    }
}
