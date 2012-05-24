<?php
/**评论管理*/
class CommentAction extends CommonAction {
	public function index(){
		import("ORG.Util.Page");
		$p=intval($_REQUEST['p'])>1?intval($_REQUEST['p']):1;
        if (isset($_REQUEST ['_order'])) {
            $order = $_REQUEST ['_order'];
        } else {
            $order = 'id';
        }
        //排序方式默认按照倒序排列
        //接受 sost参数 0 表示倒序 非0都 表示正序
        if (isset($_REQUEST ['_sort'])) {
            $sort = $_REQUEST ['_sort'] ? 'asc' : 'desc';
        } else {
            $sort = $asc ? 'asc' : 'desc';
        }	
		$Comment=M('comment');
		/**
		考虑使用tp内置的join 替换
		*/
		$list=$Comment->query("SELECT c.id,c.productid,c.comment,c.addtime,c.status,p.productName,p.url,p.merchantId,p.merchantName,p.merchantTopurl from ".C('DB_PREFIX')."comment as c left join ".C('DB_PREFIX')."product as p  on c.productid=p.id  order by ".$order." ".$sort." limit ".($p-1)*C('PAGE_SIZE').",".C('PAGE_SIZE'));
		$total=$Comment->count('id');
		//var_dump($list);
		//分页处理		
		$page = new Page($total,C('PAGE_SIZE'));
		//列表排序显示
		$sortImg = $sort; //排序图标
		$sortAlt = $sort == 'desc' ? '升序排列' : '倒序排列'; //排序提示
		$sort = $sort == 'desc' ? 1 : 0; //排序方式	
		$this->assign('list',$list);
		$this->assign('sort', $sort);
		$this->assign('order', $order);
		$this->assign('sortImg', $sortImg);
		$this->assign('sortType', $sortAlt);
		$this->assign('page',$page->show());
		$this->display();
	}
	
}
?>