<?php

/**
* ownCloud - MailNotify Plugin
*
* @author Bastien Ho
* @copyleft 2013 bastienho@eelv.fr
* 
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
* License as published by the Free Software Foundation; either 
* version 3 of the License, or any later version.
* 
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU AFFERO GENERAL PUBLIC LICENSE for more details.
*  
* 
*/
OC::$CLASSPATH['OC_SuperLog'] = 'superlog/lib/log.php';
OC::$CLASSPATH['OC_SuperLog_Hooks'] = 'superlog/lib/hooks.php';

OCP\Util::addStyle('superlog', 'superlog');
OCP\Util::addScript('superlog', 'superlog');

OCP\App::registerAdmin('superlog','settings');
OCP\App::registerPersonal('superlog', 'settings');

/* HOOKS */
// Users
OC_HOOK::connect('OC_User', 'pre_login', 'OC_SuperLog_Hooks', 'prelogin');
OC_HOOK::connect('OC_User', 'post_login', 'OC_SuperLog_Hooks', 'login');
OC_HOOK::connect('OC_User', 'logout', 'OC_SuperLog_Hooks', 'logout');

// Filesystem
OC_HOOK::connect('OC_Filesystem', 'post_write', 'OC_SuperLog_Hooks', 'write');
OC_HOOK::connect('OC_Filesystem', 'post_delete', 'OC_SuperLog_Hooks', 'delete');
OC_HOOK::connect('OC_Filesystem', 'post_rename', 'OC_SuperLog_Hooks', 'rename');
OC_HOOK::connect('OC_Filesystem', 'post_copy', 'OC_SuperLog_Hooks', 'copy');

OC_HOOK::connect('\OC\Files\Storage\Shared', 'file_put_contents', 'OC_SuperLog_Hooks', 'all');

// Webdav
OC_HOOK::connect('OC_DAV', 'initialize', 'OC_SuperLog_Hooks', 'dav');

//Apps
OC_HOOK::connect('OC_App', 'post_enable', 'OC_SuperLog_Hooks', 'app_enable');
OC_HOOK::connect('OC_App', 'pre_disable', 'OC_SuperLog_Hooks', 'app_disable');

// Cleanning settings
\OCP\BackgroundJob::addRegularTask('OC_SuperLog', 'clean');
if (isset($_POST['superlog_lifetime']) && is_numeric($_POST['superlog_lifetime'])) {
   OC_Appconfig::setValue('superlog', 'superlog_lifetime', $_POST['superlog_lifetime']);
}

