#smartcomb

===============

smartcomb��һ����phpʵ�ֵ�webģ��ƴ����������������Ĵ���ƴ�Ϲ��ߣ������������ԣ�

* ����ƴ���������͵��ļ���������js�ļ���
* ���в������������Զ���������ƴ�ϣ�������ء�
* ֧�ֶ��������л�
* �Զ��޸�css,less�е�ͼƬ·�������赣��ƴ�Ϻ�cssͼƬ·������
* ֧��php�����е��ã�֧������ֱ������ƴ�Ͼ�̬�ļ����ҹ��̿���
* ����֧��
* ֧��ֱ�����ɾ�̬�ļ������µ���

##github��ַ

    git clone https://github.com/hafeyang/smartcomb.git

##Smartcomb�ṹ��ͼ

<img src="https://raw.github.com/hafeyang/smartcomb/master/smartcomb.png" width="800" />

[������Բ鿴��ͼ](https://raw.github.com/hafeyang/smartcomb/master/smartcomb.png)

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


##���ɾ�̬�ļ�����������


���Խ������е�`smartcomb.php`���ã�

    <script type="text/javascript" src="path/to/smartcomb.php?type=js&modules=pageA"></script>
    <link href="path/to/smartcomb.php?type=css&modules=pageA" type="text/css" charset="utf-8"/>

ֱ���滻Ϊ

    <script type="text/javascript" src="path/to/[md5].js"></script>
    <link href="path/to/[md5].css" type="text/css" charset="utf-8"/>

�滻����Ϊ��

    php path/to/combbuild.php  -target=target/dir -prefix=html,php -exclude aboutus.html,contact.html

���������smartcomb.php����Ŀ¼������ƴ���ļ�����MD5ֵ�������ļ���ͬʱ����smartcomb.php�����á�

�������壺

* target ��Ҫ�滻��Ŀ��Ŀ¼��ע���Ŀ¼������ֻ��Ψһsmartcomb.php �ļ�����Ϊ���ɵ��ļ���smartcomb.phpͬĿ¼��Ĭ��Ϊ��ǰĿ¼
* ext ��Ҫ�滻���ļ�����׺�������׺��,�ָ���Ĭ��Ϊhtml
* exclude ��Ҫ�ų����ļ�������ļ�����,�ָ�,ֻҪ�ļ�����·���а������ļ��������ų���Ĭ�ϻ��ų�smartcomb������ļ�


##��̬�ļ���ԭ��smartcomb.php����


���Խ������е�`[md5].js [md5].css`��ԭΪ`smartcomb.php`���ã�

    <script type="text/javascript" src="path/to/[md5].js"></script>
    <link href="path/to/[md5].css" type="text/css" charset="utf-8"/>


��ԭΪ

    <script type="text/javascript" src="path/to/smartcomb.php?type=js&modules=pageA"></script>
    <link href="path/to/smartcomb.php?type=css&modules=pageA" type="text/css" charset="utf-8"/>




�滻����Ϊ��

    php path/to/combdev.php  -target=target/dir -prefix=html,php -exclude aboutus.html,contact.html


�������壺

* target ��Ҫ�滻��Ŀ��Ŀ¼��ע���Ŀ¼������ֻ��Ψһsmartcomb.php �ļ�����Ϊ���ɵ��ļ���smartcomb.phpͬĿ¼��Ĭ��Ϊ��ǰĿ¼
* ext ��Ҫ�滻���ļ�����׺�������׺��,�ָ���Ĭ��Ϊhtml
* exclude ��Ҫ�ų����ļ�������ļ�����,�ָ�,ֻҪ�ļ�����·���а������ļ��������ų���Ĭ�ϻ��ų�smartcomb������ļ�
* 

=============

Thanks,�κ�����,��������ϵ��)