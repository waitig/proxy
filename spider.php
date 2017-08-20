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
    'country' => array('td:nth-child(1) > img','alt'),
    'IP' => array('td:nth-child(2)','text'),
    'port' => array('td:nth-child(3)','text'),
    'location' => array('td:nth-child(4) > a','text'),
    'anonymous' => array('td:nth-child(5)','text'),
    'type' => array('td:nth-child(6)','text'),
    'speed' => array('td:nth-child(7) > div','title'),
    'connection' => array('td:nth-child(8) > div','title'),
    'survival_time' => array('td:nth-child(9)','text'),
    'verification_time' => array('td:nth-child(10)','text')
);
for($j=0;$j<count($url);$j++){
    $data = QueryList::Query($url[$j],$proxyRules,'#ip_list tr')->data;
    for($i=1;$i<count($data);$i++){
        var_dump($data[$i]);
        insertIntoDB( $data[$i]);
    }
}


/**
 * @param $proxyData
 * 向数据库中写入代理，每次一条，无则插入，有则更新
 * 等英博客出品 http://www.waitig.com
 */
function insertIntoDB($proxyData){
    global $wpdb;
    $queryNum=$wpdb->query($wpdb->prepare(
        'REPLACE INTO wp_proxy (country, IP, port, location, anonymous,type, speed, connection, survival_time, verification_time)
VALUES (%s,%s,%d,%s,%s,%s,%s,%s,%s,%s)',
        $proxyData['country'],
        $proxyData['IP'],
        $proxyData['port'],
        $proxyData['location'],
        $proxyData['anonymous'],
        $proxyData['type'],
        $proxyData['speed'],
        $proxyData['connection'],
        $proxyData['survival_time'],
        $proxyData['verification_time']
    ));
    echo '插入或更新：'.$queryNum.'条</br>';
}

