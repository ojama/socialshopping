<?php
function http_request($url,$method='GET',$data='',$cookie='',$refer=''){
	$header='';
	$body='';
	$newcookie='';
	if (preg_match('/^http:\/\/(.*?)(\/.*)$/',$url,$reg)){
		$host=$reg[1]; $path=$reg[2];
	}
	else {outs(1,"URL($url)格式非法!"); return;
	}
	$http_host=$host;
	if (preg_match('/^(.*):(\d+)$/', $host, $reg)) {
		$host=$reg[1]; $port=$reg[2];
	}
	else $port=80;
	$fp = fsockopen($host, $port, $errno, $errstr, 30);
	if (!$fp) {
		outs(1,"$errstr ($errno)\n");
	} else {
		fputs($fp, "$method $path HTTP/1.1\r\n");
		fputs($fp, "Host: $http_host\r\n");
		if ($refer!='') fputs($fp, "Referer: $refer\r\n");
		if ($cookie!='') fputs($fp, "Cookie: $cookie\r\n");
		fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
		fputs($fp, "Content-length: ".strlen($data)."\r\n");
		fputs($fp, "Connection: close\r\n\r\n");
		fputs($fp, $data . "\r\n\r\n");
		$header_body=0;
		$chunked_format=0;
		$chunked_len=0;
		while (!feof($fp)) {
			$str=fgets($fp);
			//$len=hexdec($str);        if ($header_body==1) {echo ">>$str\t$len\n";        $str=fread($fp,$len);echo $str;}
			if ($header_body==1){
				if ($chunked_format){
					if ($chunked_len<=0){
						$chunked_len=hexdec($str);
						if ($chunked_len==0) break;
						else continue;
					} else {
						$chunked_len-=strlen($str);
						if ($chunked_len<=0) $str=trim($str);
						//elseif ($chunked_len==0) fgets($fp);
					}
				}
				$body.=$str;
			}
			else if ($str=="\r\n") $header_body=1;
			else {
				$header.=$str;
				if ($str=="Transfer-Encoding: chunked\r\n") $chunked_format=1;
				if (preg_match('|Set-Cookie: (\S+)=(\S+);|',$str,$reg)) $newcookie.=($newcookie==''?'':'; ').$reg[1].'='.$reg[2];
			}
		}
		fclose($fp);
	}
	$GLOBALS['TRAFFIC']+=414+strlen($url)+strlen($data)+strlen($header)+strlen($body);
	if (preg_match('/^Location: (\S+)\r\n/m',$header,$reg)) {
		if (substr($reg[1],0,1)!='/'){
			$path=substr($path,0,strrpos($path,'/')+1);
			$path.=$reg[1];
		} else $path=$reg[1];
		if ($newcookie) $cookie=$newcookie;
		return http_request('http://'.$http_host.$path,'GET','',$cookie,$url);
	}
	return array($body, $header, $newcookie);
}




//$A=trim(urlencode($_REQUEST['A']));
//$B=trim(urlencode($_REQUEST['B']));
//$params="A=$A&B=$B";
//list($body,$header)=http_request('http://openapi.yiqifa.com/category.json','POST',$params);

//如果你无需检查返回结果，那就这样也可以：
//http_request('http://openapi.yiqifa.com/','POST',$params);

function sentrequest(){
if(empty($_REQUEST['email']))
{
	 
	$fp = fsockopen("http://openapi.yiqifa.com", 8080, $errno, $errstr, 30);
	if (!$fp) {
		echo "$errstr ($errno)
				/n";
	} else {
	$out = "POST /category.json HTTP/1.1/r/n";
	$out .= "Host: http://openapi.yiqifa.com";
	$out .= "User-Agent: Mozilla/5.0 (X11; U; Linux i686; en-GB; rv:1.9.2.15) Gecko/20110303 Ubuntu/10.04 (lucid) Firefox/3.6.15/r/n";
	$out .= "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8/r/n";
	$out .= "Accept-Language: en-gb,en;q=0.5/r/n";
	$out .= "Accept-Encoding: gzip,deflate/r/n";
	$out .= "Content-Type: application/x-www-form-urlencoded/r/n";
	$out .= "Content-Length: 80/r/n";
	$out .= "Connection: Close/r/n/r/n";
	$out .= "email=youname%40gmail.com&password=youpasswd&act=login&redirectURL=&loginsubmit=/r/n/r/n";
	fwrite($fp, $out);
	while (!feof($fp)) {
	echo fgets($fp, 128);
				}
				fclose($fp);
}
				}
				else
				{
print_r($_REQUEST);
}
}
//用php发送http请求的主要分两部(1)构造一个http头部的串.(2)用fsockopen打开socket连接。(3)再用fwrite把构造好的数据传送到请求主机

?>