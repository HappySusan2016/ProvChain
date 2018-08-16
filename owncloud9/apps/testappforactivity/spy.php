<?php
require_once 'apps/superlog/lib/log.php';
require_once 'apps/superlog/lib/hooks.php';
OC_SuperLog_Hooks::all($_SERVER);
