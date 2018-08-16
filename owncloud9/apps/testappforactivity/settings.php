<?php
$tmpl = new OC_Template('superlog', 'settings');
$tmpl->assign('superlog_lifetime', OC_Appconfig::getValue('superlog', 'superlog_lifetime','2'));


return $tmpl->fetchPage();
