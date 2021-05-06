<?php


namespace App\Models\UserCenter\Models;


use App\Exceptions\ApiException;
use App\Models\UserCenter\UserCenterModel;
use Tymon\JWTAuth\Contracts\JWTSubject;

class AdminModel extends UserCenterModel implements JWTSubject
{
    public $table = 'admin';

    public $fillable = [
        'role_id',//varchar(256)   NOT NULL DEFAULT '' COMMENT '角色id',
        'name',//varchar(128)   NOT NULL DEFAULT '' COMMENT '账号',
        'password',//varchar(128)   NOT NULL DEFAULT '' COMMENT '密码',
        'uid',//varchar(64)   NOT NULL DEFAULT '' COMMENT 'uid',
        'token',//varchar(64)   NOT NULL DEFAULT '' COMMENT 'token',
        'email',//varchar(128)   NOT NULL DEFAULT '' COMMENT '邮箱',
        'phone',//varchar(32)   NOT NULL DEFAULT '' COMMENT '手机号',
        'status',//tinyint(1)  NOT NULL DEFAULT '1' COMMENT '状态 1=开启;2=关闭',
        'is_delete', //tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '是否删除 1=是;2=否',
//        'create_time',//int(10)  NOT NULL DEFAULT '0' COMMENT '创建时间',
//        'update_time',//int(10)  NOT NULL DEFAULT '0' COMMENT '更新时间',
    ];

    public $hidden = ['password'];


    public function getDepartmentIdsUrlAttribute($value)
    {
        return $this->strToArrayForVue($value);
    }


    /**********************************修改器***********************************************/
    /**
     * password修改器
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = password_hash(md5($value), PASSWORD_BCRYPT);
    }

    public function setRoleIdAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['role_id'] = implode(',', $value);
        }
    }























    public function department()
    {
        return $this->hasOne(DepartmentModel::class, 'id', 'department_id');
    }

    public function role()
    {
        return $this->hasManyFromStr(UserRoleModel::class, 'id', 'role_id');
    }

    public function userInfo()
    {
        return $this->belongsTo(UserInfoModel::class, 'id', 'account_id')
            ->withDefault();
    }

    public function getAdminList($where)
    {
        $res = $this
            ->handleCondition($where)
            ->with([
                'department:id,department',
                'role:id,role',
            ])
            ->orderBy('create_time', 'desc')
            ->paginate(config('pageSize'))
            ->toArray();

        return $res;

    }

    public function getAdminInfo($where)
    {
        if (empty($where))
            throw new ApiException(ApiException::ERROR_DATA_NOT_EXISTS);

        $res = $this
            ->handleCondition($where)
            ->with([
                'department',
                'role',
            ])
            ->orderBy('create_time', 'desc')
            ->first();

        if (is_null($res))
            throw new ApiException(ApiException::ERROR_DATA_NOT_EXISTS);


        $res->role_ids = $this->strToArrayForVue($res->role_id);

        return $res;

    }
}
