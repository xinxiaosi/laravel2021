<?php
/**
 * 返回数据处理
 */

namespace App\Models\Base;


use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;
class ResponseModel implements Arrayable, Jsonable, JsonSerializable
{
    /**
     * 默认返回code
     */
    const DEFAULT_CODE_SUCCESS = 1;
    /**
     * 操作失败
     */
    const DEFAULT_CODE_FAILURE = 0;

    /**
     * 登录失败
     */
    const DEFAULT_LOGIN_FAILUER = -10;

    /**
     * 错误 返回 message
     */
    const DEFAULT_ERROR_MESSAGE = '服务器忙，请稍候重试...';
    /**
     * @var int
     */
    private $code = self::DEFAULT_CODE_SUCCESS;

    /**
     * @var string
     */
    private $msg;

    /**
     * @var object/array/null/string
     */
    private $data;
    /**
     * 是否为错误信息
     * @var bool
     */
    private $isError = false;

    /**
     * 页码
     */
    private $p = 0;

    /**
     * 页条数
     */
    private $pagesize = 10;
    /**
     * 缓存建议
     * @var bool
     */
    private $cache = false;
    /**
     * 缓存时间
     * @var int
     */
    private $cacheTime = 0;

    function __construct()
    {
    }

    /**
     * 获取code
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * 设置code
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * 获取cache
     * @return bool
     */
    public function getCache(): bool
    {
        return $this->cache;
    }

    /**
     * 设置cache
     * @param bool $bool
     */
    public function setCache(bool $bool): void
    {
        $this->cache = $bool;
    }

    /**
     * @return int
     */
    public function getCacheTime(): int
    {
        return $this->cache;
    }

    /**
     * @param int $time
     */
    public function setCacheTime(int $time): void
    {
        $this->cache = $time;
    }

    /**
     * @return string
     */
    public function getMsg(): string
    {
        return $this->msg;
    }

    /**
     * @param string $msg
     */
    public function setMsg(string $msg): void
    {
        $this->msg = $msg;
    }

    /**
     * @return array|object|string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param  $data
     */
    public function setData($data = null): void
    {
        if (is_object($data) && method_exists($data, 'toArray'))
            $data = $data->toArray();
        else
            $data = json_decode(json_encode($data), true);

        if (isset($data['current_page']) && isset($data['data'])) {
            $data['list'] = $data['data'];
            unset($data['data']);
        }
        if (isset($data['total'])) {
            $data['count'] = intval($data['total']);
            unset($data['total']);
        }
        $this->data = $data;
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {
        return $this->isError;
    }

    /**
     * @param bool $isError
     */
    public function setIsError(bool $isError)
    {
        $this->isError = $isError;
    }

    /**
     * @param int $p
     */
    public function setPage(int $p): void
    {
        $this->p = intval($p);
    }

    /**
     * @param int $pagesize
     */
    public function setPageSize(int $pagesize): void
    {
        $this->pagesize = intval($pagesize);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        if ($this->isError) {
            return [
                'code' => $this->code,
                'messages' => $this->msg,
                'resultData' => [
                    'fixed' =>
                        [
                            'page' => $this->p,
                            'pageSize' => $this->pagesize
                        ],
                    'data' => ""
                ]
            ];
        } else {
            return [
                'code' => $this->code,
                'messages' => $this->msg,
                'resultData' => [
                    'fixed' =>
                        [
                            'page' => $this->p,
                            'pageSize' => $this->pagesize
                        ],
                    'data' => $this->data,
                    'cache' => $this->cache,
                    'cacheTime' => $this->cacheTime,
                ]
            ];
        }
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        $data = json_encode($this->toArray());
        if (json_last_error() && json_last_error() == JSON_ERROR_UTF8) {
            $data = json_encode($this->utf8Encode($this->toArray()));
        }

        return $data;
    }

    /**
     * Encode array to utf8 recursively
     * @param $dat
     * @return array|string
     */
    private function utf8Encode($dat)
    {
        if (is_string($dat))
            return utf8_encode($dat);
        if (!is_array($dat))
            return $dat;
        $ret = array();
        foreach ($dat as $i => $d) {
            $ret[$i] = $this->utf8Encode($d);
        }
        return $ret;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }


}
