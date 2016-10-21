<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */

require_once("classes/tools.class.php");

abstract class HTMLPage extends Tools
{
    protected $Title = "";
    
function __construct($Title)    {
    $this->Title = $Title;
}

function BeginHTML()
 {
    echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" 
    \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
    <html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">
        <head>
            <meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />
            <meta name=\"author\" content=\"\" />
            <title>{$this->Title}</title>

            <!--[if lte IE 7]>
            <style type=\"text/css\">
                html .jqueryslidemenu{height: 1%;} /*Holly Hack for IE7 and below*/
            </style>
            <![endif]-->";
            
    $this->writeHeaderAttachment();
            
    echo "
    <script language=\"JavaScript\" type=\"text/javascript\">
        
        $(document).ready(function() {
              
            linkCalendar();

            //align element in the middle of the screen

            $.fn.alignCenter = function() {

                //get margin left

                var marginLeft = Math.max(40, parseInt($(window).width()/2 - $(this).width()/2)) + 'px';

                //get margin top

                var marginTop = Math.max(40, parseInt($(window).height()/2 - $(this).height()/2)) + 'px';

                //return updated element

                return $(this).css({'margin-left':marginLeft, 'margin-top':marginTop});

            };
            

        });
    </script>
       </head>";
 }
 
 abstract function BodyHTML();
 abstract function writeHeaderAttachment();
 
 function EndHTML()
 {
    echo "
    </html>";
 }  
 
 function Write()
 {
    $this->BeginHTML();
    $this->BodyHTML();
    $this->EndHTML();
 }    
  
}

?>
