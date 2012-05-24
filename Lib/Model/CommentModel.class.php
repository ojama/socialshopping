<?php
class CommentModel extends Model{
	//自动验证
    protected $_validate = array(
        array("userid", "require", "请先登录！"),
        array('productid', 'require', "请选择你希望评论的产品！"),
        array('comment', 'require', "内容必须！"),
    );
	
    //自动填充设置
    protected $_auto = array(
        array('addtime', 'time', 1, 'function')
    );	
}
