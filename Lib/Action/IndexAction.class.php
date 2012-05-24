<?php
class IndexAction extends Action{
	public function _initialize(){
        $username=cookie('username');
		$userid=cookie('userid');
		$this->assign('username',$username);
		$this->assign('userid',$userid);
	}
	
    public function index() {
        $this->display();
    }
	
	public function search() {		
		import('@.ORG.Yiqifa.YiqifaOpen');//导入一起发sdk
		import("ORG.Util.Page");
		$client=new YiqifaOpen();
		/**
		组合搜索参数
		*/
		$params['keyword']=str_replace(' ','',trim($_REQUEST['keyword']));
		//$params['merchantids']='100038,100049';//多商家搜索接口还需测试
		//$params['merchantids']=intval($_REQUEST['merchantids']);
		if(intval($_REQUEST['minprice'])!=0){$params['minprice']=intval($_REQUEST['minprice']);}
		if(intval($_REQUEST['maxprice'])!=0){$params['maxprice']=intval($_REQUEST['maxprice']);}
		if(intval($_REQUEST['ordertype'])!=0){$params['ordertype']=intval($_REQUEST['ordertype']);}	
		$page=intval($_REQUEST['p'])>1?intval($_REQUEST['p']):1;
		$products=json_decode($client->searchProduct($params,$page,C('PAGE_SIZE')));
		//var_dump($products);
		$rescount=sizeof($products->results);
		$list=array();
		$ids="";
		foreach($products->results as $product)
		{
			parse_str($product->url,$tmp);
			$product->rawurl=$tmp['t'];
			$list[]=get_object_vars($product);
			$ids.=",".$product->id;
		}

		/*产品入库*/	
		$m=M('product');
		$ids="id in (".substr($ids,1).")";
		/**
		返回的$db_list是以主键id为下标的二维数组
		*/
		$db_list=$m->where($ids)->getField("id,good,bad,commentcount");
		//$updatelist=array();
		$insertlist=array();
		for($i=0;$i<$rescount;$i++)
		{
			if(is_array($db_list[$list[$i]['id']]))
			{
				//$updatelist[]=$list[$i];
				$m->where("id=".$list[$i]['id'])->data($list[$i])->save();
				$list[$i]['good']=$db_list[$list[$i]['id']]['good'];
				$list[$i]['bad']=$db_list[$list[$i]['id']]['bad'];
				$list[$i]['commentcount']=$db_list[$list[$i]['id']]['commentcount'];
			}
			else
			{
				$insertlist[]=$list[$i];
				$list[$i]['good']=0;
				$list[$i]['bad']=0;
				$list[$i]['commentcount']=0;
			}
		}
		
		$m->addAll($insertlist,array(),false);		
		
		//分页处理		
		$p = new Page($products->total,C('PAGE_SIZE'));
		$pager=$p->show();
		$this->assign('keyword',trim($_REQUEST['keyword']));
		$this->assign('minprice',$params['minprice']);
		$this->assign('maxprice',$params['maxprice']);
		$this->assign('ordertype',$params['ordertype']);	
		$this->assign('pager',$pager);
		$this->assign('list',$list);
		$this->display();
	}

	/***
	团购产品搜索
	*/
	public function tuan(){
		import('@.ORG.Yiqifa.YiqifaOpen');
		import("ORG.Util.Page");
		$client=new YiqifaOpen();
		/**
		组合搜索参数
		*/
		$params['keyword']=trim($_REQUEST['keyword']);
		
		/*
		$params['category']=intval($_REQUEST['category']);
		*
		多商家搜索暂时屏蔽
		
		//$params['merchantids']=trim($_REQUEST['merchantids']);
		$params['minprice']=intval($_REQUEST['minprice']);
		$params['maxprice']=intval($_REQUEST['maxprice']);
		$params['ordertype']=intval($_REQUEST['ordertype']);
		*/
		$page=intval($_REQUEST['p'])>1?intval($_REQUEST['p']):1;	
		$list=json_decode($client->searchTuanProduct($params,3,10));
		var_dump($list);
		$this->display();
		
	}
	/***
	团购地区获取
	*/
	public function getGwkTuanDistrictorList(){
		import('@.ORG.Yiqifa.YiqifaOpen');
		$client=new YiqifaOpen();	
		$list=json_decode($client->getGwkTuanDistrictorList());
		echo sizeof($list->citys);
		foreach($list->citys as $city){
			echo $city->citybelong."&nbsp;&nbsp;".$city->name."<br>";
		}
		
	}
	/***
	全部团购站点
	*/
	public function tuanwebsite(){
		import('@.ORG.Yiqifa.YiqifaOpen');
		$client=new YiqifaOpen();
		$TuanWibsetList=json_decode($client->getGwkTuanWibsetList());
		$list=array();
		foreach($TuanWibsetList->websites as $site){
			$list[]=get_object_vars($site);
		}
		var_dump($list);
	}

	
	public function shop(){
		import('@.ORG.Yiqifa.YiqifaOpen');
		$client=new YiqifaOpen();	

	}
	
	public function compare(){
		//var_dump(cookie('comparedata'));
		//echo cookie('comparelist');
		//$comparelist=explode('_',cookie('comparelist'));
		$comparelist=explode('_',trim($_GET['comparelist']));
		if(sizeof($comparelist)==0){
			$this->error('比较栏里还没有产品呢');
		}elseif(sizeof($comparelist)==1){
			$condition="id=".intval($comparelist[0]);
		}/*elseif(sizeof($comparelist)>5){
			$this->error('比较数量超出限制');
		}*/else{
			$condition="";
			foreach($comparelist as $item){
				$condition.=",".intval($item);
			}
			$condition="id in (".substr($condition,1).")";
		}
		$p=M('product');
		//echo $condition;
		$list=$p->where($condition)->select();
		//var_dump($list);
		$this->assign('list',$list);
		$this->display();
	}
}
?>