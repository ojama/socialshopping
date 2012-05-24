<?php
include_once('yiqifaconfig.php' );
include_once('YiqifaUtils.php' );
class YiqifaOpen{
    
    var $consumerKey;
    
    var $consumerSecret;
    
    function __construct($key=YQF_C_KEY,$secret=YQF_C_SECRET) {
               
       $this->consumerKey = $key;
       $this->consumerSecret = $secret;
       
    }
    
    /**
     * 分页查询购物客合作商家
     *
     * @param query
     *            查询条件
     * @param page
     *            查询页
     * @param rowcount
     *            每页查询数量
     * @return
     */
    function getMerchants($category="",$page=1,$rowCount=100){ 
        
        $url = YiqifaUtils::getBaseUrl()."/merchant.json?cat=".$category."&page=".$page."&pageRowCount=".$rowCount;
        
        $result = YiqifaUtils::sendRequest($url,$this->consumerKey,$this->consumerSecret);
        
        return $result;
    }
    /**
     *  查询购物客购物客商品分类
     * @return 一级分类
     */
   function getCategoryList(){ 
   	 
        $url = YiqifaUtils::getBaseUrl()."/category.json";
        
        $result = YiqifaUtils::sendRequest($url,$this->consumerKey,$this->consumerSecret);
        
        return $result;
    }
    /**
     * 分页查询购物客商品二级分类
     *
     * @param query
     *            查询条件
     * @param page
     *            查询页
     * @param rowcount
     *            每页查询数量
     * @return
     */
    function getSubCategory($category,$page=1,$rowCount=100){ 
        
        $url = YiqifaUtils::getBaseUrl()."/category/subcategory.json?category=".urlencode($category)."&page=".$page."&pageRowCount=".$rowCount;
        
        $result = YiqifaUtils::sendRequest($url,$this->consumerKey,$this->consumerSecret);
        
        return $result;
    }
    /**
     * 分页查询购物客商品
     *
     * @param query
     *            查询条件
     * @param page
     *            查询页
     * @param rowcount
     *            每页查询数量
     * @return
     */
    function searchProduct($params,$page=1,$rowCount=18){
        
        $url = YiqifaUtils::getBaseUrl()."/product/search.json";
        
        $url .= "?keyword=".$params['keyword'];
        if(isset($params['category'])){
            $url .= "&category=".$params['category'];    
        }
        if(isset($params['merchantids'])){
            $url .= "&merchantids=".$params['merchantids'];    
        }
        if(isset($params['minprice'])){
            $url .= "&minprice=".$params['minprice'];    
        }
        if(isset($params['maxprice'])){
            $url .= "&maxprice=".$params['maxprice'];    
        }
        if(isset($params['ordertype'])){
            $url .= "&ordertype=".$params['ordertype'];    
        }
        
        $url .="&page=".$page."&rowcount=".$rowCount;    
                
        $result = YiqifaUtils::sendRequest($url,$this->consumerKey,$this->consumerSecret);
        
        return $result;
        
            
    }                                                                      
    /**
     * 查询购物客团购一级分类列表
     *
     * @return
     * @throws OpenYiqifaException
     */
    function getTuanCategoryList()   {
        $url = YiqifaUtils::getBaseUrl()."/tuancategory.json";
        $result = YiqifaUtils::sendRequest($url,$this->consumerKey,$this->consumerSecret);
        return $result;
    }
    
    /**
     * 分页查询购物客团购二级分类
     *
     * @param query
     *            查询条件
     * @param page
     *            查询页
     * @param rowcount
     *            每页查询数量
     * @return
     */
    function getTuanSubCategory($category="", $page=1, $rowCount=100) {
    	$url = YiqifaUtils::getBaseUrl()."/category/tuansubcategory.json?category=".$category."&page=".$page."&pageRowCount=".$rowCount;
    	$result = YiqifaUtils::sendRequest($url,$this->consumerKey,$this->consumerSecret);
    	return $result;
    }
    
    /**
     * 分页查询购物客团购商品
     *
     * @param query
     *            查询条件
     * @param page
     *            分页信息类
     * @return
     */
    function searchTuanProduct($params, $page=1, $rowCount=18){
    	 
    	$url = YiqifaUtils::getBaseUrl()."/tuanproduct.json";
    	$url .= "?keyword=".$params['keyword'];
    	if(isset($params['category'])){
    		$url .= "&category=".$params['category'];
    	}
    	if(isset($params['citybelong'])){
    		$url .= "&citybelong=".$params['citybelong'];
    	}
    	if(isset($params['minprice'])){
    		$url .= "&minprice=".$params['minprice'];
    	}
    	if(isset($params['maxprice'])){
    		$url .= "&maxprice=".$params['maxprice'];
    	}
    	if(isset($params['ordertype'])){
    		$url .= "&ordertype=".$params['ordertype'];
    	}
    	if(isset($params['ordercolumn'])){
    		$url .= "&ordercolumn=".$params['ordercolumn'];
    	}
    	$url .="&page=".$page."&rowcount=".$rowCount;
    	$result = YiqifaUtils::sendRequest($url,$this->consumerKey,$this->consumerSecret);
    	
    	return $result;
    }
     
    
    /**
     * 搜索购物客团购城市列表
     *
     * @param query
     *            查询参数设置类
     * @return GwkTuanCityList
     */
    function getGwkTuanDistrictorList(){
    	$url = YiqifaUtils::getBaseUrl()."/tuancity.json";
    	$result = YiqifaUtils::sendRequest($url,$this->consumerKey,$this->consumerSecret);
    	return $result;
    }
    
    /**
     * 搜索购物客团购商家
     *
     * @param query
     *            查询参数设置类
     * @return GwkTuanWibsetList
     */
     function getGwkTuanWibsetList()   {
    	$url = YiqifaUtils::getBaseUrl()."/tuanwebsite.json";
    	$result = YiqifaUtils::sendRequest($url,$this->consumerKey,$this->consumerSecret);
    	return $result;
    }
    
	/**
	*搜索单品
	*貌似还未开放
	*/
     function singleProduct($params)   {
    	$url = YiqifaUtils::getBaseUrl()."/product/singleproduct.json";
    	$url.="?merchantId=".$params['merchantId'];
		$url.="&productUrl=".$params['productUrl'];
		$url.="&feedback=".$params['feedback'];
		$url.="&websiteId=".$params['websiteId'];
		var_dump($url);	
    	$result = YiqifaUtils::sendRequest($url,$this->consumerKey,$this->consumerSecret);
    	return $result;
    }	
	
}
?>
