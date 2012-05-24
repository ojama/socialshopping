<?php
class YiqifaUtils{
   
    const BASE_URL = "http://openapi.yiqifa.com";
    
    static function getBaseUrl(){
        return YQF_OPEN_URL;
    }
    
    static function sendRequest($url,$key,$secret){
        
        $au = YiqifaUtils::generateOauth($url,$key,$secret);
        
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: ".$au));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "http://open.yiqifa.com");
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $result = curl_exec($ch);
        curl_close($ch);
        
        return iconv("GBK","UTF-8//IGNORE",$result);
    }
    
    static function getMerchants($category="",$page=1,$rowCount=100){
        $url = "http://openapi.yiqifa.com/merchant.json?page=".$page."&pageRowCount=".$rowCount;
        
        $surl = YiqifaUtils::generateRequestStr($url);
        
        $au = YiqifaUtils::generateOauth($url);
        
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: ".$au));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "http://open.yiqifa.com");
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $result = curl_exec($ch);
        curl_close($ch);
        
        return iconv("GBK","UTF-8//IGNORE",$result);      
        
            
    }    
        
   static function hmacsha1($key,$data) {
        $blocksize=64;
        $hashfunc= 'sha1';
        if (strlen($key)>$blocksize)
            $key=pack('H*',$hashfunc($key));
        $key=str_pad($key,$blocksize,chr(0x00));
        $ipad=str_repeat(chr(0x36),$blocksize);
        $opad=str_repeat(chr(0x5c),$blocksize);
        $hmac = pack(
                    'H*',$hashfunc(
                        ($key^$opad).pack(
                            'H*',$hashfunc(
                                ($key^$ipad).$data
                            )
                        )
                    )
                );
        
        return base64_encode($hmac);
    }
    
    static function generateOauth($url,$key,$secret){
        
        $authparam = self::generateAuthParams($key,$secret);       
        
        $params = array_merge($authparam,self::parseGetParams($url));
        
        $basestr = self::generateBaseStr($url,$params);
                
        $tk = $secret."&openyiqifa";
                
        $sign = self::hmacsha1($tk,$basestr);
        $str = "";
        foreach($authparam as $k=>$v){
            if($str=="") $str .= $k."=\"".urlencode($v)."\"";
            else $str.= (",".$k."=\"".urlencode($v)."\"");    
            
        }
        
        $str = "OAuth ".$str.",oauth_signature=\"".urlencode($sign)."\"";
        return $str;
    }

    
    static function generateAuthParams($key,$secret){
        $ts = strtotime("now");
        $nonce = $ts + rand();
        $authparam = array(
            "oauth_consumer_key"=>$key,
            "oauth_signature_method"=>"HMAC-SHA1",
            "oauth_timestamp"=>$ts,
            "oauth_nonce"=>$nonce,
            "oauth_version"=>"1.0",
            "oauth_token"=>"openyiqifa"
        );       
        return $authparam;    
    }
    
    static function generateRequestStr($url){
        $authparam = self::generateAuthParams();       
        
        $params = array_merge($authparam,self::parseGetParams($url));
        
        $basestr = self::generateBaseStr($url,$params);
        
        $sign = self::hmacsha1(self::TOKEN_KEYS,$basestr);     
        
        //$params['oauth_signature'] = urlencode($sign);
                
        return self::constructRequestURL($url).'?oauth_signature='.urlencode($sign)."&".self::normalizeRequestParameters($params); 
    }
    
    static function generateBaseStr($url,$params){
        $params = self::sortParams($params);        
         
        $basestr = "GET&".urlencode(self::constructRequestURL($url)).'&'.urlencode(self::normalizeRequestParameters($params));
        return $basestr;
    }
    
    
    static function normalizeRequestParameters($params){
        $s = "";
        foreach($params as $k=>$v){
            if($s==""){
                $s = $k."=".urlencode($v);
            }else{
                $s = $s."&".$k."=".urlencode($v);   
            }
        }
        return $s;   
    }
    
    static function sortParams($params){
        $keys = array_keys($params); 
        sort($keys);
        $newparams = array();
        foreach($keys as $k){
            $newparams[$k] = $params[$k];    
        } 
        return $newparams;  
    }
    
    static function constructRequestURL($url){
        $i = strpos($url,"?"); 
        if(!$i){
            return $url;
        }else{
            return substr($url,0,$i);    
        } 
          
    } 
    
    static function parseGetParams($url){
        $params = array();
        $i = strpos($url,"?");
        
        if(!$i){
            return $params;
        }
        
        $sp = explode("&",substr($url,$i+1,strlen($url)));
        
        foreach($sp as $p){
            $spi = explode("=",$p);
            if(count($spi)>1) $params[urldecode($spi[0])] = urldecode($spi[1]);
        }
        return $params;
    }       
}  
?>
