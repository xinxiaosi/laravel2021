<?php

namespace App\Models\UserCenter\Models;

use App\Models\UserCenter\UserCenterModel;

class GroupModel extends UserCenterModel
{
    public $table = 'group';

    public $fillable = [
//        'id', //int unsigned NOT NULL AUTO_INCREMENT,
        'parent_id', //int NOT NULL DEFAULT '1' COMMENT '父级id',
        'ids_url', //varchar(255) NOT NULL DEFAULT '' COMMENT '父级id路径',
        'name', //varchar(255) CHARACTER NOT NULL DEFAULT '' COMMENT '名称',
        'remarks', //varchar(255) CHARACTER NOT NULL DEFAULT '' COMMENT '备注',
        'status', //tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态 1=正常;2=不正常',
//        'create_time', //int unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
//        'update_time', //int NOT NULL DEFAULT '0' COMMENT '更新时间',
        'main_id', //mediumint NOT NULL DEFAULT '1' COMMENT '主id',
    ];

    public function userGroup()
    {
        return $this->hasMany(UserGroupModel::class, 'group_id', 'id');
    }
}
