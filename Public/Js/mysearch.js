// JavaScript Document
var cookiePre='think_';
var cookieOpt = {
    //domain: '*.test2.com',
    path: '/',
    hoursToLive : 168
  };

$(document).ready(function(){
	$(".comment_form").submit(function(){
		//alert($(this).children(":text").val());
		//alert($(this).children(":hidden").val());
		var comment=$(this).children(":text").val();
		var productid=$(this).children(":hidden").val();
		var p=1;
		$.post("/Comment/add",{comment:comment,productid:productid},function(result){
			if(result.status==1){
			$.post("/Comment/show",{id:productid,p:p},function(result){
				$("#comment_list_"+productid).html(result.data).show();
				$("#comment_count_"+productid).html('评论('+result.info+')');
				},"json");
			}else{
				//alert(result.info);//请先登录
				alert('评论前请先登录');
			}
			},"json");
		return false;
		});
		
	$("#ordertypeselect").change(function(){
		$("#f").submit();
		});	
	//载入当前比较的产品
	if($.cookies.get(cookiePre+'comparelist')!=null&&$.cookies.get(cookiePre+'comparedata')!=null){
		var itemlist = $.cookies.get(cookiePre+'comparelist').toString().split('_');
		var itemdata = $.cookies.get(cookiePre+'comparedata').toString().split('[-]');
		for(var i=0;i<itemlist.length;i++){
			$("#compareitems").append(itemdata[i]);
			$("#itemid_"+itemlist[i]).bind("click",{id:itemlist[i]},function(event){
				removeitem(event.data.id)});
		}
	}
	$("#hidecompare").click(function (){
		$("#compare").hide();
		});
	$("#resetcompare").click(function(){
		$.cookies.del(cookiePre+'comparelist');
		$.cookies.del(cookiePre+'comparedata');
		$("#compareitems").html('');
		});
	$("#gocompare").click(function(){
		var comparelist = $.cookies.get(cookiePre+'comparelist');
		window.open('/Index/compare?comparelist='+comparelist);
	});
	
    $(window).scroll(function() {
        var bodyTop = 0;
        if (typeof window.pageYOffset != 'undefined') {
            bodyTop = window.pageYOffset;
        } else if (typeof document.compatMode != 'undefined' && document.compatMode != 'BackCompat') {
            bodyTop = document.documentElement.scrollTop;
        } else if (typeof document.body != 'undefined') {
            bodyTop = document.body.scrollTop;
        }
        $("#compare").css("top", 100 + bodyTop);
    });
	});
	
		
function showcomment(productid,p){
	var loading='<img scr="http://www.test2.com/Public/Images/loading.gif" width="10px" height="10px"><span>正在加载，请稍候...</span>';
	if($("#comment_form_"+productid).css('display')!='none'){
		$("#comment_form_"+productid).hide();
		$("#comment_list_"+productid).hide();
		return false;
		}
	$("#comment_form_"+productid).show();
	$("#comment_list_"+productid).html(loading).show();
	//ajax 评论列表
	$.post("/Comment/show",{id:productid,p:p},function(result){
		$("#comment_list_"+productid).html(result.data).show();
		$("#comment_count_"+productid).html('评论('+result.info+')');
		},"json");
}


function rate(productid,op){
	if(op=='good'){
	$.post("/Comment/rate",{id:productid,good:1},function(result){
		//alert(result.info);
		$("#stat_good_"+productid).html('顶：'+result.info);
		},"json");
	}
	else if(op=='bad'){
	$.post("/Comment/rate",{id:productid,bad:1},function(result){
		//alert(result.data);
		$("#stat_bad_"+productid).html('踩：'+result.info);
		},"json");		
	}
}



function addcompare(productid){
	var comparelist = $.cookies.get(cookiePre+'comparelist');
	var comparedata = $.cookies.get(cookiePre+'comparedata');
	var html='<li>'+$("#product_"+productid).children("h3").html()+'<span id="itemid_'+productid+'">移除</span></li>';
	if(comparelist==null||comparelist==''){
		comparelist=productid;
		comparedata=html;
		$.cookies.set(cookiePre+'comparelist',comparelist,cookieOpt);
		$.cookies.set(cookiePre+'comparedata',comparedata,cookieOpt);
	}else{
		var str=comparelist.toString();
		if(str.indexOf(productid)==-1){
			comparelist=comparelist+'_'+productid;
			comparedata=comparedata+'[-]'+html;
			$.cookies.set(cookiePre+'comparelist',comparelist,cookieOpt);
			$.cookies.set(cookiePre+'comparedata',comparedata,cookieOpt);
		}else{
			return false;	
		}
	}
	
	$("#compareitems").append(html);
	$("#itemid_"+productid).bind("click",function(){removeitem(productid)});
	$("#compare").show();
}


function removeitem(productid){
	var comparelist = $.cookies.get(cookiePre+'comparelist').toString();
	var comparedata = $.cookies.get(cookiePre+'comparedata').toString();
	var html='<li>'+$("#itemid_"+productid).parent("li").html()+'</li>';
	comparelist=comparelist.replace(productid+'_','');
	comparelist=comparelist.replace('_'+productid,'');
	comparelist=comparelist.replace(productid,'');
	comparedata=comparedata.replace(html+'[-]','');
	comparedata=comparedata.replace('[-]'+html,'');
	comparedata=comparedata.replace(html,'');	
	$.cookies.set(cookiePre+'comparelist',comparelist,cookieOpt);
	$.cookies.set(cookiePre+'comparedata',comparedata,cookieOpt);
	$("#itemid_"+productid).parent("li").detach();
}


String.prototype.jsonParse = function(){
if (/^[\],:{}\s]*$/.test(this.replace(/\\["\\\/bfnrtu]/g, '@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').replace(/(?:^|:|,)(?:\s*\[)+/g, ''))){
   return eval('(' + this + ')');
}
}