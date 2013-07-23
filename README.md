DiscuzX3-Database-Structure
===========================

DiscuzX3的数据库结构

从discuz网上的数据字典页面和ucenter的数据字典页面分析抓取出来的数据库结构

discuz数据字典：http://faq.comsenz.com/library/database/x3/x3_index.htm

ucenter数据字典：http://faq.comsenz.com/library/database/uc/uc_index.htm

最终数据以json储存，要用的的时候解析一下就可以

discuzdb.json：discuz的数据库结构(带_formated的是用编辑器格式化过的)

ucenterdb.json：ucenter的数据结构(带_formated的是用编辑器格式化过的)

我的实际需求是要找到所有跟用户名有关的字段，并把它们的类型改成 varchar(255)，因为原本的用户名相关的字段不支持长用户名.

生成程序：username_related.php

生成结果：username_related.sql 和 username_related.html
