<?php
/**
 * Created by PhpStorm.
 * User: lius
 * Date: 2017/8/19
 * Time: 23:01
 * From: 等英博客出品 http://www.waitig.com
 */

/**
 *把所有页面的标题都改成 “EndSkin”
 *新的 WordPress 网页标题设置方法
 *https://www.endskin.com/new-document-title/
 */
require_once 'common.php';
global $wpdb;
$input = file_get_contents("php://input"); //接收POST数据
$inputJson = json_decode($input);
$type = $_GET['type'];
$pageSize = 40;

$numRows = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM wp_proxy;","") );
$pages=intval($numRows/$pageSize);
if (isset($_GET['page'])){
    $page=intval($_GET['page']);
}
else{
    $page=1; //否则，设置为第一页
}

if (isset($_GET['$type'])){
    $type=intval($_GET['$type']);
}
else{
    $type=0;
}

$startNum = ($page-1)*$pageSize;
$queryStr = "SELECT
  a.id ,
  a.country,
  a.IP,
  a.port,
  a.location,
  a.anonymous,
  a.type,
  a.speed,
  a.connection,
  a.survival_time,
  a.verification_time
FROM wp_proxy a
WHERE a.type= %s  OR a.anonymous = %s OR 0 = %d 
ORDER BY a.verification_time DESC LIMIT $startNum ,$pageSize;";
$queryType = '';
$queryAnonymous = '';
$queryCheck = 1;
//取所有
if ($type == null || $type == '' || $type == 0) {
    $queryType = '';
    $queryAnonymous = '';
    $queryCheck = 0;
}
//取高匿
elseif ($type == 1) {
    $queryType = '';
    $queryAnonymous = '高匿';
    $queryCheck = 1;
}
//取透明
elseif ($type == 2) {
    $queryType = '';
    $queryAnonymous = '透明';
    $queryCheck = 1;
}
//取HTTP
elseif ($type == 3) {
    $queryType = 'HTTP';
    $queryAnonymous = '';
    $queryCheck = 1;
}
//取HTTPS
elseif ($type == 4) {
    $queryType = 'HTTPS';
    $queryAnonymous = '';
    $queryCheck = 1;
}
$proxyList = $wpdb->get_results($wpdb->prepare(
    $queryStr, $queryType,$queryAnonymous,$queryCheck
)
);
//$resultJson = json_encode($proxyList);
$blogUrl = home_url('/');
$blogName = get_option('blogname');
$proxyUrl = $blogUrl . 'proxy/';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <title>国内外免费代理列表 - 实时更新 - <?= $blogName ?></title>
    <meta name="description" itemprop="description"
          content="<?= $blogName ?>，专门为大家收集的国内外常用的免费代理。有透明代理，高匿名代理，HTTP代理及HTTPS代理等，希望能满足大家的需求！"/>
    <meta name="keywords" itemprop="keywords" content="免费代理,<?= $blogName ?>,透明代理,高匿代理,HTTP代理,HTTPS代理"/>
    <link href="//cdn.staticfile.org/twitter-bootstrap/3.0.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="//cdn.staticfile.org/twitter-bootstrap/3.0.1/js/bootstrap.min.js"></script>
</head>
<body>
<nav class="navbar  navbar-inverse" role="navigation">
    <div class="container-fluid" style="max-width: 80%; margin:auto;">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target="#example-navbar-collapse">
                <span class="sr-only">切换导航</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?= $blogUrl ?>"><?= $blogName ?></a>
        </div>
        <div class="collapse navbar-collapse" id="home-navbar-collapse">
            <ul class="nav navbar-nav pull-right">
                <li <?php if($type==0) echo 'class= "active"'?>><a href="<?= $proxyUrl ?>">免费代理</a></li>
                <li <?php if($type==1) echo 'class= "active"'?>><a href="<?= $proxyUrl ?>?type=1">高匿名代理</a></li>
                <li <?php if($type==2) echo 'class= "active"'?>><a href="<?= $proxyUrl ?>?type=2">透明代理</a></li>
                <li <?php if($type==3) echo 'class= "active"'?>><a href="<?= $proxyUrl ?>?type=3">HTTP代理</a></li>
                <li <?php if($type==4) echo 'class= "active"'?>><a href="<?= $proxyUrl ?>?type=4">HTTPS代理</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container border-box min-height-div">
    <div class="row clearfix">
        <div class="col-md-12 column article-content">
            <div class="col--sm-offset-1 col-sm-10">
                <h2 class="text-center">
                    国内外免费代理 - 等英博客
                </h2>
                <br/>
                <br/>
                <div id="proxy-div">
                    <div class="form-group row clearfix">
                        <span style="color:red" id="alertSpan"></span>
                    </div>
                    <div class="form-group row clearfix">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr class="success">
                                <th>国家</th>
                                <th>IP</th>
                                <th>端口</th>
                                <th>地点</th>
                                <th>匿名性</th>
                                <th>类型</th>
                                <th>速度</th>
                                <th>延迟</th>
                                <th>存活时间</th>
                                <th>验证时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($proxyList as $proxy){?>
                            <tr>
                                <td><?=$proxy->country?></td>
                                <td><?=$proxy->IP?></td>
                                <td><?=$proxy->port?></td>
                                <td><?=$proxy->location?></td>
                                <td><?=$proxy->anonymous?></td>
                                <td><?=$proxy->type?></td>
                                <td><?=$proxy->speed?> s</td>
                                <td><?=$proxy->connection?> s</td>
                                <td><?=$proxy->survival_time?></td>
                                <td><?=$proxy->verification_time?></td>
                            </tr>
                            <?php }?>
                            </tbody>
                        </table>

                    </div>
                </div>
                <div style="margin-bottom: 10px;margin-top: 10px;">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    共有<?=$numRows?>条记录，共有<?=$pages?>页，每页<?=$pageSize?>条记录，当前第<label id="currentPage"><?=$page?></label>页&nbsp;&nbsp;&nbsp;&nbsp;
                    <nav>
                        <ul class="pagination">
                            <?php for($pageTmp = 1;$pageTmp < $pageSize; $pageTmp++){?>
                            <li <?php if($pageTmp==$page) echo 'class= "active"'?>><a href="<?= $proxyUrl ?>?type=<?=$type?>&page=<?=$pageTmp?>"><?=$pageTmp?></a></li>
                            <?php }?>
                        </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
</script>
</body>
</html>