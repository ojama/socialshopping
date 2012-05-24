<?php
/**
sami 的一些常用方法
*/

/**
 * timestamp转换成显示时间格式
 * @param $timestamp
 * @return unknown_type
 */
function friendlytime($timestamp)
{
	$curTime = time();
	$space = $curTime - $timestamp;
	//1分钟
	if($space < 60)
	{
		$string = "刚刚";
		return $string;
	}
	elseif($space < 3600) //一小时前
	{
		$string = floor($space / 60) . "分钟前";
		return $string;
	}
	$curtimeArray = getdate($curTime);
	$timeArray = getDate($timestamp);
	if($curtimeArray['year'] == $timeArray['year'])
	{
		if($curtimeArray['yday'] == $timeArray['yday'])
		{
			$format = "%H:%M";
			$string = strftime($format, $timestamp);
			return "今天 {$string}";
		}
		elseif(($curtimeArray['yday'] - 1) == $timeArray['yday'])
		{
			$format = "%H:%M";
			$string = strftime($format, $timestamp);
			return "昨天 {$string}";
		}
		else
		{
			$string = sprintf("%d月%d日 %02d:%02d", $timeArray['mon'], $timeArray['mday'], $timeArray['hours'], 
			$timeArray['minutes']);
			return $string;
		}
	}
	$string = sprintf("%d年%d月%d日 %02d:%02d", $timeArray['year'], $timeArray['mon'], $timeArray['mday'], 
	$timeArray['hours'], $timeArray['minutes']);
	return $string;
}

/**
抽出来的验证方法
*/

function regex($value,$rule) {
        $validate = array(
            'require'=> '/.+/',
            'email' => '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',
            'url' => '/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/',
            'currency' => '/^\d+(\.\d+)?$/',
            'number' => '/^\d+$/',
            'zip' => '/^[1-9]\d{5}$/',
            'integer' => '/^[-\+]?\d+$/',
            'double' => '/^[-\+]?\d+(\.\d+)?$/',
            'english' => '/^[A-Za-z]+$/',
        );
        // 检查是否有内置的正则表达式
        if(isset($validate[strtolower($rule)]))
            $rule   =   $validate[strtolower($rule)];
        return preg_match($rule,$value)===1;
    }
?>