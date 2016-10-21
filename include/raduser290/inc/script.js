/*
 * Rad User Manager Version 2.00
 * Copyright (C) Rad Inks (Pvt) Ltd. 2003-2004
 * http://www.radinks.net/
 *
 * Licence:
 * The contents of this file are subject to the Mozilla Public
 * License Version 1.1 (the "License"); you may not use this file
 * except in compliance with the License. You may obtain a copy of
 * the License at http://www.mozilla.org/MPL/
 * 
 * Software distributed under the License is distributed on an "AS
 * IS" basis, WITHOUT WARRANTY OF ANY KIND, either express or
 * implied. See the License for the specific language governing
 * rights and limitations under the License.
 * 
 * The Initial Developer of the Original Code is Rad Inks (Pvt) Ltd.
 * Portions created by Rad Inks are Copyright (C) 2003
 * Rad Inks (Pvt) Ltd. All Rights Reserved.
*/ 

function select_all()
{
        var elements = document.forms[0].elements;
        var eleCount = elements.length;
        var check = document.forms[0].elements[0].checked;

        for(var i=0; i < eleCount ; i++)
        {
                elements[i].checked=check;
                //alert(document.forms[0].elements[0].value);
        }
}

/**
 * when adding new items we need to refresh the left frame that contains the
 * list of current users. Otherwise some administrators get confused. 
 */
function refresh_left()
{
	window.top.frames['left'].location.reload();
}