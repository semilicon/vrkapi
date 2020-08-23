<?php
function cp($a){
	$a=utf($a);
	return iconv('UTF-8',"CP1251",$a);
}
?>