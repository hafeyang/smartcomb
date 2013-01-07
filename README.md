#smartcomb

===============

smartcomb��һ����phpʵ�ֵ�webģ��ƴ����������������Ĵ���ƴ�Ϲ��ߣ������������ԣ�

* ����ƴ���������͵��ļ���������js�ļ���
* ���в������������Զ���������ƴ�ϣ�������ء�
* ֧�ֶ��������л�
* �Զ��޸�css,less�е�ͼƬ·�������赣��ƴ�Ϻ�cssͼƬ·������
* ֧��php�����е��ã�֧������ֱ������ƴ�Ͼ�̬�ļ�
* ����֧��

##github��ַ

    git clone https://github.com/hafeyang/smartcomb.git

##ģ����������

smartcomb����`smartcomb.php` ��`modules.js`���ɣ�`modules.js`��һ��json�ļ����������ϸ��json��ʽ��key��Ҫ����˫���ţ�����ģ����������ʽΪ��

    {
    	[profile]:{
    		"basePath": "�����ļ����еĻ���·��",
    		"modules":{
    			"[modulename]":{
    				[type1]:["file1","file2"],
    				"dependencies":["depend module name","",""]
    			}
    		}
    	}
     }


������һ��demo:

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

##web��ʹ��

�����demo������profile Ϊdefault��ģ�����á���������ģ��base��pageA,pageA������baseģ�顣����baseģ���������js�ļ�: modules/base/base.js����·���������`smartcomb.php`��·�����ļ����Ϳ������ⶨ�壬ʹ��ʱָ�����ͼ��ɡ�

���������ļ������ǿ�����ҳ�����������ã�

    <script type="text/javascript" src="path/to/smartcomb.php?type=js&modules=pageA"></script>
    <link href="path/to/smartcomb.php?type=css&modules=pageA" type="text/css" charset="utf-8"/>

pageA������baseģ�顣smartcomb�Զ�ƴ�������ĵ��ļ���

`smartcomb.php`���ò�����

* `profile`:�������ͣ�Ĭ��Ϊdefault���øò��������л�`profile`
* `type`:�ļ����ͣ�ģ�������е����ͣ��������Զ�����κ��ļ����ͣ�Ĭ����js
* `modules`:��Ҫƴ�ϵ�ģ�飬���ģ�������,�ָ�

##��������ʹ��

����ֱ��ʹ�� php����磺

    php smartcomb.php -profile default -type js -modules pageA

����ֱ���ڱ�׼��������ƴ�Ͻ��������ֱ�������ļ���

    php smartcomb.php -profile default -type js -modules pageA > pageA-dep.js

������web���÷�ʽһ��


##css������ͼƬ·���޸Ĺ���

ֻ�޸����·����ͼƬ���ļ���׺����png,gif,png

    background-image: url("images/q4.jpg"); /*���·��������*/
    background-image: url('../images/q3.jpg'); /*���·��������*/
    background-image: url(./images/q3.jpg); /*���·��������*/
    background-image: url(/abc/images/q2.jpg); /*����·��������*/
    background-image: url(http://abc.com/a.png); /*����·��������*/


##�������

���Բμ�phpʵ�ֻ��� [http://www.jonasjohn.de/snippets/php/caching.htm](http://www.jonasjohn.de/snippets/php/caching.htm)

###Etag����

��ƴ�ϵ��ļ�����md5ֵ����ΪETag��ֵд�뵽 http header�������ļ��޸�ETagҲ��֮���£�������˻���ʧЧ

###ͨ��Last-Modified

��http header�е� Last-Modified ���ó�ƴ���ļ�������޸ĵ��ļ�ʱ�䣬ͨ����`Last-Modified` `If-Modified-Since`���档

###Ĭ�ϵĻ���ʱ��

���ETag,��Last-Modified �����ı䣬����30��ʧЧ��ͨ��http header �е� `Cache-Control` ,`Expires`ʵ��

=============

Thanks,�κ�����,��������ϵ��)