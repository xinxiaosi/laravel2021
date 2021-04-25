<?php
/**
 * 自定义验证器
 */

namespace App\Exceptions;

use App\Models\Base\ResponseModel;
use Illuminate\Http\Response;
use Exception;

class ValidatorException extends Exception
{
    /**
     * ValidatorException constructor.
     * @param array $error
     */
    public function __construct($error = [])
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

    public function render()
    {
        $response = new Response();
        $response->exception = $this;
        return $response;
    }
}
