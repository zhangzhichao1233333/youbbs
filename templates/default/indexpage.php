<?php 
if (!defined('IN_SAESPOT')) exit('error: 403 Access Denied'); 

echo '
<div class="nav-title">
       <i class="fa fa-angle-double-right"></i> 第',$page,'页 / 共',$taltol_page,'页';
echo '    <div class="c"></div>
</div>

<div class="main-box home-box-list">';

foreach($articledb as $article){
echo '
<div class="post-list">
    <div class="item-avatar"><a href="/user/',$article['uid'],'">';
if(!$is_spider){
    echo '<img src="/avatar/large/',$article['uavatar'],'.png" alt="',$article['author'],'" />';
}else{
    echo '<img src="/static/grey.gif" data-original="/avatar/large/',$article['uavatar'],'.png" alt="',$article['author'],'" />';
}
echo '    </a></div>
    <div class="item-content">
        <h1><a href="/topics/',$article['id'],'">',$article['title'],'</a></h1>
        <span class="item-date"><i class="fa fa-archive"></i> <a href="/nodes/',$article['cid'],'">',$article['cname'],'</a>&nbsp;&nbsp;<i class="fa fa-user"></i> <a href="/user/',$article['uid'],'">',$article['author'],'</a>';
if($article['comments']){
    echo '&nbsp;&nbsp;<i class="fa fa-clock-o"></i> ',$article['edittime'],'&nbsp;&nbsp;<i class="fa fa-user-secret"></i> 最后回复来自 <a href="/user/',$article['ruid'],'">',$article['rauthor'],'</a>';
}else{
    echo '&nbsp;&nbsp;<i class="fa fa-clock-o"></i> ',$article['addtime'];
}
echo '        </span>
    </div>';
if($article['comments']){
    $gotopage = ceil($article['comments']/$options['commentlist_num']);
    if($gotopage == 1){
        $c_page = '';
    }else{
        $c_page = '/'.$gotopage;
    }
    echo '<div class="item-count"><a href="/topics/',$article['id'],$c_page,'#reply',$article['comments'],'">',$article['comments'],'</a></div>';
}
echo '    <div class="c"></div>
</div>';

}


if($taltol_article > $options['list_shownum']){ 
echo '<div class="pagination">';
if($page>1){
echo '<a href="/page/',$page-1,'" class="float-left"><i class="fa fa-angle-double-left"></i> 上一页</a>';
}
if($page<$taltol_page){
echo '<a href="/page/',$page+1,'" class="float-right">下一页 <i class="fa fa-angle-double-right"></i></a>';
}
echo '<div class="c"></div>
</div>';
}


echo '</div>';


?>
