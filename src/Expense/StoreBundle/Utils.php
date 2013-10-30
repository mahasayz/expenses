<?php

namespace Expense\StoreBundle;

class Utils {
	public static $paginationLimit = 4;
	
	public static function getCountry(){
		return array(
			"BDT" => "TK",
			"AUD" => "$",
			"USD" => "$",
		);
	}
}

?>