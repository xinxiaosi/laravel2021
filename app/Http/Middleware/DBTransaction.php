<?php
/**
 * DB事物中间键
 */

namespace App\Http\Middleware;


//use App\Base\Exceptions\ApiException;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\DB;

class DBTransaction
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        DB::connection('product')->beginTransaction();

        $response = $next($request);
        if ($response->exception == null) {
            DB::connection('product')->commit();
        } else {
            DB::connection('product')->rollBack();
        }
        return $response;
    }
}
