<?php

namespace Expense\StoreBundle;

use Symfony\Component\DependencyInjection\Container;
class Utils extends Container{
	public static $paginationLimit = 4;
	
	public static function getCountry(){
		return array(
			"BDT" => "TK",
			"AUD" => "$",
			"USD" => "$",
			"GBP" => "£",
		);
	}
	
	public static function currencyConverter($amt,$from,$to){
	
		$res=file_get_contents('http://www.google.com/ig/calculator?hl=en&q='.$amt.$from.'=?'.$to);
		$data=explode('"',$res);
		if($data[1]=='' || $data[3]==''){
			return null;
		}else{
			$value = $data[3];
			$convertedValue = substr($value, 0, strpos($value, " "));
			return $convertedValue;
			
		}
	
	}
	
	
}

?>