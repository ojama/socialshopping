<?php
include_once 'YiqifaOpen.class.php';
$client = new YiqifaOpen ();

//$params['keyword']="电影";
//$params['merchantids']='';
$products=$client->searchTuanProduct($params,1,6);
//echo $products;
$products=json_decode($products);
echo sizeof($products->results);

var_dump($products);
//var_dump($products);

//var_dump(get_object_vars($products));
//$merchant = $client ->getMerchants("",1,20);
 //$category = $client ->getCategoryList();
 // $subcat = $client->getSubCategory ( "手机通讯", 1, 50 );
// $tuancat = $client ->getTuanCategoryList();
// $tuancity = $client ->getGwkTuanDistrictorList();
// $website = $client ->getGwkTuanWibsetList();
// $tuansubcat = $client ->getTuanSubCategory("餐饮美食",1,25);
// $tuanproduct = $client ->searchTuanProduct($params,1,50);
// echo $tuancat;
// echo $tuancity;
// echo $website;
// echo $tuansubcat;
 //echo $category;
//echo $merchant;
   //echo $subcat;
// echo $tuanproduct;
//说明：此处注释信息皆为测试信息，使用者可以打开对应的注释信息，进行接口测试 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>


<div id="header">
<form action="index.php" method="post" enctype="application/x-www-form-urlencoded" target="_self">
<p>
  <input name="keyword" type="text" />
  <input type="submit" name="button" id="button" value="搜索" />
</p>
<p>
  <input type="checkbox" name="merchantids[]" value="100016" id="merchantids_0" />
京东 
<input type="checkbox" name="merchantids[]" value="100175" id="merchantids_1" />
当当</p>
</form>
</div>
<div id="main"></div>
<div id="footer"></div>
</body>
</html>