<?php
/*
 * buildcomb.php用于将代码中使用了smartcomb.php?type=xxx&modules=aa,bb 的地方替换成一个静态文件的引用
 * 使用方法： php buildcomb.php -target the/path -ext html,php -exclude xxx.html
 *  target: 目标文件夹，默认是当前文件夹 需要确保文件夹中有且只有一个smartcomb.php文件
 *  ext: 需要替换的文件后缀，默认html,多个以,分隔
 *  exclude: 需要排除的文件,只要文件路径中含有该字符串就会排除，多个以,分隔
 *
 *  删除生成的文件 ls  | grep '[a-z0-9]\{32,32\}.\(js\|css\)' | xargs rm
 */
$params= array();
foreach($argv as  $idx => $p){
    if ( preg_match("/^\-\w+/", $p) && isset($argv[$idx+1]) ){
        $paramname = preg_replace("/^\-/", "",$p);
        $params[$paramname] = $argv[$idx+1];
    }
}
$targetDir = isset($params["target"])?$params["target"]:".";//默认目录是当前目录
$ext = isset($params["ext"])?$params["ext"]:"html";//默认值替换.html文件
$ext  = "\.".preg_replace("/\,/","$|\\.",$ext)."$"; //转换成正则表达式
$arrExclude = explode(",",(isset($params["exclude"])?$params["exclude"]:"")."smartcomb.php,combbuild.php,smartcomb-config.php");//默认得排除smartcomb三个文件
function isExcluded($filePath){
    global $arrExclude;
    $result = false;
    foreach($arrExclude as $idx => $excludeItem){
        if(strpos($filePath,$excludeItem)!==false){
            $result = true;
            break;
        }
    }
    return $result;
}


//找到smartcomb.php所在文件夹
$smartDir = "";

/**
 * find files matching a pattern
 * using PHP "glob" function and recursion
 *
 * @return array containing all pattern-matched files
 *
 * @param string $dir     - directory to start with
 * @param string $pattern - pattern to glob for
 */
function find($dir, $pattern){
    // escape any character in a string that might be used to trick
    // a shell command into executing arbitrary commands
    $dir = escapeshellcmd($dir);
    // get a list of all matching files in the current directory
    $files = glob("$dir/$pattern");
    // find a list of all directories in the current directory
    // directories beginning with a dot are also included
    foreach (glob("$dir/{.[^.]*,*}", GLOB_BRACE|GLOB_ONLYDIR) as $sub_dir){
        $arr   = find($sub_dir, $pattern);  // resursive call
        $files = array_merge($files, $arr); // merge array with files from subdirectory
    }
    // return all found files
    return $files;
}
$arrFound = find($targetDir,"smartcomb.php");
if(count($arrFound)==0){
    echo "没有找到smartcomb.php文件\n";
    return ;
}
if(count($arrFound)>1){
    echo "找到多个smartcomb.php文件\n";
    return ;
}
echo "找到唯一的smartcomb.php文件\n";
define('DS', DIRECTORY_SEPARATOR);
$staticFilesDir = preg_replace("/\/\//","/",dirname($arrFound[0]))."/"; //静态文件生成目录，与smartcomb.php同一个目录

//找到所有引用smartcomb.php的文件
//一个递归调用的文件夹操作
function buildDir($dir){
    global $filesToBuild;
    global $ext;
    $files = preg_grep('/^([^.])/', scandir($dir));//过滤隐藏文件
    foreach($files as $idx => $item){
        if(!is_dir($dir.DS.$item)) { //file
            $filePath = $dir.DS.$item;
            if(preg_match("/".$ext."/", $filePath) && (!isExcluded($filePath))){ //只替换ext文件类型
                buildFile($filePath);
            }
        }else{
            buildDir($dir.DS.$item);
        }
    }
}


buildDir($targetDir);
function readFileStr($file_dir){
    $fp=fopen($file_dir,"r"); 
    $content=fread($fp,filesize($file_dir));//读文件 
    fclose($fp); 
    return $content;
}

$buildCount = 0 ;
function replaceCombRef($match){
    global $staticFilesDir;
    global $buildCount;
    $buildCount++;
    $tmpFile = "_tmp.tmp";
    if(file_exists($tmpFile)){
        unlink($tmpFile);
    }
    preg_match("/([^\"\']+)smartcomb\.php\?/",$match[2],$refPathMatch,PREG_OFFSET_CAPTURE);
    $refPath = $refPathMatch[1][0];
    $cmd =  preg_replace("/[^\"\']+smartcomb\.php\?/", "smartcomb.php ",$match[2]);
    preg_match("/type\=(\w+)/",$cmd,$typematch,PREG_OFFSET_CAPTURE);
    $type = $typematch[1][0];
    $cmd = "php ".$staticFilesDir . preg_replace("/\&?(\w+)=/", " -$1 ",$cmd). " > ".$tmpFile;
    exec($cmd);
    $result = readFileStr($tmpFile);
    $md5 = md5($result);
    file_put_contents($staticFilesDir.$md5.".".$type, $result);
    if(file_exists($tmpFile)){
        unlink($tmpFile);
    }
    echo "替换引用 ".$match[2]." ,生成文件".$staticFilesDir.$md5.".".$type."\n";

    return $match[1].$refPath.$md5.".".$type.$match[3];
    //return $match[1].$match[2].$match[3];
}
function buildFile($filePath){
    global $buildCount ; 
    $buildCount = 0 ; 
    echo "\n替换文件".$filePath."\n";
    $fileContent = readFileStr($filePath);
    if(preg_match("/(\"|\')([^\"\']+smartcomb\.php[^\"\']+)(|\"|\')/im",$fileContent)){
        $fileContent = preg_replace_callback("/(\"|\')([^\"\']+smartcomb\.php[^\"\']+)(\"|\')/im", "replaceCombRef", $fileContent);
    }
    echo "文件".$filePath."替换".$buildCount."处\n";
    file_put_contents($filePath, $fileContent);
}

?>
