<?php
/*
 * smartcomb.php :  模块化的拼装工具
 * 模块定义modules.js格式：
 * {
 *	[profile]:{
 *		basePath: "所有文件共有的基础路径",
 *		modules:{
 *			"[modulename]":{
 *				[type1]:["file1","file2"],
 *				"dependencies":["depend module name","",""]
 *			}
 *		}
 *	}
 * }
 * 调用方法：
 * 1.web调用
 * <script type="text/javascript" src="path/to/smartcomb.php?type=js&modules=m1,m2"></script>
 * <link rel="stylesheet/less" href="../../smartcomb.php?type=less&modules=m1,m2" type="text/css" charset="utf-8"/>
 * <link href="../../smartcomb.php?type=css&modules=m1,m2" type="text/css" charset="utf-8"/>
 * 2.命令行调用
 * php smartcomb.php -type js -modules m1,m2
 */
//禁用错误报告
error_reporting(0);
define('DS', DIRECTORY_SEPARATOR);
$params = $_REQUEST;
$modulesJSPath = "./";
if(!$params){
    foreach($argv as  $idx => $p){
        if ( preg_match("/^\-\w+/", $p) && isset($argv[$idx+1]) ){
            $paramname = preg_replace("/^\-/", "",$p);
            $params[$paramname] = $argv[$idx+1];
        }
    }
    //第二个参数是smartcomb.php 的路径，modules.js与smartcomb.php相同路径
    $modulesJSPath= dirname($argv[0]).DS;
}
function readFileStr($file_dir){
    $fp=fopen($file_dir,"r"); 
    $content=fread($fp,filesize($file_dir));//读文件 
    fclose($fp); 
    return $content;
}
//读取配置文件解析成json
$cfg = json_decode(readFileStr($modulesJSPath."modules.js"));
// $params["profile"] 读取配置文件中的第一层数据
$profile = isset($params["profile"]) ? $params["profile"] :"default";
$profileConfig = isset($cfg->$profile) ?  $cfg->$profile : $cfg->default;

//basePath 路径，拼接在文件路径前
$basePath = $modulesJSPath.(isset($profileConfig->basePath) ?  $profileConfig->basePath : "");
$defModules = isset($profileConfig->modules) ? $profileConfig->modules :array();
//类型。读取模块下面定义的类型 默认是js
$type = isset($params["type"])?$params["type"]:"js";

//需要拼合的文件名列表
$arrFiles= array();
$arrModules = explode(",",$params["modules"]);

function traversal ($module,$defModules,$basePath,$type,&$arrFiles){
    $moduleDef = $defModules -> $module;
    if(isset($moduleDef->$type)){//js
        $arrDepFiles = array_reverse($moduleDef->$type);
        foreach ($arrDepFiles as $idx => $defFile) {
            if (!in_array($defFile, $arrFiles)){
                array_unshift($arrFiles, $basePath.$defFile);
            }
        }
    }
    if(isset($moduleDef -> dependencies)){
        $arrDependencies =  $moduleDef -> dependencies;
        if(is_array($arrDependencies)){
            foreach ($arrDependencies as $idx => $dep) { 
                traversal($dep,$defModules,$basePath,$type,$arrFiles);
            }
        }
    }
}

foreach ($arrModules as $idx => $m) {
    $arrModuleFiles = array();
    traversal($m,$defModules,$basePath,$type,$arrModuleFiles);
    foreach ($arrModuleFiles as $idx => $f) {
        if(!in_array($f, $arrFiles)){
            array_push($arrFiles, $f);
        }
    }
}

$content = "/*! profile:".$profile." modules:".join(",",$arrModules)." -> files ".join(",",$arrFiles)." combined by smartcomb */\n";
//拼合文件

function replaceImgPath($matches){
    global $filePath; 
    //$curFileDir 当前css/less 文件的相对路径文件夹
    $curFileDir = preg_replace('/(\/)[^\/]+$/', '$1', $filePath);
    $imgPath = preg_replace("/^\.\//", "",$matches[2]); //图片在css中的定义路径 去掉  ./
    //将$imgPath中的 ^../ 替换掉，同时curFileDir 减少一级目录
    while(preg_match("/^\.\.\//", $imgPath)){
        $imgPath = preg_replace("/^\.\.\//","",$imgPath);
        $curFileDir = preg_replace("/[^\/]+\/$/", "", $curFileDir);
    }
    return $matches[1].$curFileDir.$imgPath.$matches[4];
}

$LastChangeTime = 1144055759; //文件最后的修改时间
foreach ($arrFiles as $idx => $filePath) {
    $realPath = realpath($filePath);
    if(!$realPath){
        $filecontent="/*!file ".$filePath." does not exists*/";
    }else{
        //取得文件列表中最近修改的文件作为拼合后文件的最后修改时间
        $fileLastChangeTime = filemtime($realPath);
        if($fileLastChangeTime  > $LastChangeTime){
            $LastChangeTime = $fileLastChangeTime;
        }
        $filecontent = readFileStr($realPath);
        if(preg_match("/\.css$|\.less$/", $filePath)){
            //拼合css,less中的相对路径
            //将 "images/abc.png|gif" (images/abc.png|gif) "images/abc.png" 中的相对路径替换成相对smartcomb.php路径
            $filecontent = preg_replace_callback("/(\(|\"|\')([^\)\"\']+\.(jpg|png|gif))(\)|\"|\')/im", "replaceImgPath", $filecontent);
        }
    }
    $content = $content.$filecontent;

}
if($_REQUEST){
    if( $type =="css" ){
        header('Content-type: text/css');
    }
    //  cache reference :http://www.jonasjohn.de/snippets/php/caching.htm
    $HashID = md5($content);
    $headers = apache_request_headers();
    header('ETag: ' . $HashID);
    $DoIDsMatch = (isset($headers['If-None-Match']) and ereg($HashID, $headers['If-None-Match']));
    $ExpireTime = 60*60*24*30; //default 30 days expire
    header('Cache-Control: max-age=' . $ExpireTime); // must-revalidate
    header('Expires: '.gmdate('D, d M Y H:i:s', time()+$ExpireTime).' GMT');
    header('Last-Modified: '.gmdate('D, d M Y H:i:s', $LastChangeTime).' GMT');
    $PageWasUpdated = !(isset($headers['If-Modified-Since']) and strtotime($headers['If-Modified-Since']) == $LastChangeTime);
    if(!$PageWasUpdated or $DoIDsMatch){
        header('HTTP/1.1 304 Not Modified');
        header('Connection: close');
    }else{
        header('HTTP/1.1 200 OK');
        echo $content;
    }

}else{
    echo $content;
}

?>
