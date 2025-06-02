<?php
/* Global constants for site */
// define('FFMPEG_CONVERT_COMMAND', '');


define("ADMIN_FOLDER", "admin/");
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', base_path());
define('APP_PATH', app_path());

define("IMAGE_CONVERT_COMMAND", "");

define('WEBSITE_URL', url('/') . '/');

define('WEBSITE_JS_URL', WEBSITE_URL . 'js/');
define('WEBSITE_CSS_URL', WEBSITE_URL . 'css/');

define('WEBSITE_FRONT_JS_URL', WEBSITE_URL . 'front/js/');
define('WEBSITE_FRONT_CSS_URL', WEBSITE_URL . 'front/css');
define('WEBSITE_FRONT_IMG_URL', WEBSITE_URL . 'front/images/');

define('WEBSITE_IMG_URL', WEBSITE_URL . 'images/');
define('WEBSITE_UPLOADS_ROOT_PATH', ROOT . DS . 'public' . DS);
define('WEBSITE_UPLOADS_URL', WEBSITE_URL);

define('WEBSITE_FRONT_UPLOADS_URL', WEBSITE_UPLOADS_URL . 'slider_images/');

define('WEBSITE_ADMIN_URL', WEBSITE_URL . ADMIN_FOLDER);
define('WEBSITE_ADMIN_IMG_URL', WEBSITE_ADMIN_URL . 'img/');
define('WEBSITE_ADMIN_JS_URL', WEBSITE_ADMIN_URL . 'js/');
define('WEBSITE_ADMIN_FONT_URL', WEBSITE_ADMIN_URL . 'fonts/');
define('WEBSITE_ADMIN_CSS_URL', WEBSITE_ADMIN_URL . 'css/');

define('SETTING_FILE_PATH', APP_PATH . DS . 'settings.php');

define('CK_EDITOR_URL', WEBSITE_UPLOADS_URL . 'ck_editor_images/');
define('CK_EDITOR_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'ck_editor_images' . DS);



define('USER_IMAGE_URL', WEBSITE_UPLOADS_URL . 'user_images/');
define('USER_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'user_images' . DS);


define('TRAINING_DOCUMENT_URL', WEBSITE_UPLOADS_URL . 'training_document/');
define('TRAINING_DOCUMENT_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'training_document' . DS);


define('TEST_DOCUMENT_URL', WEBSITE_UPLOADS_URL . 'test_document/');
define('TEST_DOCUMENT_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'test_document' . DS);


define('CMS_IMAGE_URL', WEBSITE_UPLOADS_URL . 'cms_images/');
define('CMS_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'cms_images' . DS);

define('TEAM_MEMBERS_IMAGE_URL', WEBSITE_UPLOADS_URL . 'team_members_images/');
define('TEAM_MEMBERS_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'team_members_images' . DS);

define('BANNER_IMAGE_URL', WEBSITE_UPLOADS_URL . 'banner_images/');
define('BANNER_IMAGE_ROOT_PATH', WEBSITE_UPLOADS_ROOT_PATH .  'banner_images' . DS);

//////////////// extension
define('IMAGE_EXTENSION', 'jpeg,jpg,png,gif,bmp');
define('DOCUMENT_EXTENSION', 'doc,docx,pdf,jpeg,jpg,png,gif,bmp');




$config    =    array();

define('ALLOWED_TAGS_XSS', '<a><strong><b><p><br><i><font><img><h1><h2><h3><h4><h5><h6><span><div><em><table><ul><li><section><thead><tbody><tr><td><figure><article>');

define('ADMIN_ID', 1);
define('SUPER_ADMIN_ROLE_ID', 1);
define('TRAINER_ROLE_ID', 2);
define('TRAINEE_ROLE_ID', 3);

define('SUB_ADMIN_ROLE_ID', 5);
define('MANAGER_ROLE_ID', 4);



define('user_role', array('2' => 'Trainer', '3' => 'Trainee', '4' => 'manager'));
define('test_type', array('regular_test' => 'Regular Test', 'training_test' => 'Training Test', 'feedback_test' => 'Feedback Test'));



define('SITE_FORM_EMAIL', 'ravi@gmail.com');


Config::set('default_language.folder_code', 'eng');
Config::set('default_language.language_code', '1');
Config::set('default_language.name', 'English');

Config::set("Site.currency", "&#2547;");
Config::set("Site.currencyCode", "INR");

Config::set('per_page', array('10' => trans("Default"), '15' => '15', '20' => '20', '30' => '30', '50' => '50', '100' => '100'));

define('question_type', array('MCQ' => 'MCQ', 'SCQ' => 'SCQ', 'T/F' => 'T/F', 'FreeText' => 'FreeText'));

define('QDS_PROJECT_LIST', array('RetailIQ' => 'RetailIQ', 'QAViews' => 'QAViews', 'SurveyCXM' => 'SurveyCXM', 'LMS' => 'All LMS'));
