<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */

class Tools
{
    protected $text_css_class_name;
    
    private $months = array (
          1 => 'january',2 => 'february',3 => 'march', 4 => 'april',
          5 => 'may',6 => 'june',7 => 'july',8 => 'august',9 => 'september',
          10 => 'october',11 => 'november', 12 => 'december');
          
    function __construct($text_css_class_name)    {
        $this->text_css_class_name = $text_css_class_name;    
    }
    
    function DateAdd($interval, $number, $date) {

    $date_time_array = getdate($date);
    $hours = $date_time_array['hours'];
    $minutes = $date_time_array['minutes'];
    $seconds = $date_time_array['seconds'];
    $month = $date_time_array['mon'];
    $day = $date_time_array['mday'];
    $year = $date_time_array['year'];

    switch ($interval) {
    
        case 'yyyy':
            $year+=$number;
            break;
        case 'q':
            $year+=($number*3);
            break;
        case 'm':
            $month+=$number;
            break;
        case 'y':
        case 'd':
        case 'w':
            $day+=$number;
            break;
        case 'ww':
            $day+=($number*7);
            break;
        case 'h':
            $hours+=$number;
            break;
        case 'n':
            $minutes+=$number;
            break;
        case 's':
            $seconds+=$number; 
            break;            
    }
       $timestamp= mktime($hours,$minutes,$seconds,$month,$day,$year);
        return $timestamp;
    }
    
    function getTextFromTemplate($elements_array, $template)  {
         if (sizeof($elements_array)>0) {
             if ($template!=null)    {
                 $form_elements_keys = array_keys($elements_array);
                 $template_modified = $template;
                 foreach($form_elements_keys as $form_elements_key) {
                     $template_modified = str_replace("***___".$form_elements_key,
                             $elements_array[$form_elements_key], $template_modified);
                 }
                 
                 return $template_modified;
                 
             }  else
                    return null;
         }  else
             return null;
         
     } 
    
    function getSlideOutButtonJS($panel_class ,$button_class, $image, $height, $width, 
            $top_pos, $fixed, $tab_location)  {
        return "<script type=\"text/javascript\">
                    $(function(){
                        
                        $('.{$panel_class}').tabSlideOut({	//Класс панели
                            tabHandle: '.{$button_class}',	//Класс кнопки
                            pathToTabImage: '{$image}',   //Путь к изображению кнопки
                            imageHeight: '{$height}px',	//Высота кнопки
                            imageWidth: '{$width}px',         //Ширина кнопки
                            tabLocation: '{$tab_location}',       //Расположение панели top - выдвигается сверху, right - выдвигается справа, bottom - выдвигается снизу, left - выдвигается слева
                            speed: 300,                 //Скорость анимации
                            action: 'click',		//Метод показа click - выдвигается по клику на кнопку, hover - выдвигается при наведении курсора
                            topPos: '{$top_pos}px',		//Отступ сверху
                            fixedPosition: {$fixed}	//Позиционирование блока false - position: absolute, true - position: fixed
                        });
                    });
                </script>";
    }
    
    function getSlideOutButtonJSId($panel_class ,$button_class, $image, $height, $width, 
            $top_pos, $fixed, $tab_location)  {
        return "<script type=\"text/javascript\">
                    $(function(){
                        
                        $('#{$panel_class}').tabSlideOut({	//Класс панели
                            tabHandle: '#{$button_class}',	//Класс кнопки
                            pathToTabImage: '{$image}',   //Путь к изображению кнопки
                            imageHeight: '{$height}px',	//Высота кнопки
                            imageWidth: '{$width}px',         //Ширина кнопки
                            tabLocation: '{$tab_location}',       //Расположение панели top - выдвигается сверху, right - выдвигается справа, bottom - выдвигается снизу, left - выдвигается слева
                            speed: 300,                 //Скорость анимации
                            action: 'click',		//Метод показа click - выдвигается по клику на кнопку, hover - выдвигается при наведении курсора
                            topPos: '{$top_pos}px',		//Отступ сверху
                            fixedPosition: {$fixed}	//Позиционирование блока false - position: absolute, true - position: fixed
                        });
                    });
                </script>";
    }
    
