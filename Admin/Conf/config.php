<?php
    if (!defined('THINK_PATH')) exit();
	return array(
		'URL_MODEL'=>2, // 如果你的环境不支持PATHINFO 请设置为3
		'DB_TYPE'=>'mysql',
		'DB_HOST'=>'localhost',
		'DB_NAME'=>'sami',
		'DB_USER'=>'root',
		'DB_PWD'=>'',
		'DB_PORT'=>'3306',
		'DB_PREFIX'=>'think_',
		'APP_AUTOLOAD_PATH'=>'@.TagLib',
		'SESSION_AUTO_START'=>true,
		'USER_AUTH_ON'              =>true,
		'USER_AUTH_TYPE'			=>1,		// 默认认证类型 1 登录认证 2 实时认证
		'USER_AUTH_KEY'             =>'authId',	// 用户认证SESSION标记
		'ADMIN_AUTH_KEY'			=>'administrator',
		'USER_AUTH_MODEL'           =>'Admin',	// 默认验证数据表模型
		'AUTH_PWD_ENCODER'          =>'md5',	// 用户认证密码加密方式
		'USER_AUTH_GATEWAY'         =>'/Public/login',// 默认认证网关
		'NOT_AUTH_MODULE'           =>'Public',	// 默认无需认证模块
		'REQUIRE_AUTH_MODULE'       =>'',		// 默认需要认证模块
		'NOT_AUTH_ACTION'           =>'',		// 默认无需认证操作
		'REQUIRE_AUTH_ACTION'       =>'',		// 默认需要认证操作
		'GUEST_AUTH_ON'             =>false,    // 是否开启游客授权访问
		'GUEST_AUTH_ID'             =>0,        // 游客的用户ID
		'DB_LIKE_FIELDS'            =>'title|remark',
		'RBAC_ROLE_TABLE'           =>'think_role',
		'RBAC_USER_TABLE'           =>'think_role_user',
		'RBAC_ACCESS_TABLE'         =>'think_access',
		'RBAC_NODE_TABLE'           =>'think_node',
		'SHOW_PAGE_TRACE'=>1,//显示调试信
		'PAGE_SIZE'=>15,
		'TMPL_PARSE_STRING'=>array('__PUBLIC__' => '/Admin/Resources',)
	);
?>