<?php
class UserModel extends Model{
	//自动验证
    protected $_validate = array(
        array('username', 'require', '请填写用户名'),
		array('username','','帐号名称已经存在！',0,'unique',1), // 
        array('password', 'require', '请填写密码'),
		array('repassword','password','确认密码不正确',0,'confirm'), 
		array('email', 'require', '请填写您常用的邮箱'),
		array('email', 'email', '邮箱格式不正确'),
    );	
	
    //自动填充设置
    protected $_auto = array(
        array('registertime', 'time', 1, 'function')
    );
}
?>