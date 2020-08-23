<?php
function utf($a){
	if(iconv("UTF-8","UTF-8",$a) == $a) {return $a;}
	elseif(iconv("ASCII","ASCII",$a) == $a) {return iconv('ASCII',"UTF-8",$a);}
	elseif(iconv("CP1251","CP1251",$a) == $a) {return iconv('CP1251',"UTF-8",$a);}
	elseif(iconv("KOI8-U","KOI8-U",$a) == $a) {return iconv('KOI8-U',"UTF-8",$a);}
	else{return iconv('',"UTF-8",$a);}
}
?>