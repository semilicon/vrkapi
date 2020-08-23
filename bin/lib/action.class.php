<?php
  class action{
    public static function getAction($VALUES){
		$action=__URI;
		$vals=array();
		if(count($VALUES)>0&&!((file_exists(__path.'bin/actions/'.$action.'.php')&&$VALUES[count($VALUES)-1]!='main'&&$VALUES[count($VALUES)-1]!='default')||file_exists(__path.'bin/actions/'.$action.'/main.php')||file_exists(__path.'bin/actions/'.$action.'/default.php'))){
			while(count($VALUES)>0) {
				array_unshift($vals,array_pop($VALUES));
				$action=implode('/',$VALUES);
				if((file_exists(__path.'bin/actions/'.$action.'.php')&&$VALUES[count($VALUES)-1]!='main'&&$VALUES[count($VALUES)-1]!='default')||file_exists(__path.'bin/actions/'.$action.'/main.php')||file_exists(__path.'bin/actions/'.$action.'/default.php')){
					if(file_exists(__path.'bin/actions/'.$action.'/main.php')){
						array_push($VALUES,'main');
						$action=implode('/',$VALUES);
					}else if(file_exists(__path.'bin/actions/'.$action.'/default.php')){
						array_push($VALUES,'default');
						$action=implode('/',$VALUES);
					}
					break;
				}
			}
		}else if((file_exists(__path.'bin/actions/'.$action.'.php')&&$VALUES[count($VALUES)-1]!='main'&&$VALUES[count($VALUES)-1]!='default')||file_exists(__path.'bin/actions/'.$action.'/main.php')||file_exists(__path.'bin/actions/'.$action.'/default.php')){
			if(file_exists(__path.'bin/actions/'.$action.'/main.php')){
				array_push($VALUES,'main');
				$action=implode('/',$VALUES);
			}else if(file_exists(__path.'bin/actions/'.$action.'/default.php')){
				array_push($VALUES,'default');
				$action=implode('/',$VALUES);
			}
		}
		array_pop($VALUES);
		$section=implode('/',$VALUES);
		$options=action::getOptions($section);
		return (object)array('name'=>$action,'section'=>$section,'values'=>$vals,'options'=>$options);
	}
    static function getOptions($section){
		if(file_exists(__path.'bin/actions/'.$section.'/options.json')){
			return get_json_file(__path.'bin/actions/'.$section.'/options.json');
		}else return array();
	}
	public static function exec($action){
		if(!isset($action->name)){return false;}
		if(!defined('ACTION'))define('ACTION', $action->name);
		if(!defined('SECTION'))define('SECTION', $action->section);
		if(!defined('MODULE'))define('MODULE', $action->section);//deprecated
		$CURRENT_DIR=getcwd();
		$SECTION_DIR=__path.'bin/actions/'.$action->section;
		
		if(file_exists(__path.'bin/actions/'.$action->name.'.php')){
			chdir($SECTION_DIR);
			ob_start();
			$RETURN=action::call($action->values);
			$CONTENT=ob_get_contents();
			ob_end_clean();
			chdir($CURRENT_DIR);
			$CONTENT=preg_replace('/\x{FEFF}/u', '', $CONTENT);
			
			$RETURN=($CONTENT!='')?$CONTENT:$RETURN;
		}else{
			$RETURN=false;
		}
		return $RETURN;
	}
	static function call($VALUES){
		//global $sID;
		global $HTML,$USER;
		$ACTS=$VALUES;//deprecated
		return include(__path.'bin/actions/'.ACTION.'.php');
	}
  }
?>