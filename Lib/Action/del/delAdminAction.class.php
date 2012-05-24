<?php
class AdminAction extends Action{
	
	public function index(){
		
	}
	
	public function buildtuancat(){
		
	}
	
	public function gettuanproduct(){
		import('@.ORG.Yiqifa.YiqifaOpen');
		$client=new YiqifaOpen();
		$params['keyword']='';
		$page=intval($_REQUEST['p'])>1?intval($_REQUEST['p']):1;
		$list=json_decode($client->searchTuanProduct($params,$page,50));
		$data=array();
		foreach($list->results as $key=>$item){
			//$item->website=json_encode($item->website);
			//$data[]=get_object_vars($item);
			//字段大小限制所以data数组修改
			$data[$key]['addr']=$item->addr;
			$data[$key]['alliancecode']=$item->allianceCode;
			$data[$key]['bought']=$item->bought;
			$data[$key]['citybelong']=$item->cityBelong;
			$data[$key]['createtime']=$item->createTime;
			$data[$key]['curprice']=$item->curPrice;
			$data[$key]['desc']=$item->desc;
			$data[$key]['endtime']=$item->endTime;
			$data[$key]['expiredtime']=$item->expiredTime;
			$data[$key]['hotpriority']=$item->hotPriority;
			$data[$key]['id']=$item->id;
			$data[$key]['lastmodified']=$item->lastModified;
/*			$data[$key]['latitude']=$item->latitude;
			$data[$key]['longitude']=$item->longitude;*/
			$data[$key]['name']=$item->name;
			/*$data[$key]['newer']=$item->newer;*/
			$data[$key]['orderrandom']=$item->orderRandom;
			$data[$key]['oriprice']=$item->oriPrice;
			$data[$key]['pdturl']=$item->pdtUrl;
			$data[$key]['picurl']=$item->picUrl;
			$data[$key]['priority']=$item->priority;
			$data[$key]['product_category']=$item->product_category;
			$data[$key]['rebate']=$item->rebate;
			$data[$key]['region']=$item->region;
			$data[$key]['rule']=$item->rule;
			$data[$key]['searchstring']=$item->searchString;
			$data[$key]['shopname']=$item->shopName;
			$data[$key]['shortname']=$item->shortName;
			$data[$key]['soldout']=$item->soldOut;
/*			$data[$key]['subwayLine']=$item->subwayLine;
			$data[$key]['subwayStop']=$item->subwayStop;*/
			$data[$key]['successbought']=$item->successBought;
/*			$data[$key]['tel']=$item->tel;
			$data[$key]['tips']=$item->tips;*/
			$data[$key]['tradearea']=$item->tradeArea;
			$data[$key]['website']=json_encode($item->website);;
			/*$data[$key]['zk']=$item->zk;	*/
		}
		$tuan=M('tuanproduct');
		$tuan->addAll($data,array(),true);
		if($page*50<$list->total){
			$info="处理第(".$page.")页数据(".sizeof($data)."条)成功";
			$page++;
			$url="http://www.test2.com/Admin/gettuanproduct?p=".$page;
			$this->assign('waitSecond',0);
			$this->success($info,$url);	
		}else{
			echo $page;die;
			//$this->success("全部团购数据处理完成","/");
		}
	}
}
?>