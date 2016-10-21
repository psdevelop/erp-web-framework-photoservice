<?
# Rad User Manager Version 2.90
# Copyright (C) Rad Inks (Pvt) Ltd. 2003-2004
# http://www.radinks.net/

# Licence:
# The contents of this file are subject to the Mozilla Public
# License Version 1.1 (the "License"); you may not use this file
# except in compliance with the License. You may obtain a copy of
# the License at http://www.mozilla.org/MPL/
# 
# Software distributed under the License is distributed on an "AS
# IS" basis, WITHOUT WARRANTY OF ANY KIND, either express or
# implied. See the License for the specific language governing
# rights and limitations under the License.
# 
# The Initial Developer of the Original Code is Rad Inks (Pvt) Ltd.
# Portions created by Rad Inks are Copyright (C) 2003
# Rad Inks (Pvt) Ltd. All Rights Reserved.
# 

$imgDir="../images";

class UserAdmin
{
	var $strt;
	var $lim=10;
	var $more=0;	
	
	
	function create_form_buttons($newLink="newuser.php",$link='')
	{
		global $imgDir;

		if(isset($this->strt) && $this->strt >= $this->lim)
		{
			$prev = "$link?strt=" . ($this->strt - $this->lim);
		}
		if($this->more ==1)
		{
			$next = "$link?strt=" . ($this->strt + $this->lim);
		}

		echo "<p>
				<center>
				<a href='$prev'><img src='$imgDir/left.gif' border=0 alt='previous'></a>
				<a href='$newLink' target='right'><img src='$imgDir/new.gif' border=0 alt='new item'></a>
				<input type='image' src='$imgDir/del.gif' border=0 name='del' alt='delete item'></a>
				<a href='$next'><img src='$imgDir/right.gif' border=0 alt='next'></a>
			</center>
			</p>";
	}
	
	
	/** 
	 * displayes a paginated list of users,
	 */
	function show_user_list()
	{
		global $db_type;
		if($db_type == 'mysql')
		{
			$query = "SELECT * FROM users LIMIT $this->strt,$this->lim";
		}
		else
		{
			$query = "SELECT * FROM users LIMIT $this->strt OFFSET $this->lim";
		}
		$result = db_query($query);
		db_error_log();

	
		$count = db_num_rows($result);
		
		if($count == 0)
		{
			echo '<tr><td colspan=2>Nothing to show</td></tr>';
		}
		else
		{
			for($i=0 ; $i < $count ; $i++)
			{

				$row = db_fetch_array($result);
				$url ="userdetails.php?userId=$row[0]&name=". urlencode($row[2]);
				printf('<tr><td bgcolor="#CCCCCC">
								<input type="checkbox" name="userId[]" value="%s"></td>
							<td bgcolor="#EFEFEF"><nobr>
								<a href="%s" class="Category" target="right">%s</a></nobr></td></tr>',
						$row[0],$url,$row[2]);
			}
			if($count == $this->lim)
			{
				$this->more =1;
			}
		}
	}
}

?>
