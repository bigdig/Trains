/*
SQLyog Enterprise Trial - MySQL GUI v7.11 
MySQL - 5.7.21 : Database - train
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`train` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `train`;

/*Table structure for table `admin_settings` */

DROP TABLE IF EXISTS `admin_settings`;

CREATE TABLE `admin_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '名称',
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '代码',
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `describe` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '描述',
  `tag` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '分类标签',
  `type` enum('text','textarea','select','date','combodate','datetime','typeahead','checklist','select2','address','wysihtml5') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'text' COMMENT '类型',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `admin_settings` */

insert  into `admin_settings`(`id`,`name`,`code`,`value`,`describe`,`tag`,`type`,`created_at`,`updated_at`) values (1,'后台分页','admin_pages_length','20','页面通用分页长度','admin','text','2018-07-04 14:17:12','2018-07-04 14:17:12'),(2,'上传图片原图最大宽度','upload_picture_mix','1280','上传图片原图最大宽度','admin','text','2018-07-04 14:17:12','2018-07-04 14:17:12'),(3,'上传图片缩略图高度','upload_picture_thumbnail_small','180','上传图片缩略图高度，填0不裁剪','admin','text','2018-07-04 14:17:12','2018-07-04 14:17:12'),(4,'上传图片中等高度','upload_picture_thumbnail_middle','600','上传图片中等大小高度，填0不裁剪','admin','text','2018-07-04 14:17:12','2018-07-04 14:17:12'),(5,'上传图片最大高度（0：不限制）','upload_picture_thumbnail_max','0','上传图片最大高度，填0不裁剪','admin','text','2018-07-04 14:17:12','2018-07-04 14:17:12');

/*Table structure for table `article_cate` */

DROP TABLE IF EXISTS `article_cate`;

CREATE TABLE `article_cate` (
  `article_id` int(10) unsigned NOT NULL,
  `article_category_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`article_id`,`article_category_id`),
  KEY `article_cate_article_category_id_foreign` (`article_category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `article_cate` */

insert  into `article_cate`(`article_id`,`article_category_id`) values (1,1);

/*Table structure for table `article_categories` */

DROP TABLE IF EXISTS `article_categories`;

CREATE TABLE `article_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '分类名称',
  `describe` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '描述',
  `display_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '分类英文别名',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父类ID',
  `order` int(11) NOT NULL DEFAULT '1' COMMENT '排序，asc',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `article_categories` */

insert  into `article_categories`(`id`,`name`,`describe`,`display_name`,`parent_id`,`order`,`created_at`,`updated_at`) values (1,'PHP','测试测试','php',0,1,'2018-07-08 10:44:34','2018-07-08 10:44:34');

/*Table structure for table `article_tag` */

DROP TABLE IF EXISTS `article_tag`;

CREATE TABLE `article_tag` (
  `article_id` int(10) unsigned NOT NULL,
  `article_tag_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`article_id`,`article_tag_id`),
  KEY `article_tag_article_tag_id_foreign` (`article_tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `article_tag` */

/*Table structure for table `article_tags` */

DROP TABLE IF EXISTS `article_tags`;

CREATE TABLE `article_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '标签名',
  `display_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '标签英文别名',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `article_tags` */

insert  into `article_tags`(`id`,`name`,`display_name`,`created_at`,`updated_at`) values (1,'PHP','php','2018-07-08 12:38:18','2018-07-08 12:38:18');

/*Table structure for table `articles` */

DROP TABLE IF EXISTS `articles`;

CREATE TABLE `articles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '文章标题',
  `abstract` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '文章摘要',
  `content` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '文章内容',
  `content_md` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '文章内容MarkDown',
  `article_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '文章特色图片',
  `article_status` tinyint(4) NOT NULL COMMENT '文章状态，1：公共，2：私有',
  `comment_status` tinyint(4) NOT NULL COMMENT '评论状态',
  `display_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '文章英文别名',
  `comment_count` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '评论总数',
  `author` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '作者',
  `user_id` int(11) NOT NULL COMMENT '作者ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `articles` */

insert  into `articles`(`id`,`title`,`abstract`,`content`,`content_md`,`article_image`,`article_status`,`comment_status`,`display_name`,`comment_count`,`author`,`user_id`,`created_at`,`updated_at`) values (1,'测试文章','测试文章，测试','<p>是是是</p>','是是是','upload/images/201807/08/1/20180708104536_yj_small.jpg',1,1,NULL,NULL,'管理员',1,'2018-07-08 10:45:36','2018-07-08 10:45:36');

/*Table structure for table `cache` */

DROP TABLE IF EXISTS `cache`;

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  UNIQUE KEY `cache_key_unique` (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `cache` */

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8_unicode_ci NOT NULL,
  `queue` text COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `failed_jobs` */

/*Table structure for table `images` */

DROP TABLE IF EXISTS `images`;

CREATE TABLE `images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `user_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名',
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '文件路径',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '文件名',
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户命名文件名',
  `extension` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '文件尾缀',
  `year_month` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '上传年月',
  `size` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '文件大小',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `images` */

/*Table structure for table `jobs` */

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_reserved_at_index` (`queue`,`reserved_at`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `jobs` */

/*Table structure for table `menus` */

DROP TABLE IF EXISTS `menus`;

CREATE TABLE `menus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父级ID',
  `order` int(11) NOT NULL DEFAULT '0' COMMENT '排序，asc',
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '名称',
  `icon` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '字体图标',
  `uri` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '路由名',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `menus` */

insert  into `menus`(`id`,`parent_id`,`order`,`name`,`icon`,`uri`,`created_at`,`updated_at`) values (1,0,1,'仪表盘','icon-speedometer','parent.admin','2018-07-04 14:17:12','2018-07-04 14:17:12'),(2,1,1,'系统总览','icon-bar-chart','admin.indexTrain','2018-07-04 14:17:12','2018-12-03 16:05:21'),(3,1,2,'图标参考','icon-list','icon.index','2018-07-04 14:17:12','2018-07-04 14:17:12'),(4,0,4,'用户管理','icon-users','parent.user','2018-07-04 14:17:12','2018-10-18 09:41:36'),(5,4,1,'用户列表','icon-user','user.index','2018-07-04 14:17:12','2018-07-04 14:17:12'),(6,4,2,'用户组管理','icon-social-dropbox','role.index','2018-07-04 14:17:12','2018-07-04 14:17:12'),(7,4,3,'用户组权限','icon-user-following','permissions.index','2018-07-04 14:17:12','2018-07-04 14:17:12'),(8,0,6,'设置','icon-settings','parent.setting','2018-07-04 14:17:12','2018-07-06 09:24:35'),(9,8,1,'编辑菜单','icon-list','menutable.index','2018-07-04 14:17:12','2018-07-04 14:17:12'),(10,0,7,'系统日志','icon-note','parent.log','2018-07-04 14:17:12','2018-07-06 09:24:35'),(11,10,1,'仪表盘','icon-speedometer','log.index','2018-07-04 14:17:12','2018-07-04 14:17:12'),(12,0,5,'多媒体','icon-layers','picture','2018-07-04 14:17:12','2018-07-06 09:24:35'),(13,12,1,'图片管理','icon-picture','picture.index','2018-07-04 14:17:12','2018-07-04 14:17:12'),(14,8,2,'系统设置','icon-settings','setting.index','2018-07-04 14:17:12','2018-07-04 14:17:12'),(26,24,1,'课程管理','icon-heart','course.index','2018-10-18 11:29:34','2018-11-16 10:56:46'),(25,24,2,'职称管理','icon-heart','profess.index','2018-10-18 09:42:14','2018-11-16 10:56:46'),(19,0,2,'培训信息','icon-grid','trains','2018-07-06 09:24:09','2018-07-06 09:30:42'),(20,19,1,'培训通知','icon-doc','trains.index','2018-07-06 09:32:11','2018-10-18 09:41:36'),(21,19,2,'报名列表','icon-doc','entry.index','2018-07-08 15:05:43','2018-10-18 09:41:36'),(22,19,3,'学员名单','icon-doc','students.index','2018-07-08 16:23:41','2018-10-18 09:41:36'),(24,0,3,'培训设置','icon-graduation','teach','2018-10-18 09:41:28','2018-10-18 09:41:36'),(27,24,3,'教师管理','icon-heart','teach.index','2018-10-18 18:27:55','2018-11-16 10:56:46'),(28,19,4,'培训报表','icon-doc','report.index','2018-11-16 10:53:04','2018-11-16 13:48:39'),(29,1,0,'数据统计','icon-bar-chart','chart.index','2018-12-03 15:47:08','2018-12-03 15:47:08');

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2016_11_07_090436_create_table_menus',1),(4,'2016_11_11_162030_entrust_setup_tables',1),(5,'2016_11_24_092220_create_cache_table',1),(6,'2016_12_14_092019_add_route_name_permissions',1),(7,'2017_03_09_210409_create_images_table',1),(8,'2017_03_13_195756_create_admin_settings_table',1),(9,'2017_03_23_213010_create_articles_table',1),(10,'2017_03_23_220242_create_article_categories_table',1),(11,'2017_03_23_220243_create_article_cate_table',1),(12,'2017_04_12_172703_create_article_tags_table',1),(13,'2017_04_12_174008_create_article_tag_table',1),(14,'2018_07_06_174449_create_trains_table',2),(15,'2018_07_08_151026_create_entries_table',2),(16,'2018_07_08_162518_create_students_table',2),(17,'2018_07_09_132241_create_nursery_students_table',2),(18,'2018_07_11_103439_create_flows_table',2),(19,'2018_07_13_133759_create_jobs_table',2),(20,'2018_07_13_133819_create_failed_jobs_table',2);

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `password_resets` */

/*Table structure for table `permission_role` */

DROP TABLE IF EXISTS `permission_role`;

CREATE TABLE `permission_role` (
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `permission_role_role_id_foreign` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `permission_role` */

insert  into `permission_role`(`permission_id`,`role_id`) values (1,1),(2,1),(2,2),(2,4),(2,5),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),(13,1),(14,1),(15,1),(16,1),(17,1),(18,1),(19,1),(20,1),(21,1),(22,1),(23,1),(24,1),(25,1),(26,1),(27,1),(28,1),(29,1),(30,1),(31,1),(32,1),(33,1),(34,1),(35,1),(36,1),(37,1),(38,1),(39,1),(40,1),(40,2),(40,3),(40,4),(40,5),(41,1),(42,1),(43,1),(43,3),(43,4),(43,5),(44,1),(44,4),(44,5),(45,1),(45,4),(45,5),(46,1),(46,4),(46,5),(47,1),(48,1),(48,4),(48,5),(49,1),(49,4),(49,5),(50,1),(50,4),(50,5),(51,1);

/*Table structure for table `permissions` */

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `uri` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '路由名',
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `permissions` */

insert  into `permissions`(`id`,`name`,`display_name`,`description`,`created_at`,`updated_at`,`uri`) values (1,'system.login','登录后台权限','登录后台权限','2018-07-04 14:17:11','2018-07-04 14:17:11','login'),(2,'parent.admin','仪表盘','仪表盘','2018-07-04 14:17:11','2018-08-02 10:06:12','parent.admin'),(3,'menu.list','显示菜单列表','显示菜单列表','2018-07-04 14:17:11','2018-07-04 14:17:11','menutable.index'),(4,'menu.create','创建菜单','创建菜单','2018-07-04 14:17:11','2018-07-04 14:17:11','menutable.create'),(5,'menu.destroy','删除菜单','删除菜单','2018-07-04 14:17:11','2018-07-04 14:17:11','menutable.destroy'),(6,'menu.edit','修改菜单','修改菜单','2018-07-04 14:17:11','2018-07-04 14:17:11','menutable.edit'),(7,'menu.show','查看菜单','查看菜单','2018-07-04 14:17:11','2018-07-04 14:17:11','menutable.show'),(8,'menu.store','菜单创建POST','菜单创建POST','2018-07-04 14:17:11','2018-07-04 14:17:11','menu.store'),(9,'menu.update','菜单更新PUT','菜单更新PUT','2018-07-04 14:17:11','2018-07-04 14:17:11','request'),(10,'menu.saveMenuOrder','保存菜单排序','保存菜单排序','2018-07-04 14:17:11','2018-07-04 14:17:11','menutable.saveMenuOrder'),(11,'role.list','显示角色列表','显示角色列表','2018-07-04 14:17:11','2018-07-04 14:17:11','role.index'),(12,'role.create','创建角色','创建角色','2018-07-04 14:17:11','2018-07-04 14:17:11','role.create'),(13,'role.destroy','删除角色','删除角色','2018-07-04 14:17:11','2018-07-04 14:17:11','role.destroy'),(14,'role.edit','修改角色','修改角色','2018-07-04 14:17:11','2018-07-04 14:17:11','role.edit'),(15,'role.show','查看角色权限','查看角色权限','2018-07-04 14:17:11','2018-07-04 14:17:11','role.show'),(16,'role.store','用户角色创建POST','用户角色创建POST','2018-07-04 14:17:11','2018-07-04 14:17:11','request'),(17,'role.update','用户角色更新PUT','用户角色更新PUT','2018-07-04 14:17:11','2018-07-04 14:17:11','request'),(18,'permission.list','显示权限列表','显示权限列表','2018-07-04 14:17:11','2018-07-04 14:17:11','permissions.index'),(19,'permission.create','创建权限','创建权限','2018-07-04 14:17:11','2018-07-04 14:17:11','permissions.create'),(20,'permission.destroy','删除权限','删除权限','2018-07-04 14:17:11','2018-07-04 14:17:11','permissions.destroy'),(21,'permission.edit','修改权限','修改权限','2018-07-04 14:17:11','2018-07-04 14:17:11','permissions.edit'),(22,'permission.store','用户权限创建POST','用户权限创建POST','2018-07-04 14:17:11','2018-07-04 14:17:11','request'),(23,'permission.update','用户权限更新PUT','用户权限更新PUT','2018-07-04 14:17:11','2018-07-04 14:17:11','request'),(24,'user.list','显示用户列表','显示用户列表','2018-07-04 14:17:11','2018-07-04 14:17:11','user.index'),(25,'user.create','创建用户','创建用户','2018-07-04 14:17:11','2018-07-04 14:17:11','user.create'),(26,'user.edit','修改用户','修改用户','2018-07-04 14:17:11','2018-07-04 14:17:11','user.edit'),(27,'user.destroy','删除用户','删除用户','2018-07-04 14:17:11','2018-07-04 14:17:11','user.destroy'),(28,'user.show','查看用户信息','查看用户信息','2018-07-04 14:17:11','2018-07-04 14:17:11','user.show'),(29,'user.store','用户创建POST','用户创建POST','2018-07-04 14:17:11','2018-07-04 14:17:11','request'),(30,'user.update','用户更新PUT','用户更新PUT','2018-07-04 14:17:11','2018-07-04 14:17:11','request'),(31,'log.dash','显示日志仪表盘','显示日志仪表盘','2018-07-04 14:17:11','2018-07-04 14:17:11','log.index'),(32,'log.filter','查看日志','查看日志','2018-07-04 14:17:11','2018-07-04 14:17:11','log.filter'),(33,'log.download','日志下载','日志下载','2018-07-04 14:17:11','2018-07-04 14:17:11','log.download'),(34,'log.destroy','日志删除','日志删除','2018-07-04 14:17:11','2018-07-04 14:17:11','log.destroy'),(35,'parent.user','用户管理','用户管理','2018-07-04 14:17:11','2018-07-04 14:17:11','parent.user'),(36,'parent.setting','设置','设置','2018-07-04 14:17:11','2018-07-04 14:17:11','parent.setting'),(37,'parent.log','系统日志','系统日志','2018-07-04 14:17:11','2018-07-04 14:17:11','parent.log'),(38,'parent.picture','多媒体','多媒体','2018-08-02 09:39:03','2018-08-02 09:46:39','picture.picture'),(39,'picture.index','图片管理','图片管理','2018-08-02 09:39:47','2018-08-02 09:40:03','picture.index'),(40,'admin.index','后台首页','后台首页','2018-08-02 10:05:00','2018-08-02 10:05:00','admin.index'),(41,'icon.index','图标参考','图标参考','2018-08-02 10:06:40','2018-08-02 10:06:40','icon.index'),(42,'parent.train','培训信息','培训信息','2018-08-21 17:21:23','2018-08-21 17:21:23','parent.train'),(43,'train.refund','退款','不是每个人都能操作退款的','2018-08-21 17:24:43','2018-08-21 17:24:43','entry.refund'),(44,'train.index','培训列表','培训列表','2018-11-12 13:22:57','2018-11-12 13:22:57','trains.index'),(45,'train.entry','报名列表','报名列表','2018-11-12 13:24:55','2018-11-12 13:26:38','entry.index'),(46,'train.students','学员列表','学员列表','2018-11-12 13:26:03','2018-11-12 13:27:06','students.index'),(47,'parent.train_setting','培训设置','培训设置','2018-11-12 13:31:06','2018-11-12 13:31:06','parent.train_setting'),(48,'train_setting.profess','职称管理','职称管理','2018-11-12 13:32:30','2018-11-12 13:32:30','profess.index'),(49,'train_setting.course','课程设置','课程设置','2018-11-12 13:33:30','2018-11-12 13:33:30','course.index'),(50,'train_setting.teach','教师管理','教师管理','2018-11-12 13:34:31','2018-11-12 13:34:31','teach.index'),(51,'train.report','培训报表','培训报表','2018-11-16 10:55:26','2018-11-16 10:55:26','reprot.index');

/*Table structure for table `role_user` */

DROP TABLE IF EXISTS `role_user`;

CREATE TABLE `role_user` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_user_role_id_foreign` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `role_user` */

insert  into `role_user`(`user_id`,`role_id`) values (1,1),(3,2);

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `roles` */

insert  into `roles`(`id`,`name`,`display_name`,`description`,`created_at`,`updated_at`) values (1,'admin','管理员','超级管理员','2018-07-04 14:17:11','2018-07-04 14:17:11'),(2,'user','用户','普通用户','2018-07-04 14:17:12','2018-07-04 14:17:12'),(3,'tuikuan','退款','有退款权限','2018-08-21 18:00:00','2018-08-21 18:00:00'),(4,'qnursery','亲子园用户组','亲子园用户组','2018-11-12 13:43:52','2018-11-12 13:43:52'),(5,'ynursery','幼儿园用户组','幼儿园用户组','2018-11-12 13:44:36','2018-11-12 13:44:58');

/*Table structure for table `t_apply_students` */

DROP TABLE IF EXISTS `t_apply_students`;

CREATE TABLE `t_apply_students` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `apply_user` int(10) unsigned NOT NULL COMMENT '报名人id',
  `student_id` int(50) unsigned NOT NULL COMMENT '关联nursery_students',
  `contract_no` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '园所合同号',
  `train_id` int(10) unsigned NOT NULL COMMENT '关联trains',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `t_apply_students` */

/*Table structure for table `t_nursery_students` */

DROP TABLE IF EXISTS `t_nursery_students`;

CREATE TABLE `t_nursery_students` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `apply_user` int(10) unsigned NOT NULL COMMENT '报名人id',
  `contract_no` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '园所合同号',
  `student_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1亲子园 2幼儿园',
  `student_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '学员姓名',
  `student_sex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1男2女0未知',
  `student_phone` char(11) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '学员性别',
  `student_position` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '学员岗位',
  `school` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '毕业院校',
  `education` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '学历',
  `profession` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '专业',
  `idcard` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '身份证',
  `card_z` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '身份证正面',
  `card_f` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '身份证反面',
  `health_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '健康证',
  `health_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '健康证',
  `health_3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '健康证',
  `labor_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '劳动合同首页',
  `labor_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '劳动合同尾页',
  `learnership` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '培训协议',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `t_nursery_students` */

/*Table structure for table `t_order_students` */

DROP TABLE IF EXISTS `t_order_students`;

CREATE TABLE `t_order_students` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL COMMENT '关联train_order',
  `student_id` int(10) unsigned NOT NULL COMMENT '关联nursery_students',
  `fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '费用',
  `is_paid` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1支付0未支付',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '-1退训 0 未审核 1 审核通过未签到 2 审核未通过 3已签到 4已完成',
  `check_recoder` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '操作人',
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注、退训原因',
  `check_time` timestamp NULL DEFAULT NULL COMMENT '审核时间',
  `sign_time` timestamp NULL DEFAULT NULL COMMENT '签到时间',
  `cert_time` date DEFAULT NULL COMMENT '发放证书时间',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `t_order_students` */

/*Table structure for table `t_pay_info` */

DROP TABLE IF EXISTS `t_pay_info`;

CREATE TABLE `t_pay_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '订单号',
  `trade_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '微信支付订单号',
  `total_fee` decimal(10,2) DEFAULT NULL COMMENT '支付金额',
  `pay_time` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '支付时间',
  `openid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '用户标识',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `NewIndex1` (`order_sn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `t_pay_info` */

/*Table structure for table `t_phone_code` */

DROP TABLE IF EXISTS `t_phone_code`;

CREATE TABLE `t_phone_code` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phone` char(11) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `code` char(6) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '验证码',
  `send_time` int(10) unsigned NOT NULL COMMENT '发送时间',
  `dead_time` int(10) unsigned NOT NULL COMMENT '过期时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0有效 1失效',
  `next_time` int(11) NOT NULL COMMENT '重新发送时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `t_phone_code` */

/*Table structure for table `t_refund` */

DROP TABLE IF EXISTS `t_refund`;

CREATE TABLE `t_refund` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `train_id` int(11) NOT NULL,
  `refund_time` timestamp NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 待退款 1 已审核 2已退款',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `t_refund` */

/*Table structure for table `t_refund_log` */

DROP TABLE IF EXISTS `t_refund_log`;

CREATE TABLE `t_refund_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '订单号',
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '微信交易号',
  `total_fee` decimal(10,2) unsigned NOT NULL COMMENT '交易金额',
  `refund_no` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '退款号',
  `refund_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '退款交易号',
  `refund_fee` decimal(10,2) unsigned NOT NULL COMMENT '退款金额',
  `refund_desc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '退款描述',
  `is_refund` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 未退款 1已退款',
  `created_at` timestamp NOT NULL COMMENT '创建日期',
  `updated_at` timestamp NOT NULL COMMENT '更新日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `t_refund_log` */

/*Table structure for table `t_teach_course` */

DROP TABLE IF EXISTS `t_teach_course`;

CREATE TABLE `t_teach_course` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `train_id` int(10) unsigned NOT NULL COMMENT '关联t_trains',
  `course_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1亲子园2幼儿园',
  `course_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '课程名称',
  `desc` varchar(200) COLLATE utf8mb4_bin NOT NULL COMMENT '表述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '-1删除0禁用1启用',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

/*Data for the table `t_teach_course` */

/*Table structure for table `t_teach_profess` */

DROP TABLE IF EXISTS `t_teach_profess`;

CREATE TABLE `t_teach_profess` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `professional` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '职称',
  `desc` varchar(200) COLLATE utf8mb4_bin DEFAULT NULL COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '-1 删除 0 禁用  1启用',
  `profess_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1 亲子园 2幼儿园',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

/*Data for the table `t_teach_profess` */

/*Table structure for table `t_train_cert` */

DROP TABLE IF EXISTS `t_train_cert`;

CREATE TABLE `t_train_cert` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `student_id` int(10) unsigned NOT NULL COMMENT '学员id',
  `student_name` varchar(50) NOT NULL COMMENT '学员姓名',
  `cert_picture` varchar(255) NOT NULL COMMENT '证件照片',
  `train_id` int(11) NOT NULL COMMENT '培训id',
  `train_name` varchar(100) NOT NULL COMMENT '培训项目',
  `student_position` varchar(100) NOT NULL COMMENT '职位',
  `park_name` varchar(50) NOT NULL COMMENT '园所',
  `score` varchar(50) NOT NULL COMMENT '成绩',
  `number` varchar(50) NOT NULL COMMENT '编号',
  `created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `t_train_cert` */

/*Table structure for table `t_train_charge` */

DROP TABLE IF EXISTS `t_train_charge`;

CREATE TABLE `t_train_charge` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `train_id` int(10) unsigned NOT NULL COMMENT '关联t_trains',
  `charge_way` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '付费方式1/2/3',
  `unit` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 人 2园所',
  `max_nursery_num` int(10) unsigned NOT NULL COMMENT '限制园所报名人数',
  `min_num` int(11) DEFAULT NULL COMMENT '团购最低人数',
  `attr1_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attr1_value` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attr1_price` decimal(10,2) DEFAULT '0.00',
  `attr2_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attr2_value` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attr2_price` decimal(10,2) DEFAULT '0.00',
  `attr3_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attr3_value` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attr3_price` decimal(10,2) DEFAULT '0.00',
  `is_card` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否上传身份证',
  `is_health` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否上传健康证',
  `is_labor` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否上传劳动合同',
  `is_learnership` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否上传培训协议',
  `is_cert` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 无证书 1 有证书',
  `is_idcard` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否填写身份证号',
  `is_education` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否填写学历',
  `is_school` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否填写学校',
  `is_profession` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否填写专业',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `NewIndex1` (`train_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `t_train_charge` */

/*Table structure for table `t_train_order` */

DROP TABLE IF EXISTS `t_train_order`;

CREATE TABLE `t_train_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '订单标识',
  `contract_no` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '园所合同号',
  `park_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '园所名称',
  `apply_user` int(10) unsigned DEFAULT NULL COMMENT '报名人，关联wx_user',
  `apply_user_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '报名人姓名',
  `apply_phone` char(11) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '报名手机号',
  `apply_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '报名人数',
  `apply_form` tinyint(1) unsigned NOT NULL COMMENT '1 单人 2团购',
  `train_id` int(11) NOT NULL COMMENT '关联trains',
  `total_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '支付费用',
  `is_paid` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 未支付 1 已支付',
  `payment` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 微信2 支付宝 3现金 4 汇款',
  `pay_time` timestamp NULL DEFAULT NULL COMMENT '支付时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 正常 1 退款 2 未支付取消 3审核中 4审核失败 5部分审核 6已审核 7已完成 ',
  `from` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1 线上 2线下',
  `order_source` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1亲子园 2幼儿园',
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注',
  `created_at` timestamp NOT NULL COMMENT '创建时间',
  `updated_at` timestamp NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `t_train_order` */

/*Table structure for table `t_trains` */

DROP TABLE IF EXISTS `t_trains`;

CREATE TABLE `t_trains` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `train_category` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1 亲子园 2幼儿园',
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '培训标题',
  `banner` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'banner图',
  `pre_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '预计报名人数',
  `sale_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '已报名人数',
  `jia_sale_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '虚拟报名数',
  `train_start` date DEFAULT NULL,
  `train_end` date DEFAULT NULL,
  `train_adress` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '培训地址',
  `apply_start` date DEFAULT NULL,
  `apply_end` date DEFAULT NULL,
  `desc` text COLLATE utf8mb4_unicode_ci,
  `desc_md` text COLLATE utf8mb4_unicode_ci,
  `is_free` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1 收费 0 免费',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 待发布 2 已发布 0删除',
  `sort` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `recorder` int(11) DEFAULT NULL,
  `shengming` text COLLATE utf8mb4_unicode_ci COMMENT '声明',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `t_trains` */

/*Table structure for table `t_wx_user` */

DROP TABLE IF EXISTS `t_wx_user`;

CREATE TABLE `t_wx_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `open_id` varchar(100) NOT NULL,
  `contract_no` varchar(50) DEFAULT NULL COMMENT '园所合同号',
  `nick_name` varchar(50) NOT NULL,
  `avatar_url` varchar(255) NOT NULL,
  `city` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `mobile` varchar(50) DEFAULT NULL COMMENT '微信绑定手机号',
  `gender` tinyint(1) NOT NULL DEFAULT '0',
  `province` varchar(50) NOT NULL,
  `app_id` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `NewIndex1` (`open_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `t_wx_user` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`password`,`remember_token`,`created_at`,`updated_at`,`deleted_at`) values (1,'管理员','admin@admin.com','$2y$10$fPXC2g9ZTHitSy.8pMIWMeRR6K5bcsGj0vheKDyUVrBodbj8rm06a','FoeDjkys4hWhPp6a4Kdqc3Yy8Ca4oHRWaai2Y1lbjx0X1F2Rg2jLzDxyZpa6','2018-07-04 14:17:12','2018-07-04 14:17:12',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
