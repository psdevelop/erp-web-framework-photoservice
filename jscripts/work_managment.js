function fillEditForm (fields_values)   {
    
    for(var key in fields_values) {
        
        try	{	
            //document.getElementById(result_container).style.visibility = "visible"; //показываем картинку
            container_object = document.getElementById(key);
            container_object.value = fields_values[key];
            
            //container_object.checked = true;
            //document.getElementById("warning_region").innerHTML = 
            //    document.getElementById("warning_region").innerHTML+container_object.type;
            try {
                if ((container_object.type=="checkbox")||
                    (container_object.type=="CHECKBOX"))    {
                        //container_object.checked = true;
                        if ((fields_values[key]==1)||(fields_values[key]=="1"))
                            container_object.checked = true;
                        else
                            container_object.checked = false;
                    }
            } catch (e) { 
                document.getElementById("warning_region").innerHTML = 
                    "Ошибка записи в элемент-checkbox!";
            }
    
                
        } catch ( e ) {
            //container_object.innerHTML = "Ошибка получения элемента-контейнера!";
            document.getElementById("warning_region").innerHTML = "Ошибка записи в элемент-контейнер!";
            return;
        }
	}   
    //document.getElementById("warning_region").innerHTML = "Ошибка записи в элемент-контейнер!"; 
}

/*****************************
**     Popup message
******************************/

//close pop-up box
function closePopup()
 {
   try {
        $('#opaco').toggleClass('hidden').removeAttr('style');
   } catch (e) { 
       document.getElementById("warning_region").innerHTML = 
                "Ошибка скрытия фона POPUP-элемента!";
   }
   try {
        $('#popup').toggleClass('hidden');
   } catch (e) { 
       document.getElementById("warning_region").innerHTML = 
                "Ошибка скрытия POPUP-элемента!";
   }
   //return false;
 }

