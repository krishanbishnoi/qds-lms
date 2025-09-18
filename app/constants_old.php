<?php

// app/constants.php
// constants with public

return [
    'ADMIN_FOLDER' => 'admin/',
    'DS' => DIRECTORY_SEPARATOR,
    'ROOT' => base_path(),
    'APP_PATH' => app_path(),

    'IMAGE_CONVERT_COMMAND' => '',

    'WEBSITE_URL' => url('/public/').'/',

    'WEBSITE_JS_URL' => url('/public/js/').'/',
    'WEBSITE_CSS_URL' => url('/public/css/').'/',

    'WEBSITE_FRONT_JS_URL' => url('/public/front/js/').'/',
    'WEBSITE_FRONT_CSS_URL' => url('/public/front/css/').'/',
    'WEBSITE_FRONT_IMG_URL' => url('/public/front/images/').'/',

    'WEBSITE_IMG_URL' => url('/public/images/').'/',
    'WEBSITE_UPLOADS_ROOT_PATH' => base_path('public'.DIRECTORY_SEPARATOR),
    'WEBSITE_UPLOADS_URL' => url('/public/').'/',

    'WEBSITE_FRONT_UPLOADS_URL' => url('/public/slider_images/').'/',

    'WEBSITE_ADMIN_URL' => url('/public/admin/').'/',
    'WEBSITE_ADMIN_IMG_URL' => url('/public/admin/img/').'/',
    'WEBSITE_ADMIN_JS_URL' => url('/public/admin/js/').'/',
    'WEBSITE_ADMIN_FONT_URL' => url('/public/admin/fonts/').'/',
    'WEBSITE_ADMIN_CSS_URL' => url('/public/admin/css/').'/',

    'SETTING_FILE_PATH' => app_path('settings.php'),

    'CK_EDITOR_URL' => url('/public/ck_editor_images/').'/',
    'CK_EDITOR_ROOT_PATH' => base_path('public/ck_editor_images/').'/',

    'USER_IMAGE_URL' => url('/public/user_images/').'/',
    'USER_IMAGE_ROOT_PATH' => base_path('public/user_images/').'/',

    'TRAINING_DOCUMENT_URL' => url('/public/training_document/').'/',
    'TRAINING_DOCUMENT_ROOT_PATH' => base_path('public/training_document/').'/',

    'TEST_DOCUMENT_URL' => url('/public/test_document/').'/',
    'TEST_DOCUMENT_ROOT_PATH' => base_path('public/test_document/').'/',

    'CMS_IMAGE_URL' => url('/public/cms_images/').'/',
    'CMS_IMAGE_ROOT_PATH' => base_path('public/cms_images/').'/',

    'TEAM_MEMBERS_IMAGE_URL' => url('/public/team_members_images/').'/',
    'TEAM_MEMBERS_IMAGE_ROOT_PATH' => base_path('public/team_members_images/').'/',

    'BANNER_IMAGE_URL' => url('/public/banner_images/').'/',
    'BANNER_IMAGE_ROOT_PATH' => base_path('public/banner_images/').'/',

    'IMAGE_EXTENSION' => 'jpeg,jpg,png,gif,bmp',
    'DOCUMENT_EXTENSION' => 'doc,docx,pdf,jpeg,jpg,png,gif,bmp',

    'ALLOWED_TAGS_XSS' => '<a><strong><b><p><br><i><font><img><h1><h2><h3><h4><h5><h6><span><div><em><table><ul><li><section><thead><tbody><tr><td><figure><article>',

    'ADMIN_ID' => 1,
    'SUPER_ADMIN_ROLE_ID' => 1,
    'TRAINER_ROLE_ID' => 2,
    'TRAINEE_ROLE_ID' => 3,

    'SUB_ADMIN_ROLE_ID' => 5,
    'MANAGER_ROLE_ID' => 4,

    'user_role' => ['2' => 'Trainer', '3' => 'Trainee', '4' => 'Manager'],
    'test_type' => ['regular_test' => 'Regular Test', 'training_test' => 'Training Test'],

    'SITE_FORM_EMAIL' => 'ravi@gmail.com',

    'default_language' => [
        'folder_code' => 'eng',
        'language_code' => '1',
        'name' => 'English',
    ],

    'Site' => [
        'currency' => '&#2547;',
        'currencyCode' => 'INR',
    ],

    'per_page' => [
        '10' => trans('Default'),
        '15' => '15',
        '20' => '20',
        '30' => '30',
        '50' => '50',
        '100' => '100',
    ],

    'question_type' => ['MCQ' => 'MCQ', 'SCQ' => 'SCQ', 'T/F' => 'T/F'],
];
