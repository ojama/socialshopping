<?php
class ShopAction extends Action{
	public function index()
	{
		import('@.ORG.Yiqifa.YiqifaOpen');
		$client=new YiqifaOpen();
		$list=json_decode($client->getCategoryList());
		var_dump($list);
	}
	
	public function bulidcategory()
	{
		import('@.ORG.Yiqifa.YiqifaOpen');
		$client=new YiqifaOpen();
		$list=json_decode($client->getCategoryList());
		$sql="";
		foreach($list->categorys as $cat)
		{
			$sql.=",('$cat->catName')";
		}
		$sql ="INSERT into ".C('DB_PREFIX')."goods_category (categoryname)  values".substr($sql,1);
		$m=M('shop_category');
		$m->query("TRUNCATE TABLE `".C('DB_PREFIX')."goods_category`");
		$m->query($sql);
		$list=$m->select();
		$sql="";
		foreach($list as $cat)
		{
			$subcategory=json_decode($client->getSubCategory($cat['categoryname']));
			foreach($subcategory->categorys as $item)
			{
				$sql.=",('".$item->subCatName."',".$cat['id'].")";
			}
		}
		$sql="INSERT into ".C('DB_PREFIX')."goods_category (categoryname,parentid)  values".substr($sql,1);
		$m->query($sql);
	}	
}