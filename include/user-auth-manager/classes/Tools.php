<?php
// *
// Author: Ian Cain, 2010, ian.colorado@gmail.com
//  Copyright (c) 2008 The NetBSD Foundation, Inc.
//  All rights reserved.
// 
//  This code is derived from software contributed to The NetBSD Foundation
//  by 
// 
//  Redistribution and use in source and binary forms, with or without
//  modification, are permitted provided that the following conditions
//  are met:
//  1. Redistributions of source code must retain the above copyright
//     notice, this list of conditions and the following disclaimer.
//  2. Redistributions in binary form must reproduce the above copyright
//     notice, this list of conditions and the following disclaimer in the
//     documentation and/or other materials provided with the distribution.
// 
//  THIS SOFTWARE IS PROVIDED BY THE NETBSD FOUNDATION, INC. AND CONTRIBUTORS
//  ``AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED
//  TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
//  PURPOSE ARE DISCLAIMED.  IN NO EVENT SHALL THE FOUNDATION OR CONTRIBUTORS
//  BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
//  CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
//  SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
//  INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
//  CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
//  ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
//  POSSIBILITY OF SUCH DAMAGE.
// *

class Tools
{
	private $months = array (
          1 => 'january',2 => 'february',3 => 'march', 4 => 'april',
          5 => 'may',6 => 'june',7 => 'july',8 => 'august',9 => 'september',
          10 => 'october',11 => 'november', 12 => 'december');

	function cleanUserInput($array) {
		foreach ($array as $key => $value) {
			//$array[$key] = mysql_real_escape_string($value);  // not working for some reason.  blanking values
			$array[$key] = addslashes($value);
		}
	}
	
	function monthStrToNum($month_str) {
		$month_str = strtolower($month_str);
		$mon_num = array_search($month_str, $this->months);
		if( 0 !== $mon_num ) {
			return $mon_num;
		}
		return false;
	}

	function monthNumToStr($month_num) {
		foreach($this->months as $key => $value) {
			if( $key === $month_num) {
				return ucwords($this->months[$key]);
			}
		}
	}
	
	function future_years($numyrs) {
		$curryear = date('Y');
		$expyeararr = array();
		$i=$curryear;
		while($i <= ($curryear + $numyrs)){
			array_push($expyeararr, $i);
			$i++;
		}
		return $expyeararr;
	}
	
	function year_select($numyrs,$name){
		$yr = $this->future_years($numyrs);
		$out = "<select id=\"$name\" name=\"$name\">\n";
		$i = 0;
		while($i <= $numyrs){
			$out .= "  <option>$yr[$i]</option>\n";
			$i++;
		}
		$out .= "</select>";
		return $out;
	}
	
	function month_select($name){
		$out = "<select id=\"$name\" name=\"$name\">\n";
		$i=1;
		while($i <= 12){
			$out .= "  <option>$i</option>\n";
			$i++;
		}
		$out .= "</select>";
		return $out;
	}
}

?>
