<?php
OCP\JSON::checkLoggedIn();
OCP\JSON::callCheck();
$params = OC_SuperLog::params($_REQUEST);

if(basename($_SERVER['HTTP_REFERER'])!='admin'){
	$params['user']=OC_User::getUser();
}
if(false === $list = OC_SuperLog::get($params)){
	OCP\JSON::error(array('message'=>'Error retreiving superlog list'));
} 
else{
	OCP\JSON::success(array('data'=>$list));
}

