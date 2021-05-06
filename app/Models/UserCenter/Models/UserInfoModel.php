<?php


namespace App\Models\UserModels\Models;

;
use App\Models\UserModels\UserCenterModel;

class UserInfoModel extends UserCenterModel
{
    public $table = 'user_info';
    public $fillable = [
        'id',// int(11) NOT NULL AUTO_INCREMENT,
        'account_id',// int(11) NOT NULL COMMENT '用户id',
        'member_uuid',// varchar(64) NOT NULL DEFAULT '' COMMENT '用户uuid',
        'img',// varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
        'thumbnail',// varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图',
        'address',// varchar(512) NOT NULL DEFAULT '' COMMENT '地址',
    ];
    public $timestamps = false;

    public function department()
    {
        return $this->belongsTo(DepartmentModel::class, 'department_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(UserRoleModel::class, 'role_id', 'id');
    }
}