    function getSlidePanel($panel_class ,$button_class, $image, $height, $width, 
            $top_pos, $fixed, $tab_location, $panel_content)    {
        return "<div class=\"{$panel_class}\" >
            <a class=\"{$button_class}\" href=\"#\">Content</a><span lang=\"ru\">".
            $panel_content."</span></div>".$this->getSlideOutButtonJS($panel_class ,$button_class, 
            $image, $height, $width, $top_pos, $fixed, $tab_location);
    }
    
    function getSlidePanelId($panel_id, $button_id, $panel_class ,$button_class, $image, $height, $width, 
            $top_pos, $fixed, $tab_location, $panel_content)    {
        return "<div id=\"{$panel_id}\" class=\"{$panel_class}\" >
            <a id=\"{$button_id}\" class=\"{$button_class}\" href=\"#\">Content</a><span lang=\"ru\">".
            $panel_content."</span></div>".$this->getSlideOutButtonJSId($panel_id , $button_id, 
            $image, $height, $width, $top_pos, $fixed, $tab_location);
    }

    function AddJSLinkTag($JSFilepath)   {
        echo "<script language=\"JavaScript\" 
            type=\"text/javascript\" src=\"".$JSFilepath."\"></script>";
    }
    
    function writeJScript($script)  {
        echo "<script language=\"JavaScript\" 
            type=\"text/javascript\">{$script}</script>";
    }
 
    function AddCSSLinkTag($CSSFilepath)   {
        echo "<link href=\"".$CSSFilepath."\" rel=\"stylesheet\" 
            type=\"text/css\">";
    }
    
    function writePager($pager_id)  {
        echo "
            <div id=\"{$pager_id}\" class=\"pager\">
	           <form>
		          <img src=\"images/first.png\" class=\"first\"/>
		          <img src=\"images/prev.png\" class=\"prev\"/>
		          <input type=\"text\" class=\"pagedisplay\"/>
		          <img src=\"images/next.png\" class=\"next\"/>
		          <img src=\"images/last.png\" class=\"last\"/>
		          <select class=\"pagesize\">
			         <option selected=\"selected\"  value=\"10\">10</option>
			         <option value=\"20\">20</option>
			         <option value=\"30\">30</option>
			         <option  value=\"40\">40</option>
		          </select>
	           </form>
            </div>
        ";
    }
    
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
    
    function generate_select($id, $select_array, $value_field, $name_field, $width, $prev_text) {
        //print_r($select_array);
        $out = "<table width=\"100%\"><tr><td align=\"left\">".$prev_text.
                "</td><td align=\"right\"><select id=\"{$id}\" name=\"{$id}\" style=\"width:{$width};\">\n";
        foreach($select_array as $select_item)  {
            $out .= "  <option value=\"{$select_item[$value_field]}\">{$select_item[$name_field]}</option>\n";
        }
        $out .= "</select></td></tr></table>";
        return $out;
    }
    
    function generate_select_with_value($id, $select_array, $value_field, 
            $name_field, $width, $prev_text, $value, $enabled=true) {
        //print_r($select_array);
        $out = "<table width=\"100%\"><tr><td align=\"left\">".$prev_text.
                "</td><td align=\"right\"><select id=\"{$id}\" name=\"{$id}\" 
                style=\"width:{$width};\" ".($enabled?"":"disabled")." >\n";
        
        foreach($select_array as $select_item)  {
            $selected_code = "";
            if($value==$select_item[$value_field])
                $selected_code = " selected=\"true\" ";
            $out .= "  <option {$selected_code} value=\"{$select_item[$value_field]}\">{$select_item[$name_field]}</option>\n";
        }
        $out .= "</select></td></tr></table>";
        return $out;
    }
    
    function generate_ids_select_with_value($id, $select_array, $value_field, 
            $name_field, $width, $prev_text, $value) {
        //print_r($select_array);
        $out = "<table width=\"100%\"><tr><td align=\"left\">".$prev_text.
                "</td></tr><tr><td><input type=\"hidden\" id=\"{$id}\" value=\"\" /></td></tr>
                <tr><td align=\"right\"><select id=\"set_vars_{$id}\" name=\"set_vars_{$id}\" 
                style=\"width:{$width};\">\n";
        
        foreach($select_array as $select_item)  {
            $selected_code = "";
            if($value==$select_item[$value_field])
                $selected_code = " selected=\"true\" ";
            $out .= "  <option {$selected_code} value=\"{$select_item[$value_field]}\">{$select_item[$name_field]}</option>\n";
        }
        $out .= "</select><input type=\"button\" value=\"Добавить\" 
            onclick=\"addToMultiset('set_vars_{$id}', 'multiset_{$id}', '{$id}');\"/>
            <input type=\"button\" value=\"-\" 
            onclick=\"deleteFromMultiset('multiset_{$id}', '{$id}');\"/></td></tr>
            <tr><td><select id=\"multiset_{$id}\" name=\"multiset_{$id}\" class=\"multiset_list\" size=\"4\" width=\"300\" style=\"width: 300px\">
        </select></td></tr></table>";
        return $out;
    }
    
