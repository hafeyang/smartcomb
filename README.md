#smartcomb

smartcomb��һ����phpʵ�ֵ�webģ��ƴ����������������Ĵ���ƴ�Ϲ��ߣ��������ԣ�

* ����ƴ���������͵��ļ���������js�ļ���
* ���в������������Զ���������ƴ�ϣ�������ء�
* ֧�ֶ��������л�
* �Զ��޸�css,less�е�ͼƬ·�������赣��ƴ�Ϻ�cssͼƬ·������
* ֧��php�����е��ã�֧������ֱ������ƴ�Ͼ�̬�ļ�

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

'smartcomb.php'������

* `profile`:�������ͣ�Ĭ��Ϊdefault���øò��������л�`profile`
* `type`:�ļ����ͣ�ģ�������е����ͣ��������Զ�����κ��ļ�����
* `modules`:��Ҫƴ�ϵ�ģ�飬���ģ�������,�ָ�

##��������ʹ��

����ֱ��ʹ�� php����磺

    php smartcomb.php -profile default -type js -modules pageA

����ֱ���ڱ�׼��������ƴ�Ͻ��������ֱ�������ļ���

    php smartcomb.php -profile default -type js -modules pageA > pageA-dep.js

=============

Thanks,�κ�����,��������ϵ��)