<?
# Rad User Manager Version 2.90
# Copyright (C) Rad Inks (Pvt) Ltd. 2003-2005
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
# Portions created by Rad Inks are Copyright (C) 2003-2005
# Rad Inks (Pvt) Ltd. All Rights Reserved.
# 


require_once("../inc/header.php");
require_once("common.php");

create_header();

echo '<p>&nbsp;</p>';

if($userId = is_logged())
{
	$userName = get_name();
	echo '<table border=0 align="center" cellpadding="8" width="600">
			<tr><td	align="center">';
	
	if($userName != "")
	{
		echo "<h3 class='indented'>Welcome back $userName</h3>";
	}
	else
	{
		echo "<h3 class='indented'>Welcome back to Rad Inks</h3>";
	}
	echo '       </td>
			</tr>
			<tr>
				<td align="center"><a href="profile.php">Profile</a> &nbsp; &nbsp;
				<a href="logout.php">Log out</a>';
	if(is_admin())
	{
		echo ' &nbsp; &nbsp; <a href="../admin/">Manage users</a>';
	}
	echo '</tr></table>';
	echo '<p>&nbsp;</p>';			
	echo '<table border=0 width="600"><tr><td><p>			
			Please examine the php source code for this page to get an idea about how a web page
			on your site can be access restricted to members only. You will see a section of code
			that starts with:
			<pre>
				if($userId = is_logged())
			</pre>
			Which is the test to see if the current visitor is logged in. If he is logged in his 
			userId will be returned. a value of zero (0) will be returned when the user is not 
			logged in.
		  </td></tr>
			
		  </table>';
	
}
else
{
	echo '<p class="indented"> You have been logged out</p>';
	require ('login.txt');
}

create_footer();
?>