    function generate_select_content($select_array, $value_field, $name_field) {
        //print_r($select_array);
        $out = "";
        foreach($select_array as $select_item)  {
            $out .= "  <option value=\"{$select_item[$value_field]}\">{$select_item[$name_field]}</option>\n";
        }
        return $out;
    }
    
    function generate_select_hidden($id, $select_array, $value_field, $name_field, $width, $prev_text) {
        //print_r($select_array);
        $out = "<select id=\"{$id}\" name=\"{$id}\" style=\"width:{$width}; visibility:none; \">\n";
        foreach($select_array as $select_item)  {
            $out .= "  <option value=\"{$select_item[$value_field]}\">{$select_item[$name_field]}</option>\n";
        }
        $out .= "</select>";
        return $out;
    }
    
    function generate_button($id, $on_click_js)  {
        
    }
    
    function generate_select_js($id, $select_array, $on_change_js)  {
        
    }
    
    function write_text($text, $text_css_class_name)    {
        echo $text;//"<font class=\"{$text_css_class_name}\">".."</font>";
        //echo $text;
    }
    
    function generate_link_button($text, $link_button_class, $on_click_js, $id) {
        echo "<a href=\"#\" class=\"{$link_button_class}\" onclick=\"{$on_click_js}\">{$text}</a>";
    }
    
    function generate_link_button_with_style($text, $link_button_class, $on_click_js, $id, $style) {
        echo "<a href=\"#\" class=\"{$link_button_class}\" onclick=\"{$on_click_js}\" 
            style=\" {$style} \">{$text}</a>";
    }
    
    function generate_link_button_with_href($text, $link_button_class, $on_click_js, $id, $href) {
        echo "<a href=\"{$href}\" class=\"{$link_button_class}\" onclick=\"{$on_click_js}\">{$text}</a>";
    }
    
    function get_link_button($text, $link_button_class, $on_click_js, $id) {
        return "<a href=\"#\" class=\"{$link_button_class}\" onclick=\"{$on_click_js}\">{$text}</a>";
    }
    
    function get_link_button_with_deactivate_js($text, $link_button_class, $on_click_js, $id, $deactivate_class) {
        return "<a href=\"#\" class=\"{$link_button_class}\" onclick=\" this.enabled=false; this.className='button medium gray'; {$on_click_js} this.onclick=null; \">{$text}</a>";
    }
    
    function write_input_onclick_button($text, $link_button_class, $on_click_js) {
        echo "<input type='{$text}' value='Go' onclick=\"{$on_click_js}\">";
    }
    
    function write_div($id, $class, $inner_html) {
        //class=\"{$class}\"
        echo "<div id=\"{$id}\" >{$inner_html}</div>";
    }
    
    function write_input_text($id, $width, $value, $label)   {
        echo $label."<input type=\"text\" id=\"{$id}\" name=\"{$id}\" size=\"{$width}\" value=\"{$value}\" />";
    }
    
    function get_input_text($id, $width, $value, $label)   {
        return "<table width=\"100%\"><tr><td align=\"left\">".$label.
               "</td><td align=\"right\"><input type=\"text\" id=\"{$id}\" name=\"{$id}\" size=\"{$width}\" value=\"{$value}\" /></td></tr></table>";
    }
    
    function get_disabled_input_text($id, $width, $value, $label)   {
        return "<table width=\"100%\"><tr><td align=\"left\">".$label.
               "</td><td align=\"right\"><input type=\"text\" id=\"{$id}\" disabled=\"true\" name=\"{$id}\" size=\"{$width}\" value=\"{$value}\" /></td></tr></table>";
    }
    
