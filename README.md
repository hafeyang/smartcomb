#smartcomb

smartcomb是一个用php实现的web模块拼合器，相对于其他的代码拼合工具，如下特性：

* 可以拼合任意类型的文件，不限于js文件。
* 集中并声明依赖，自动分析依赖拼合，按需加载。
* 支持多种配置切换
* 自动修改css,less中的图片路径，无需担心拼合后css图片路径出错
* 支持php命令行调用，支持命令直接生成拼合静态文件

##模块声明配置

smartcomb是由`smartcomb.php` 和`modules.js`构成，`modules.js`是一个json文件，必须是严格的json格式，key需要加上双引号，用于模块声明。格式为：

    {
    	[profile]:{
    		"basePath": "所有文件共有的基础路径",
    		"modules":{
    			"[modulename]":{
    				[type1]:["file1","file2"],
    				"dependencies":["depend module name","",""]
    			}
    		}
    	}
     }


下面是一个demo:

    {
        "default":{
            "basePath":"modules/",
            "modules":{
                "base":{
                    "js":["base/base.js","base/common.js"],
                    "css":["base/reset.css"],
                    "less":["base/reset.less"]
                 },
                 "pageA":{
                     "js":["pageA/pageA-util.js","pageA/pageA.js"],
                     "css":["pageA/pageA.css"],
                     "dependencies":["base"]
                 }
            }
        }
    }

##web中使用

上面的demo声明了profile 为default的模块配置。声明两个模块base和pageA,pageA依赖于base模块。其中base模块包括两个js文件: modules/base/base.js，该路径是相对于`smartcomb.php`的路径，文件类型可以任意定义，使用时指定类型即可。

如上配置文件，我们可以在页面中如下引用：

    <script type="text/javascript" src="path/to/smartcomb.php?type=js&modules=pageA"></script>
    <link href="path/to/smartcomb.php?type=css&modules=pageA" type="text/css" charset="utf-8"/>

pageA依赖于base模块。smartcomb自动拼合依赖的的文件。

'smartcomb.php'参数：

* `profile`:配置类型，默认为default。用该参数可以切换`profile`
* `type`:文件类型，模块声明中的类型，可以是自定义的任何文件类型
* `modules`:需要拼合的模块，多个模块可以用,分割

##命令行中使用

可以直接使用 php命令，如：

    php smartcomb.php -profile default -type js -modules pageA

命令直接在标准输出中输出拼合结果，可以直接生成文件。

    php smartcomb.php -profile default -type js -modules pageA > pageA-dep.js

=============

Thanks,任何问题,请与我联系：)