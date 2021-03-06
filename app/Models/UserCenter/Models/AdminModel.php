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
        'main_id',//主id
    ];

    public $hidden = ['password', 'is_delete'];


    public function getDepartmentIdsUrlAttribute($value)
    {
        return $this->strToArrayForVue($value);
    }

    public function role()
    {
        return $this->hasMany(AdminRoleModel::class, 'user_id', 'id');
    }

    public function group()
    {
        return $this->hasOne(AdminGroupModel::class, 'user_id', 'id');
    }

    public function roleModel()
    {
        return $this->hasMany(AdminRoleModel::class, 'user_id', 'id')
            ->join('role', 'id', '=', 'role_id')
            ->selectRaw('id, role_id, name, user_id');
    }

    public function groupModel()
    {
        return $this->hasOne(AdminGroupModel::class, 'user_id', 'id')
            ->join('group', 'id', '=', 'group_id')
            ->selectRaw('id, group_id, name, user_id');
    }

    /**********************************修改器***********************************************/
    /**
     * password修改器
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = password_hash(md5($value), PASSWORD_BCRYPT);
//        $this->attributes['password'] = \Hash::make(md5($value));
    }

    public function setRoleIdAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['role_id'] = implode(',', $value);
        }
    }

    public function getAdminList($where, $role, $group)
    {
        $res = $this
            ->handleCondition($where)
            ->with([
                'group.group',
                'role.role',
            ]);

        if (!empty($role)) {
            $res->whereHas("role", function ($query) use($role) {
                $query->where('role_id', '=', $role);
            });
        }

        if (!empty($group)) {
            $res->whereHas("group", function ($query) use($group) {
                $query->where('group_id', '=', $group);
            });
        }

        return $res->orderBy('id', 'desc')
            ->paginate(config('pageSize'));

    }

    public function getAdminInfo($where)
    {
        if (empty($where))
            throw new ApiException(ApiException::ERROR_DATA_NOT_EXISTS);

        $res = $this
            ->handleCondition($where)
            ->with([
                'group.group',
                'role.role',
            ])
            ->orderBy('create_time', 'desc')
            ->first();

        if (is_null($res))
            throw new ApiException(ApiException::ERROR_DATA_NOT_EXISTS);


        $res->role_ids = $this->strToArrayForVue($res->role_id);

        return $res;

    }
}