    function get_input_checkbox($id, $width, $value, $label)   {
        $checked_value="";
        if (($value!=0)&&($value!="0"))
            $checked_value="checked=\"checked\"";
        return "<table width=\"100%\"><tr><td align=\"left\">".$label.
               "</td><td align=\"right\"><input type=\"checkbox\" {$checked_value} id=\"{$id}\" 
               name=\"{$id}\" size=\"{$width}\" value=\"{$value}\" 
               onchange=\"if(this.checked) this.value='1'; else this.value='0';\" /></td></tr></table>";
    }
    
    function get_input_text_mixed_select($id, $width, $value, $label, $select_array)   {
        $options = "";
               $field_select_keys = array_keys($select_array);
               foreach ($field_select_keys as $field_select_key)  {
                   $options .= "<option value=\"{$field_select_key}\">{$select_array[$field_select_key]}</option>";
               }
        return "<table width=\"100%\"><tr><td align=\"left\">".$label.
               "</td><td align=\"right\"><input type=\"text\" id=\"{$id}\" name=\"{$id}\" size=\"{$width}\" value=\"{$value}\" /><br/>
               Выбрать из: <select id=\"select_{$id}\" onclick=\" document.getElementById('{$id}').value=
               document.getElementById('select_{$id}').value;\" onchange=\" document.getElementById('{$id}').value=
               document.getElementById('select_{$id}').value;\" >{$options}</select></td></tr></table>";
               
    }
    
    function get_input_text_with_class($id, $width, $value, $label, $class_name)   {
        return "<table width=\"100%\"><tr><td align=\"left\">".$label.
               "</td><td align=\"right\"><input type=\"text\" id=\"{$id}\" name=\"{$id}\" class=\"{$class_name}\" size=\"{$width}\" value=\"{$value}\" /></td></tr></table>";
    }
    
    function get_input_text_with_class_and_events($id, $width, $value, $label, $class_name, $events_js)   {
        $onKeyDown_js="";
        if(isset($events_js['onKeyDown']))
            $onKeyDown_js=$events_js['onKeyDown'];
        return "<table width=\"100%\"><tr><td align=\"left\">".$label.
               "</td><td align=\"right\"><input type=\"text\" id=\"{$id}\" 
               name=\"{$id}\" class=\"{$class_name}\" onKeyDown=\"{$onKeyDown_js}\" size=\"{$width}\" 
               value=\"{$value}\" /></td></tr></table>";
    }
    
    function get_input_text_with_class_and_placement($id, $width, $value, $label, $class_name,$placement)   {
        if ($placement=="vertical")
            return "<table width=\"100%\"><tr><td align=\"left\">".$label.
               "</td></tr><tr><td align=\"right\"><input type=\"text\" id=\"{$id}\" 
               name=\"{$id}\" class=\"{$class_name}\" size=\"{$width}\" 
               value=\"{$value}\" /></td></tr></table>";
        else
            return "<table width=\"100%\"><tr><td align=\"left\">".$label.
               "</td><td align=\"right\"><input type=\"text\" id=\"{$id}\" 
               name=\"{$id}\" class=\"{$class_name}\" size=\"{$width}\" 
               value=\"{$value}\" /></td></tr></table>";
    }
    
    function get_input_text_area($id, $width, $value, $label, $height)   {
        return "<table width=\"100%\"><tr><td align=\"left\">".$label.
               "</td><td align=\"right\"><textarea id=\"{$id}\" name=\"{$id}\" cols=\"{$width}\" 
               rows=\"{$height}\" >{$value}</textarea></td></tr></table>";
    }
    
    function get_input_text_hidden($id, $width, $value, $label)   {
        return "<input type=\"hidden\" id=\"{$id}\" name=\"{$id}\" size=\"{$width}\" value=\"{$value}\" />";
    }
    
    function write_input_text_hidden($id, $value)   {
        echo $label."<input type=\"hidden\" id=\"{$id}\" name=\"{$id}\" value=\"{$value}\" />";
    }
    
    function write_input_submit_button($value)   {
        echo "<input type=\"submit\" value=\"{$value}\" />";
    }
    
    function write_input_checkbox($id, $value, $label)   {
        echo "<input type=\"checkbox\" id=\"{$id}\" name=\"{$id}\" value=\"{$value}\" />".$label;
    }
    
    function write_input_select($id, $width, $values)   {
        echo "<input type=\"TEXT\" id=\"{$id}\" name=\"{$id}\" size=\"{$width}\" value=\"{$text}\" />";
    }
    
}

?>