//open pop-up
function showPopup()//popup_type)
 {
   //document.getElementById("warning_region").innerHTML = 
   //             "Старт отображения POPUP-элемента!";
   try {
        //when IE - fade immediately
        if($.browser.msie)
        {
            $('#opaco').height($(document).height()).toggleClass('hidden');
        }
        else
        //in all the rest browsers - fade slowly
        {
            $('#opaco').height($(document).height()).toggleClass('hidden').fadeTo('slow', 0.7);
        }
        //.html($('#popup_' + popup_type).html())
   } catch (e) { 
       document.getElementById("warning_region").innerHTML = 
                "Ошибка отображения фона POPUP-элемента!";
   }

   try {
   $('#popup')
     .alignCenter()
     .toggleClass('hidden');
   } catch (e) { 
       document.getElementById("warning_region").innerHTML = 
                "Ошибка отображения POPUP-элемента!";
   }

   //return false;
 }
 
 function showElement(element_class_name)   {
    try {
    list = document.getElementsByClassName(element_class_name);
    for (var i=0; i<list.length; i++) {
       list[i].style.visibility = 'visible';
        }
    } catch ( e ) {
                //document.getElementById("warning_region").innerHTML = 
               //     "Ошибка разбора списка элементов по classname!"+e.toString();
    }
 }
 
  function hideElement(element_class_name)   {
    try {
    list = document.getElementsByClassName(element_class_name);
    for (var i=0; i<list.length; i++) {
       list[i].style.visibility = 'hidden';
        }
    } catch ( e ) {
                //document.getElementById("warning_region").innerHTML = 
               //     "Ошибка разбора списка элементов по classname!"+e.toString();
    }
 }
 
 function closeConfirm()
 {
   try {
        $('#opaco').toggleClass('hidden').removeAttr('style');
   } catch (e) { 
       document.getElementById("warning_region").innerHTML = 
                "Ошибка скрытия фона POPUP-элемента!";
   }
   try {
        $('#confirm').toggleClass('hidden');
   } catch (e) { 
       document.getElementById("warning_region").innerHTML = 
                "Ошибка скрытия POPUP-элемента!";
   }
   //return false;
 }
 
 function showConfirm(action_function)//popup_type)
 {
   //document.getElementById("warning_region").innerHTML = 
   //             "Старт отображения POPUP-элемента!";
   try {
        //when IE - fade immediately
        if($.browser.msie)
        {
            $('#opaco').height($(document).height()).toggleClass('hidden');
        }
        else
        //in all the rest browsers - fade slowly
        {
            $('#opaco').height($(document).height()).toggleClass('hidden').fadeTo('slow', 0.7);
        }
        //.html($('#popup_' + popup_type).html())
   } catch (e) { 
       document.getElementById("warning_region").innerHTML = 
                "Ошибка отображения фона POPUP-элемента!";
   }

   try {
   $('#confirm')
     .alignCenter()
     .toggleClass('hidden');
     //$('#confirm_yes')
     document.getElementById("confirm_yes").onclick = function() { 
         action_function();}
   } catch (e) { 
       document.getElementById("warning_region").innerHTML = 
                "Ошибка отображения POPUP-элемента!";
   }

   //return false;
 }
 
 function actionConfirm(action_function)    {
     showConfirm(action_function);
 }
 
 function showDivModal(modal_id)//popup_type)
 {
   //document.getElementById("warning_region").innerHTML = 
   //             "Старт отображения POPUP-элемента!";
   
   try {
        //when IE - fade immediately
        if($.browser.msie)
        {
            $('#opaco').height($(document).height()).toggleClass('hidden');
        }
        else
        //in all the rest browsers - fade slowly
        {
            $('#opaco').height($(document).height()).toggleClass('hidden').fadeTo('slow', 0.7);
        }
        //.html($('#popup_' + popup_type).html())
   } catch (e) { 
       document.getElementById("warning_region").innerHTML = 
                "Ошибка отображения фона POPUP-элемента!";
   }

   try {
       
   //$('#'+modal_id).style.z-index=20;
   var div_id = '#'+modal_id;
   //document.getElementById("warning_region").innerHTML = ;
   //$(div_id).style.visibility = "visible";
   //document.getElementById(modal_id).style.visibility = "visible";
     $(div_id).alignCenter().toggleClass('hidden');//.alignCenter()
       
     //$('#confirm_yes')
     //document.getElementById("confirm_yes").onclick = function() { 
     //    action_function(); }
   } catch (e) { 
       document.getElementById("warning_region").innerHTML = 
                "Ошибка отображения POPUP-элемента!"+div_id;
   }
   
   

   //return false;
 }
 
 function cancelNonCloseAction()    {
     try {
        document.getElementById("not_close_action_window").checked=false; 
     } catch (e) { 
        //not_close_action_window = false;
     }
 }
 
 function closeDivModal(modal_id)//popup_type)
 {
   var not_close_action_window = false;  
     
   try {
    not_close_action_window = 
        document.getElementById("not_close_action_window").checked; 
   } catch (e) { 
       not_close_action_window = false;
   } 
     
   if (!not_close_action_window)    {
   try {
        $('#opaco').toggleClass('hidden').removeAttr('style');
   } catch (e) { 
       document.getElementById("warning_region").innerHTML = 
                "Ошибка скрытия фона POPUP-элемента!";
   }  
     
   try {
       
     var div_id = '#'+modal_id;

     $(div_id).toggleClass('hidden');//.alignCenter()

   } catch (e) { 
       document.getElementById("warning_region").innerHTML = 
                "Ошибка скрытия POPUP-элемента!"+div_id;
   }
   }
   
 }
 
 function actionCompleteFunction( act_counter, complete_function)  {
     act_counter[0]--;
     if (act_counter[0]<=0) {
         complete_function();
     }
 }
 
 function fillContainer(source_id, dest_id, slider_panel_id) {
     if ((source_id!=null)&&(dest_id!=null))    {
         try {
                document.getElementById(dest_id).innerHTML = 
                    document.getElementById(source_id).innerHTML;
            } catch (e) { 
                document.getElementById("warning_region").innerHTML = 
                    "Ошибка копирования контента!";
            }
     }
 }
 
 function setInnerHtmlByClass(class_name, html_content) {
    try {
    list = document.getElementsByClassName(class_name);

    for (var i=0; i<list.length; i++) {
            list[i].innerHTML = html_content;
        }
    } catch ( e ) {
        document.getElementById("warning_region").innerHTML = 
            "Ошибка установки содержимого контейнеров по classname!"+e.toString();
    }
 }
 
  function addToMultiset(val_list_id, dest_id, multiset_id)    {
     try {
        val_list = document.getElementById(val_list_id);
        dest = document.getElementById(dest_id);
        multiset = document.getElementById(multiset_id);
        if ((val_list!=null)&&(dest!=null)&&(multiset!=null))   {
            var oOption = document.createElement("option");
            oOption.appendChild(document.createTextNode(val_list.
                options[val_list.selectedIndex].innerHTML));
            oOption.setAttribute("value", val_list.
                options[val_list.selectedIndex].value);
            
            dest.appendChild(oOption);
            
            var multiset_val="";
            for (var i=0; i < dest.options.length; i++)
            {
                //if (dest.options[i].value>0) {
                    multiset_val = multiset_val+"***___"+dest.options[i].value;
                //}
            }
            multiset.value = multiset_val;
        }
        else
            document.getElementById("warning_region").innerHTML = 
            "Ошибка установки содержимого multiset-поля!";
     } catch ( e ) {
        document.getElementById("warning_region").innerHTML = 
            "Ошибка установки содержимого multiset-поля!"+e.toString();
    }
 }
 
 function deleteFromMultiset(dest_id, multiset_id)    {
     try {
        dest = document.getElementById(dest_id);
        multiset = document.getElementById(multiset_id);
        if ((dest!=null)&&(multiset!=null))   {
            
            //if ((dest.options[dest.selectedIndex].value!=-1)&&
            //    (dest.options[dest.selectedIndex].value!="-1")) {
                    dest.removeChild(dest.options[dest.selectedIndex]);
           //     }
            
            var multiset_val="";
            for (var i=0; i < dest.options.length; i++)
            {
                //if (dest.options[i].value>0) {
                    multiset_val = multiset_val+"***___"+dest.options[i].value;
                //}
            }
            multiset.value = multiset_val;
        }
        else
            document.getElementById("warning_region").innerHTML = 
            "Ошибка установки содержимого multiset-поля!";
     } catch ( e ) {
        document.getElementById("warning_region").innerHTML = 
            "Ошибка установки содержимого multiset-поля!"+e.toString();
    }
 }
 
 function clearMultiSetsAndKeys()  {
     //document.getElementById("warning_region").innerHTML = 
     //               "qqqqqqqqqqqqqqqqq!"+e.toString();
     try {
    list = document.getElementsByClassName('multiset_list');
    //$(".date_time_cont_div").AnyTime_picker(
    //                {format: "%z-%m-%d %H:%i", labelTitle: "Дата-Время",
    //                labelHour: "Час", labelMinute: "Минуты"} );
    for (var i=0; i<list.length; i++) {
              try {
                while (list[i].childNodes.length>0)
                    {
                        list[i].removeChild(list[i].childNodes[0]);
                    }
              } catch ( e ) {
                document.getElementById("warning_region").innerHTML = 
                    "Ошибка разбора списка элементов по classname!"+e.toString();
              }   
    }
    } catch ( e ) {
                document.getElementById("warning_region").innerHTML = 
                    "Ошибка разбора списка элементов по classname!"+e.toString();
    }
 }
 
 