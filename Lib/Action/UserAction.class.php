<?php
class UserAction extends Action{
	
	public function _initialize(){
        $username=cookie('username');
		$userid=cookie('userid');
		$this->assign('username',$username);
		$this->assign('userid',$userid);
	}	
	
	/**
	登录状态验证等等返回用户基本信息数组
	*/
	public function islogin(){	
	$user['userid']=cookie('userid');
	$user['username']=cookie('username');
	$user['rand']=cookie('rand');
	if(trim($user['userid'])==''||trim($user['username'])==''||trim($user['rand'])==''){
		$this->error('请先登录','/User/login');
		exit;
	}
	$u=D('user');
	$r=$u->where("userid=".$user['userid'])->field('userid,username,rand')->find();
	if($r['userid']!=$user['userid']||$r['username']!=$user['username']||$r['rand']!=$user['rand']){
		$this->error('请先登录','/User/login');
		exit;
	}
	
	return $r;
	}
	
	
	public function index(){
		import('@.ORG.sami.functions','','.php');
		$user=$this->islogin();
		$Comment=M();
		$RecentComment=$Comment->query("SELECT c.id,c.productid,c.comment,c.addtime,p.productName,p.url,p.merchantId,p.merchantName,p.merchantTopurl from ".C('DB_PREFIX')."comment as c left join ".C('DB_PREFIX')."product as p  on c.productid=p.id where  c.userid=".$user['userid']." order by c.id desc limit ".C('PAGE_SIZE'));
		//var_dump($RecentComment);
		$this->assign('user',$user);
		$this->assign('RecentComment',$RecentComment);
		$this->display();
	}
	
	public function mycomment(){
		$user=$this->islogin();
		import('@.ORG.sami.functions','','.php');
		import("ORG.Util.Page");
		$p=intval($_REQUEST['p'])>1?intval($_REQUEST['p']):1;
		//$pagesize=C('PAGE_SIZE');
		$Comment=M();
		$list=$Comment->query("SELECT c.id,c.productid,c.comment,c.addtime,p.productName,p.url,p.merchantId,p.merchantName,p.merchantTopurl from ".C('DB_PREFIX')."comment as c left join ".C('DB_PREFIX')."product as p  on c.productid=p.id where  c.userid=".$user['userid']." order by c.id desc limit ".C('PAGE_SIZE'));
		$total=$Comment->query("SELECT count(id) as total from ".C('DB_PREFIX')."comment where userid=".$user['userid']);
		//分页处理		
		$pager = new Page($total[0]['total'],C('PAGE_SIZE'));	
		$this->assign('list',$list);
		$this->assign('pager',$pager->show());
		$this->display();
	}
	
	public function myrecord(){
		$user=$this->islogin();
		import('@.ORG.sami.functions','','.php');
		import("ORG.Util.Page");
		$p=intval($_REQUEST['p'])>1?intval($_REQUEST['p']):1;
		$pagesize=C('PAGE_SIZE');
				
				
	}
	
	public function editinfo(){
		$user=$this->islogin();
		$U=D('user');
		$user=$U->where("userid=".$user['userid'])->find();
		$this->assign('user',$user);
		$this->display();	
	}
	
	public function act_editinfo(){
		$user=$this->islogin();
		$U=M('user');
		if($_FILES['avatar']['size']>0){
			import("ORG.Net.UploadFile");
			$upload = new UploadFile();	
			$upload->maxSize  = 3145728 ;	
			$upload->allowExts  = array('jpg', 'png','gif', 'jpeg');
			$upload->savePath =  './Public/Uploads/Avatar/';
			$upload->saveRule = 'com_create_guid';
			/*	$upload->thumb = true;
			$upload->thumbPrefix = 's_,m_';
			$upload->thumbMaxWidth = "50,200";
			$upload->thumbMaxHeight = "50,200";
			$upload->thumbRemoveOrigin = true;*/
			
			if(!$upload->upload()) {	
				$this->error($upload->getErrorMsg());		
			}else{		
				$info =  $upload->getUploadFileInfo();
				$data['avatar'] = substr($info[0]['savepath'],1).$info[0]['savename'];
			}
		}
		//$data['email'] = trim($_POST['email']);
		$U->where("userid=".$user['userid'])->save($data);
		$this->success('成功修改了信息','/User/editinfo');
	}	

	public function editsaveinfo(){
		$user=$this->islogin();
/*		$U=D('user');
		$user=$U->where("userid=".$user['userid'])->find();*/
		$this->assign('user',$user);
		$this->display();	
	}

	public function act_editsaveinfo(){
		$user=$this->islogin();
		$U=D('user');
		$data=array();
		$data['email'] = trim($_POST['email']);
		$user=$U->where("userid=".$user['userid'])->find();
		if($_POST['password']!=''){
			if($_POST['password']!=$_POST['repassword']){
				$this->error('重复密码与新密码不一致！');
			}
			$data['password']=md5($_POST['password']);
		}
		if($user['password']!=md5($_POST['oldpassword'])){
			$this->error('原密码错误！');
		}
		$U->where("userid=".$user['userid'])->save($data);
		$this->success('成功修改密码','/User/editinfo');
	}
	
		
	public function register(){
		$this->display();
	}
	

	public function act_register(){
		$u=D('User');
		if(!$u->create($_POST)){
			$this->error($u->getError());
		}
		$u->__set('password',md5($u->__get('password')));
		$u->add();
		$this->success('注册成功','/');
	}
	
	public function login(){
		$this->display();
	}
	
	public function act_login(){
		$username=trim($_POST['username']);
		$passowrd=trim($_POST['password']);
		$u=D('User');
		$user=$u->where("username='".$username."'")->field('userid,username,password')->find();
		if(md5($passowrd)!=$user['password']){
			$this->error('密码错误','/User/login');
			exit;
		}
		
		$user['rand']=rand(100000,999999);
		//$u->save($user);
		$u->where("userid=".$user['userid'])->setField('rand',$user['rand']);
		cookie('userid',$user['userid']);
		cookie('username',$user['username']);
		cookie('rand',$user['rand']);
		$this->success('成功登录','/');
	}
	
	public function logout(){
		cookie(null);
		$this->success('成功退出','/');			
	}

}