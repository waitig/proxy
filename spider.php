<?php
/**
 * Created by PhpStorm.
 * User: lius
 * Date: 2017/8/20
 * Time: 15:34
 * From: 等英博客出品 http://www.waitig.com
 */
require_once 'Snoopy.class.php';
require_once 'common.php';
require_once 'phpQuery/phpQuery.php';
require_once 'phpQuery/QueryList.php';

use QL\QueryList;

$url = array(
    "http://www.xicidaili.com/nn/",
    "http://www.xicidaili.com/nt/",
    "http://www.xicidaili.com/wn/",
    "http://www.xicidaili.com/wt/"
);

/*a.country,
a.IP,
  a.port,
  a.location,
  a.anonymous,
  a.type,
  a.speed,
  a.connection,
  a.survival_time,
  a.verification_time*/

$proxyRules = array(//#ip_list > tbody > tr:nth-child(2) > td:nth-child(1) > img
    'country' => array('td:nth-child(1) > img', 'alt'),
    'IP' => array('td:nth-child(2)', 'text'),
    'port' => array('td:nth-child(3)', 'text'),
    'location' => array('td:nth-child(4) > a', 'text'),
    'anonymous' => array('td:nth-child(5)', 'text'),
    'type' => array('td:nth-child(6)', 'text'),
    'speed' => array('td:nth-child(7) > div', 'title'),
    'connection' => array('td:nth-child(8) > div', 'title'),
    'survival_time' => array('td:nth-child(9)', 'text'),
    'verification_time' => array('td:nth-child(10)', 'text')
);
$proxyDataList=[];
$proxyData=getProxyData();
for ($j = 0; $j < count($url); $j++) {
    $html = getUrlHtml($url[$j],$proxyData->ip,$proxyData->port);
    $data = QueryList::Query($html, $proxyRules, '#ip_list tr')->data;
    $proxyDataList=array_merge($proxyDataList,$data);
    sleep(3);
}
insertIntoDB($proxyDataList);

/**
 * @param $proxyData
 * 向数据库中写入代理，批量写入，有则更新，无则插入
 * 等英博客出品 http://www.waitig.com
 */
function insertIntoDB($proxyDataList)
{
    global $wpdb;
    $queryStr = 'REPLACE INTO wp_proxy (country, IP, port, location, anonymous,type, speed, connection, survival_time, verification_time)
VALUES ';
    for ($i = 1; $i < count($proxyDataList); $i++) {
        $proxyData = $proxyDataList[$i];
        if($proxyData['IP']=='')
            continue;
        $queryStr .= '(\'' .
            $proxyData['country'] . '\',\'' .
            $proxyData['IP'] . '\',' .
            $proxyData['port'] . ',\'' .
            $proxyData['location'] . '\',\'' .
            $proxyData['anonymous'] . '\',\'' .
            $proxyData['type'] . '\',' .
            str_replace('秒', '', $proxyData['speed']) . ',' .
            str_replace('秒', '', $proxyData['connection']) . ',\'' .
            $proxyData['survival_time'] . '\',\'' .
            $proxyData['verification_time'] .
            '\'),';
    }
    $queryStr = substr($queryStr, 0, strlen($queryStr) - 1);
    $queryNum = $wpdb->query($queryStr);
    //$queryNum=0;
    if($queryNum==0){
        header('HTTP/1.1 505 ERROR');
        header("status: 505 ERROR");
    }
    echo 'Insert or update :' . $queryNum . '<br/>';
}

function getUrlHtml($url,$proxyHost,$proxyPort){
    $snoopy = new Snoopy;
    $snoopy->proxy_host = $proxyHost;
    $snoopy->proxy_port = $proxyPort;
    $snoopy->agent = "(compatible; MSIE 4.01; MSN 2.5; AOL 4.0; Windows 98)";
    $snoopy->referer = "https://www.baidu.com";
    $snoopy->fetch($url); //获取所有内容
    //var_dump($snoopy->results);
    return $snoopy->results; //显示结果
}

function getProxyData(){
    global $wpdb;
    $queryStr = '
    SELECT ip,port FROM `wp_proxy` WHERE verification_time = (
    SELECT max(verification_time) FROM wp_proxy WHERE anonymous=\'透明\');
    ';
    $proxyData = $wpdb->get_results($queryStr);
    return $proxyData[0];
    //var_dump($proxyData);
}
