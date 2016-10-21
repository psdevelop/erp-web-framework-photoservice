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

require_once('common.php');

if(is_logged())
{
	header("Location: my.php\n");
}
else
{
	include("../inc/header.php");
	create_header();

	echo '<p>&nbsp;</p>';
	echo '<table width="90%" align="center">
			<tr><td align="center" border=1>';
	require_once('login.txt');
	
	echo '</td></tr>';
	echo '<tr><td align="center"><a href="http://www.radinks.net/user/" style="margin-top:20px; font-size:90%">Rad User Manger</a></td></tr>';
	echo '</table>';

	create_footer();
}
?>

