<?php
class CommentAction extends action{
	public function add()
	{
		/**
		处理提交的点评
		*/
		$u=A('User');
		$user=$u->islogin();
		//$user=$u->islogin();
		$data['userid']=$user['userid'];
		$data['productid']=intval($_POST['productid']);
		$data['comment']=trim($_POST['comment']);
		$m=D('Comment');
		if(!$m->create($data))
		{
			$error=$m->getError();
			$this->error($error);
		}
		else
		{
			$result=$m->add();
			$p=M('product');
			$p->where("id=".$data['productid'])->setInc('commentcount',1);
			$this->ajaxReturn($result,"评论成功",1);
		}
	}
	
	public function show()
	{
		$id=intval($_REQUEST['id']);
		$p=intval($_REQUEST['p']);
		$p=$p?$p:1;
		$pagesize=10;
		$m=M();
		$sql="SELECT c.id,c.userid,c.comment,c.addtime,u.username,u.avatar from ".C('DB_PREFIX')."comment as c left join  ".C('DB_PREFIX')."user as u on c.userid=u.userid where c.productid=".$id." order by id desc limit ".($p-1)*$pagesize.",".$pagesize;
		//echo $sql;
		$list=$m->query($sql);
		$row=sizeof($list);
		//var_dump($list);
		/*采用ajax显示评论输入框和评论列表*/
		//$html="<div class=\"comment_form\"><textarea name=\"comment\" id=\"comment_textarea_".$id."\" cols=\"\"></textarea><a href=\"javascript:void(0);\" onclick=\"return false\"  id=\"comment_button_".$id."\" class=\"comment_button\">回复</a></div><div class=\"comment_list\">";
		$html="";
		for($i=0;$i<$row;$i++)
		{
			$html.="<dl comment_id=\"".$list[$i]['id']."\" class=\"comment_list W_linecolor\"><dt><img width=\"30\" height=\"30\" alt=\"".$list[$i]['username']."\" src=\"".$list[$i]['avatar']."\" ></dt><dd>".$list[$i]['username']."：".$list[$i]['comment']."</dd><dd class=\"clear\"></dd></dl>";
		}

		//$html.="</div>";
		$m=M('product');
		$totalcomment=$m->where("id=".$id)->getField('commentcount');
		$this->ajaxReturn($html,$totalcomment,1);
	}
	
	public function rate()
	{
		$id=intval($_REQUEST['id']);
		$good=intval($_REQUEST['good']);
		$bad=intval($_REQUEST['bad']);
		$ip = get_client_ip();
		
		/**
		判断商品信息是都存入数据库里
		没有则插入商品信息
		*/
		$m=M('product');
		if($good==1)
		{
			$m->where("id=$id")->setInc('good',1);
			$goodnum=$m->where("id=$id")->getField('good');
			$this->ajaxReturn('good',$goodnum,1);
		}
		elseif($bad==1)
		{
			$m->where("id=$id")->setInc('bad',1);
			$badnum=$m->where("id=$id")->getField('bad');
			$this->ajaxReturn('bad',$badnum,1);
		}
		
	}
	
}
?>