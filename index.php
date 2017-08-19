<?php
/**
 * Created by PhpStorm.
 * User: lius
 * Date: 2017/8/19
 * Time: 23:01
 * From: 等英博客出品 http://www.waitig.com
 */
define('WP_USE_THEMES', true);
if(!defined('BASE_PATH'))
    define('BASE_PATH',str_replace( '\\' , '/' , realpath(dirname(__FILE__).'/../')));//获取根目录
require_once(BASE_PATH.'/wp-load.php' );//关联wordpress，可以调用wordpress里的函数
get_header();
get_footer();