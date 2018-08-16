<?php

/**
* ownCloud - Superlog App
*
*/


class OC_SuperLog {
	public function __construct(){		
		self::clean();
	}

	public static function log($path,$path2,$action,$protocol='web'){
		if(isset($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_USER'])) $user = $_SERVER['PHP_AUTH_USER'];
		else $user = OCP\User::getUser();
		
		if($action=='login attempt'){
			$user=$path;
			$path='';
		}
		
		$folder = is_array($path)?dirname($path['path']):dirname($path);
		$file = is_array($path)?basename($path['path']):basename($path);
		
		$folder2 = is_array($path2)?dirname($path2['path']):(!empty($path2)?dirname($path2):$folder);
		$file2 = is_array($path2)?basename($path2['path']):(!empty($path2)?basename($path2):$file);		
		
		if($action=='login attempt' || $action=='login'){
			$file='.';
			$folder='.';
		}
		
		$type='unknown';
		
		if(!empty($file2)){
			if($protocol=='web'){
				$type = \OC\Files\Filesystem::filetype($folder2.'/'.$file2); 
			}
			elseif($protocol=='caldav'){
				$type = $_SERVER['CONTENT_TYPE']; 
			}
			elseif($protocol=='carddav'){
				$type = $_SERVER['CONTENT_TYPE']; 
			}
			else{
				$CONFIG_DATADIRECTORY = OC_Config::getValue( "datadirectory", OC::$SERVERROOT."/data" );
				if(is_dir($CONFIG_DATADIRECTORY.'/'.$user.'/files')){
					$type='unknown';
					if(is_file($CONFIG_DATADIRECTORY.'/'.$user.'/files'.$folder.$file)) $type='file';
					elseif(is_dir($CONFIG_DATADIRECTORY.'/'.$user.'/files'.$folder.$file)) $type='dir';
				}
			}
			if(strpos($type,';')){
				$type=substr($type,0,strpos($type,';'));
			}
		} 
		
		self::insert($user, $protocol, $type, $folder, $file,$folder2,$file2, $action);
		
	}
	
	public static function clean(){
		$lifetime = OC_Appconfig::getValue('superlog', 'superlog_lifetime','2');		
		$date = strtotime('-'.$lifetime.'days');
		$query=OC_DB::prepare('DELETE FROM `*PREFIX*superlog` WHERE `date`< ? ');
		$cleaner=$query->execute(array(date('Y-m-d H:i:s',$date)));
		
	}
	

	public static function insert($user, $protocol, $type, $folder, $file,$folder2,$file2, $action){
			
		self::clean();
		
		$request=$_REQUEST;
		if(isset($request['password'])) $request['password']='******';
		
		$server=$_SERVER;
		if(isset($server['PHP_AUTH_PW'])) $server['PHP_AUTH_PW']='******';
		if(isset($server['HTTP_COOKIE'])) $server['HTTP_COOKIE']='******';
		if(isset($server['HTTP_AUTHORIZATION'])) $server['HTTP_AUTHORIZATION']='******';
		
		$vars=serialize(array(
			'request'=>$request,
			'server'=>$server
		));
		$date = date('Y-m-d H:i:s');
		$datechk = substr($date,0,-3).'%';
		
		$query=OC_DB::prepare('SELECT `id` FROM `*PREFIX*superlog` WHERE `user`=? AND `date`LIKE ? AND `protocol`=? AND `type`=? AND `folder`=? AND`name`=? AND `folder2`=? AND `name2`=? AND `action`=?');
		$check=$query->execute(array($user,$datechk, $protocol, $type, $folder, $file, $folder2, $file2,$action));
		
		if( (false==$check || empty($check) || (OC_DB::isError($check) || $check->fetchRow()==false)) && !empty($folder) && !empty($file)  ) {
			$query=OC_DB::prepare('INSERT INTO `*PREFIX*superlog`(`user`, `date`,`protocol`,`type`, `folder`,`name`, `folder2`,`name2`,`action`,`vars`) VALUES(?,?,?,?,?,?,?,?,?,?)');
			$result=$query->execute(array($user,$date, $protocol, $type, $folder, $file, $folder2, $file2,$action, $vars));		
			
		
			//return $result;
		}
		
		
	}
	public static function params($params=array()){
		if(!is_array($params)) $params=array();
		 $default = array(
			'start'=>0,
			'limit'=>5, 
			'order_by'=>'date',
			'order'=>'DESC',
			'search'=>'',
			'user'=>'', 
			'since'=>'', 
			'to'=>'', 
			'protocol'=>'', 
			'type'=>'', 
			'folder'=>'', 
			'file'=>'',
			'action'=>''
		);
		foreach ($default as $k=>$v){
			if(!isset($params[$k])) $params[$k]=$v;
		}
		return $params;
	}
	public static function get($params=array()){
		// Check default params
		$params = self::params($params);
		
		// Built the query
		$string='SELECT * FROM `*PREFIX*superlog` WHERE `action`!=\'PROPFIND\' ';
		$vars=array();
		
		if(!empty($params['since'])){
			$string.= 'AND `date`>=? ';
			$vars[]=date('Y-m-d H:i:s',strtotime($params['since']));
		}
		if(!empty($params['to'])){
			$string.= 'AND `date`<=? ';
			$vars[]=date('Y-m-d H:i:s',strtotime($params['to']));
		}
		if(!empty($params['search'])){
			$string.= 'AND ( `user`LIKE? OR `folder`LIKE? OR `folder2`LIKE? OR `name`LIKE? OR `name2`LIKE? ) ';
			$vars[]='%'.$params['search'].'%';
			$vars[]='%'.$params['search'].'%';
			$vars[]='%'.$params['search'].'%';
			$vars[]='%'.$params['search'].'%';
			$vars[]='%'.$params['search'].'%';
		}
		if(!empty($params['user'])){
			$string.= 'AND `user`=? ';
			$vars[]=$params['user'];
		}
		if(!empty($params['protocol'])){
			$string.= 'AND `protocol`=? ';
			$vars[]=$params['protocol'];
		}
		if(!empty($params['type'])){
			$string.= 'AND `type`=? ';
			$vars[]=$params['type'];
		}
		if(!empty($params['action'])){
			$string.= 'AND `action`=? ';
			$vars[]=$params['action'];
		}
		if(!empty($params['folder'])){
			$string.= 'AND (`folder`=? OR `folder2`=? ) ';
			$vars[]=$params['folder'];
			$vars[]=$params['folder'];
		}
		if(!empty($params['file'])){
			$string.= 'AND (`name`=? OR `name2`=? ) ';
			$vars[]=$params['file'];
			$vars[]=$params['file'];
		}
		
		$string.='ORDER BY `'.$params['order_by'].'`'.$params['order'].' LIMIT '.$params['start'].','.$params['limit'];
		
		// Execute the query
		$query=OC_DB::prepare($string);
		$check=$query->execute($vars);
		if(OC_DB::isError($check)) {
			return false;
		}
		
		$l = new OC_L10N('superlog');		
		
		$logs=array();
		while($log=$check->fetchRow()) {
			
			
			
			//Webdav multiaction patch
			if($log['protocol']=='webdav'){
				if($log['action']=='move' && $log['folder']==$log['folder2'] && $log['name']==$log['name2']){
					$qs='SELECT `name` FROM `*PREFIX*superlog` WHERE `action`=\'PROPFIND\' AND `protocol`=\'webdav\' AND `folder`=? AND `date`>=?  AND `user`=?  AND `name`=`name2`LIMIT 0,1';
					$qsr=OC_DB::prepare($qs);
					$patch=$qsr->execute(array($log['folder'],$log['date'],$log['user']));
					if(!OC_DB::isError($patch)) {
						$patch=$patch->fetchRow();
						$log['name2']=$patch['name'];
						$log['action']='rename';
					}
					
				}
			}
			
			// Human readable returns
			switch ($log['action']){
				case 'write':
						$activity=$l->t('Has created or modified').
						' <span class="'.$log['type'].'">'.urldecode($log['name']).'</span> '.
						$l->t('in').
						' <span class="dir">'.urldecode($log['folder']).'</span>';
					break;
				case 'delete':
						$activity=$l->t('Has deleted').
						' <span class="'.$log['type'].'">'.urldecode($log['name']).'</span> '.
						$l->t('from').
						' <span class="dir">'.urldecode($log['folder']).'</span> ';
					break;
				case 'move':
						$activity=$l->t('Has moved').
						' <span class="'.$log['type'].'">'.urldecode($log['name']).'</span> '.
						$l->t('from').
						' <span class="dir">'.urldecode($log['folder']).'</span> ';
						$l->t('to').
						' <span class="dir">'.urldecode($log['folder2']).'</span> ';
					break;
				case 'rename':
					$activity=$l->t('Has renamed').
						' <span class="'.$log['type'].'">'.urldecode($log['name']).'</span> '.
						$l->t('into').
						' <span class="'.$log['type'].'">'.urldecode($log['name2']).'</span> '.
						$l->t('in').
						' <span class="dir">'.urldecode($log['folder']).'</span> ';
					break;
				case 'login':
					$activity=$l->t('login');
					break;
				case 'login attempt':
					$activity=$l->t('login attempt');
					break;
				default:
					$activity=$l->t($log['action']).
					' <span class="'.$log['type'].'">'.urldecode($log['name']).'</span>'.
					$l->t('in').
						' <span class="dir">'.urldecode($log['folder']).'</span> ';				
			}
			$activity.=' <span class="protocol">'.$l->t('via').' '.$log['protocol'].'</span>';
			
			
			$logs[]=array(
				'user'=>$log['user'],
				'date'=>date($l->t('m.d.Y H:i:s'),strtotime($log['date'])),
				'activity'=>$activity
			);
		}
		return $logs;
	}

}