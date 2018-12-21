<?php

namespace App\Helpers;

class Format {

	/**
	 * return a properly formatted number
	 * @param  float $number 
	 * @return float 
	 */
	public static function number($number) {
		return number_format($number, 0, '.', ',');
	}

	/**
	 * return a number formatted as a currency
	 * @param  float $amount 
	 * @param  string $symbol
	 * @return string         
	 */
	public static function currency($amount, $decimals = 2, $symbol = '$') {
		return $symbol . number_format($amount, $decimals, '.', ',');
	}
	

}