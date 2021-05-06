<?php


namespace App\Models\UserModels\Models;


use App\Models\UserModels\UserCenterModel;

class DepartmentModel extends UserCenterModel
{
    public $table = 'department';
    public $fillable = [
        'parent_id',// int(11) NOT NULL DEFAULT '0' COMMENT '父级id',
        'department',// varchar(64) NOT NULL DEFAULT '' COMMENT '部门\r\n',
        'describe',// varchar(128) NOT NU//LL DEFAULT '' COMMENT '描述',
        'children_id_str',// varchar(512) NOT NULL DEFAULT '' COMMENT '子部门',
        'status',// tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1=正常，2=禁用',
        'sort',// int(11) NOT NULL DEFAULT '0' COMMENT '排序',
        'ids_url',
        'create_time',// datetime DEFAULT NULL,
        'update_time',// datetime DEFAULT NULL,
        'create_user',
        'create_user_uuid',
    ];

    public function setIdsUrlAttribute($value)
    {
        $this->attributes['ids_url'] = implode(',', $value);
    }

    public function getIdsUrlAttribute($value)
    {
        $res = explode(',', $value);
        foreach ($res as &$v) {
            $v = (int)$v;
        }
        return $res;
    }
}
