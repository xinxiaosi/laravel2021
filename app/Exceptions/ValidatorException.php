<?php
/**
 * 自定义验证器
 */

namespace App\Exceptions;

use App\Models\Base\ResponseModel;
use Illuminate\Http\Response;

class ValidatorException extends \Exception
{
    /**
     * ValidatorException constructor.
     * @param array $error
     * @param array $params 参数
     */
    public function __construct($error = [], $params = [])
    {
        parent::__construct($error[0], ResponseModel::DEFAULT_CODE_FAILURE);
    }

    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        //
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        $response = new Response();
        $response->exception = $this;
        return $response;
    }
}
