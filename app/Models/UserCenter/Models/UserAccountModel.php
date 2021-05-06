<?php


namespace App\Models\UserModels\Models;


use App\Models\UserModels\UserCenterModel;

class UserAccountModel extends UserCenterModel
{
    public $table = 'user_account';

    public $fillable = [
//        'id',// int(11) NOT NULL AUTO_INCREMENT,
        'role_id',// int(11) NOT NULL DEFAULT '1' COMMENT '角色id',
        'member_name',// varchar(32) DEFAULT '' COMMENT '账户',
        'member_password',// varchar(64) DEFAULT '' COMMENT '密码',
        'member_uuid',// varchar(64) DEFAULT '' COMMENT 'uuid',
        'member_token',// varchar(64) DEFAULT '' COMMENT 'token',
        'member_email',// varchar(64) DEFAULT '' COMMENT '邮箱',
        'member_phone',// varchar(32) DEFAULT '' COMMENT '电话',
        'status',
        'is_delete'
//        'create_time',// datetime DEFAULT NULL,
//        'update_time',// datetime DEFAULT NULL,
    ];

    public function setMemberPasswordAttribute($value)
    {
        return md5($value);
    }

    public function userInfo()
    {
        return $this->belongsTo(UserInfoModel::class, 'id', 'account_id')
            ->withDefault();
    }

    public function getUserList($type, $where)
    {
        $filed = [
            'id', 'member_name', 'member_email', 'member_phone', 'status', 'create_time', 'update_time'
        ];

        $res = $this
            ->handleCondition($where)
            ->with([
                'userInfo',
                'userInfo.department',
                'userInfo.role',
            ])
            ->select($filed)
            ->orderBy('create_time','desc');

        if ($type == 1) {
            return $res->paginate(config('pageSize'));
        }

        return $res->get();

    }
}
