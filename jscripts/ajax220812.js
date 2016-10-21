function getXMLHttp_() {
    
    if (window.XMLHttpRequest) {
        try {
            XMLHttp = new XMLHttpRequest();
        } catch (e) { }
    } else if (window.ActiveXObject) {
        try {
            XMLHttp = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                XMLHttp = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) { }
        }
    }
    return XMLHttp;
}

function go_go()  {
    document.getElementById("dict_table_div").innerHTML = "<img align='center' src='images/busy.gif' />";
}

function setSelectionId(object, id_class) {
    //document.getElementById("warning_region").innerHTML = 
    //            "---"; 
    if (object==null)   {
        document.getElementById("warning_region").innerHTML = 
                "Нулевой элемент выделения страницы!";
    } else    {
        try {
            //prev_sel_object = document.getElementById(id_class);
            //prev_sel_object.id = "no_selected";
            var list=object.parentNode.childNodes;
            for (var i=0; i<list.length; i++) {
                if (list[i].id!=null)
                    if (list[i].id == id_class)
                        list[i].id = "no_selected";
            }
        } catch(e)  {
            document.getElementById("warning_region").innerHTML = 
                "Ошибка получения прежнего элемента выделения страницы!";
        }
        try {
            object.id = id_class;
            //document.getElementById("warning_region").innerHTML = 
            //    "Установка!";
        } catch(e)  {
            document.getElementById("warning_region").innerHTML = 
                "Ошибка установки элемента выделения страницы!";
        }
    }
    
    //$(function(){
    //	$('.panel').tabSlideOut({		//Класс панели
    //		tabHandle: '.handle',		//Класс кнопки
    //		pathToTabImage: 'button.gif',	//Путь к изображению кнопки
    //		imageHeight: '122px',		//Высота кнопки
    //		imageWidth: '40px',		//Ширина кнопки
    //		tabLocation: 'bottom',		//Расположение панели top - выдвигается сверху, right - выдвигается справа, bottom - выдвигается снизу, left - выдвигается слева
    //		speed: 300,			//Скорость анимации
    //		action: 'click',		//Метод показа click - выдвигается по клику на кнопку, hover - выдвигается при наведении курсора
    //		topPos: '200px',		//Отступ сверху
    //		fixedPosition: false		//Позиционирование блока false - position: absolute, true - position: fixed
    //	});
    //    });
    
}

function initSort(table_id, pager_id) {

    //$("#myTable").tablesorter({widgets: ['zebra']});
    //$("#"+table_id).tablesorter({widgets: ['zebra']});
    //$("#"+table_id).tablesorterPager({container: $("#"+pager_id), positionFixed: false });
    
}

function linkCalendar() {
    //document.getElementById("warning_region").innerHTML = 
    //  document.getElementById("warning_region").innerHTML + "Привязка календаря к результату AJAX!";
    try {
    list = document.getElementsByClassName('date_time_cont_div');
    //$(".date_time_cont_div").AnyTime_picker(
    //                {format: "%z-%m-%d %H:%i", labelTitle: "Дата-Время",
    //                labelHour: "Час", labelMinute: "Минуты"} );
    for (var i=0; i<list.length; i++) {
       if (list[i].id!=null)
           {
              try {
                var dt_btn = "#"+list[i].id;
                //document.getElementById("warning_region").innerHTML = 
                //document.getElementById("warning_region").innerHTML + list[i].id;
                $(dt_btn).AnyTime_noPicker();
                $(dt_btn).AnyTime_picker(
                    {format: "%z-%m-%d %H:%i", labelTitle: "Дата-Время",
                    labelHour: "Час", labelMinute: "Минуты" , zIndex: 100 } );
              } catch ( e ) {
                //document.getElementById("warning_region").innerHTML = 
                //    "Ошибка привязки календаря к результату AJAX!"+e.toString();
              }   
           }
    }
    } catch ( e ) {
                //document.getElementById("warning_region").innerHTML = 
               //     "Ошибка разбора списка элементов по classname!"+e.toString();
    } 
    
    try {
    list = document.getElementsByClassName('time_cont_div');
    for (var i=0; i<list.length; i++) {
       if (list[i].id!=null)
           {
              try {
                var dt_btn = "#"+list[i].id; 
                $(dt_btn).AnyTime_noPicker();
                $(dt_btn).AnyTime_picker(
                    {format: "%H:%i", labelTitle: "Время",
                    labelHour: "Час", labelMinute: "Минуты", zIndex: 100 } );
              } catch ( e ) {
                //document.getElementById("warning_region").innerHTML = 
                //    "Ошибка привязки календаря к результату AJAX!";
              }   
           }
    }
    } catch ( e ) {
                //document.getElementById("warning_region").innerHTML = 
                //    "Ошибка разбора списка элементов по classname!";
    }
    
    $( ".date_cont_div" ).datepicker(
        {
            dateFormat:'yy-mm-dd'
        });
        
    try {
    list = document.getElementsByClassName('date_cont_div');
    for (var i=0; i<list.length; i++) {
       if (list[i].id!=null)
           {
              try {
                var dt_btn = "#"+list[i].id;  
                //$(dt_btn).AnyTime_noPicker();
                //$(dt_btn).AnyTime_picker(
                //    {format: "%z-%m-%d", labelTitle: "Дата",
                //    labelHour: "Час", labelMinute: "Минуты", zIndex: 100 } );
              } catch ( e ) {
                //document.getElementById("warning_region").innerHTML = 
                //    "Ошибка привязки календаря к результату AJAX!";
              }   
           }
    }
    } catch ( e ) {
                //document.getElementById("warning_region").innerHTML = 
                //    "Ошибка разбора списка элементов по classname!";
    }
    
    //try {
    //list = document.getElementsByClassName('btn-slide');
    //for (var i=0; i<list.length; i++) {
       //document.getElementById("warning_region").innerHTML = 
       //    document.getElementById("warning_region").innerHTML + ("----#"+list[i].id);
    //   if (list[i].id!=null)
    //       {
    //          try {
    //            var pnl = "#panel_"+list[i].id; 
    //            var btn = list[i].id;
                //document.getElementById("warning_region").innerHTML = 
                //document.getElementById("warning_region").innerHTML + pnl + btn;
                //$(".btn-slide").click(function(){
		//$("#"+list[i].id).slideToggle("slow");
		//$(this).toggleClass("active"); return false;
                //    });
    //          } catch ( e ) {
                //document.getElementById("warning_region").innerHTML = 
                //    "Ошибка привязки панелей к результату AJAX!";
    //          }   
    //       }
    //}
    
    //} catch ( e ) {
                //document.getElementById("warning_region").innerHTML = 
                //    "Ошибка разбора списка элементов по classname!";
    //} 
    
}

function ajaxGetRequest(request_base, class_name, request_mode, get_params_array, load_indicator, result_container, next_select_params, 
    next_function) {
	var XMLHttp, container_object, url, prop_value, part_num;
        part_num=0;
        //setSelectionId(this,'selected_row');
        //document.getElementById("warning_region").innerHTML = "-----!"+class_name;
        hideElement('change_button_default');
        if (isNaN(parseInt(load_indicator))) {
            try {
                part_num_object = document.getElementById(load_indicator);
                if (isNaN(parseInt(part_num_object.value))) {
                    document.getElementById("warning_region").innerHTML = "Нечисловая величина элемента-номера страницы!";
                } else    {
                    part_num = part_num_object.value;
                }
                
            } catch(e)  {
                document.getElementById("warning_region").innerHTML = "Ошибка получения элемента-номера страницы!";
            }
            
        }
        else    {
            part_num = load_indicator;
        }
        
	try	{	
		//document.getElementById(result_container).style.visibility = "visible"; //показываем картинку
		container_object = document.getElementById(result_container);
                
		if ((request_base=="out_table.php")||(request_base=="out_detail_table.php")||
                    (request_base=="out_detail.php"))    {
                    
                    container_object.innerHTML = "<img align='center' src='images/busy.gif' />";
                } else if(request_base=="add_update_delete.php")  {
                    container_object.innerHTML = "<img align='center' src='images/ajax-loader.gif' />";
                } else    {
                    
                }
	} catch ( e ) {
		//container_object.innerHTML = "Ошибка получения элемента-контейнера!";
                try {
                    container_object = document.getElementById("detail_container");
                    if ((request_base=="out_table.php")||(request_base=="out_detail_table.php")||
                        (request_base=="out_detail.php"))    {
                        container_object.innerHTML = "<img align='center' src='images/busy.gif' />";
                    } else if(request_base=="add_update_delete.php")  {
                        container_object.innerHTML = "<img align='center' src='images/ajax-loader.gif' />";
                    } else    {
                    
                    }
                } catch (e) { 
                    document.getElementById("warning_region").innerHTML = "Ошибка получения элемента-контейнера!";
                    return;
                }

                
	}
    try	{
		XMLHttp = getXMLHttp_();
	} catch ( e ) {
		//alert("Ошибка получения объекта AJAX-запроса!");
                container_object.innerHTML = "Ошибка получения объекта AJAX-запроса!";
		return;
	}
	
	//var ssearch = document.getElementById("ssearch").value; //получаем значение из формы
	//составляем линк и отправляем запрос
	try	{
        url = request_base+"?";
        url = url + "&class_name=" + class_name;
        url = url + "&request_mode=" + request_mode;
        url = url + "&part_num=" + part_num;
                
        for(var key in get_params_array) {
            
            try {
                if ((request_base=="out_detail.php")||(key=='set_field_id')) {
                    prop_value=get_params_array[key];
                }   else
                prop_value=document.getElementById(get_params_array[key]).value;
            }
            catch ( e ) {
		          //container_object.innerHTML = container_object.innerHTML+
                          //      "<br/>Ошибка получения параметра ("+key+") по ID ("+get_params_array[key]+")!";
		 if (isNaN(parseInt(get_params_array[key]))) {
                     prop_value = "null";
                 }
                 else   {
                     prop_value = get_params_array[key];
                 }
                          
            }
			// key - название свойства
			url = url + "&" + key + "=" + encodeURIComponent(prop_value);
			// object[key] - значение свойства
            }
	} catch ( e ) {
		container_object.innerHTML = "Ошибка цикла разбора массива параметров!";
		return;
	}

//document.getElementById("warning_region").innerHTML = "-----!"+url;
	try	{
		XMLHttp.open("GET", url, true);
        //container_object.innerHTML = url;
	} catch ( e ) {
		container_object.innerHTML = "Ошибка инициализации объекта AJAX-запроса!";
		return;
	}
	XMLHttp.onreadystatechange = function()	{
            var input_field;
		try {
			if (XMLHttp.readyState != 4) {
				container_object.innerHTML = "<img align='center' src='images/busy.gif' /><br/>"+
                    "<center>Получение данных, статус: "+XMLHttp.readyState+"</center>";
				return;
			}
            
			if(XMLHttp.status != 200)
			{
				container_object.innerHTML = "Error invalid status: " + 
                    XMLHttp.responseText + " status: " + XMLHttp.status;
				delete XMLHttp;
				return;
			}
		} catch ( e ) {
			container_object.innerHTML = "Ошибка обработки состояния ответа сервера!";
			return;
		}
		
		try	{
                            //document.getElementById("warning_region").innerHTML = "-----!"+XMLHttp.responseText;
                            //document.getElementById("warning_region").innerHTML = 
                            //    document.getElementById("warning_region").innerHTML+">";
                            container_object.innerHTML = XMLHttp.responseText;
                            if (request_base=="out_table.php")    {
                                try {
                                    initSort(class_name+"_dict_table", class_name+"_dict_pager");
                                } catch ( e ) {
                                    document.getElementById("warning_region").innerHTML = "Ошибка сортировки и деления таблицы после запроса!";
                                }
                                linkCalendar();
                            }
                            else if (request_base=="out_detail_table.php") {
                                for(var key in get_params_array) {
            
                                    try {
                                        input_field=document.getElementById(
                                            class_name+"_filt_"+key);
                                        if (isNaN(parseInt(get_params_array[key]))) {
                                            prop_value = "null";
                                        }
                                        else   {
                                            prop_value = parseInt(get_params_array[key]);
                                            //document.getElementById("warning_region").innerHTML = 
                                            //    document.getElementById("warning_region").innerHTML + "kkkk"+key+
                                            //    "ssssss"+prop_value+"aaaa"+input_field.value;
                                            input_field.value = prop_value;
                                        }
                                    }
                                    catch ( e ) {
                                        //container_object.innerHTML = container_object.innerHTML+
                                        //      "<br/>Ошибка получения параметра ("+key+") по ID ("+get_params_array[key]+")!";
                                        
                                        prop_value = "null";
                                    }
                                    // key - название свойства
                                    //url = url + "&" + key + "=" + encodeURIComponent(prop_value);
                                    // object[key] - значение свойства
                                }
                                
                                try {
                                    initSort(class_name+"_dict_table", class_name+"_dict_pager");
                                } catch ( e ) {
                                    document.getElementById("warning_region").innerHTML = "Ошибка сортировки и деления таблицы после запроса!";
                                } 
                                linkCalendar();
                            } else if((request_base=="add_update_delete.php")&&true)  {
                                    try {
                                        if (typeof next_function == 'undefined')    {
                                            if (typeof next_select_params == 'undefined') {
                                                if (request_mode=='partial_update_manip_mode')  {
                                                    
                                                } else    {
                                                    ajaxGetRequest("out_table.php", class_name, "select_mode", {}, "", class_name+"_dict_table_div");
                                                }
                                            }
                                            else    {
                                                ajaxGetRequest("out_table.php", class_name, "select_mode", next_select_params, load_indicator, class_name+"_dict_table_div");
                                            }
                                        }
                                        else    {
                                            if (next_function!=null)    {
                                                next_function();
                                            }
                                        }
                                    } catch ( e ) {
                                        document.getElementById("warning_region").innerHTML = "Ошибка обновления таблицы после манипуляции!";
                                    }
                                    
                            } else    {
                                
                            }
		} catch ( e ) {
			container_object.innerHTML = "Ошибка отображения результата AJAX-запроса в контейнере!";
			return;
		}
	}
	try	{
		XMLHttp.send(null);
	} catch ( e ) {
		container_object.innerHTML = "Ошибка посылки AJAX-запроса!";
		return;
	}
    
    delete XMLHttp;
    
    return false;
}

function ajaxGetRequestExtended( direct_params, orderby_params, request_base, class_name, request_mode, get_params_array, load_indicator, result_container, next_select_params, 
    next_function) {
	var XMLHttp, container_object, url, prop_value, part_num;
        part_num=0;
        //setSelectionId(this,'selected_row');
        //document.getElementById("warning_region").innerHTML = "-----!"+class_name;
        hideElement('change_button_default');
        if (isNaN(parseInt(load_indicator))) {
            try {
                part_num_object = document.getElementById(load_indicator);
                if (isNaN(parseInt(part_num_object.value))) {
                    document.getElementById("warning_region").innerHTML = "Нечисловая величина элемента-номера страницы!";
                } else    {
                    part_num = part_num_object.value;
                }
                
            } catch(e)  {
                document.getElementById("warning_region").innerHTML = "Ошибка получения элемента-номера страницы!";
            }
            
        }
        else    {
            part_num = load_indicator;
        }
        
	try	{	
		//document.getElementById(result_container).style.visibility = "visible"; //показываем картинку
		container_object = document.getElementById(result_container);
                
		if ((request_base=="out_table.php")||(request_base=="out_detail_table.php")||
                    (request_base=="out_detail.php"))    {
                    
                    container_object.innerHTML = "<img align='center' src='images/busy.gif' />";
                } else if(request_base=="add_update_delete.php")  {
                    container_object.innerHTML = "<img align='center' src='images/ajax-loader.gif' />";
                } else    {
                    
                }
	} catch ( e ) {
		//container_object.innerHTML = "Ошибка получения элемента-контейнера!";
                try {
                    container_object = document.getElementById("detail_container");
                    if ((request_base=="out_table.php")||(request_base=="out_detail_table.php")||
                        (request_base=="out_detail.php"))    {
                        container_object.innerHTML = "<img align='center' src='images/busy.gif' />";
                    } else if(request_base=="add_update_delete.php")  {
                        container_object.innerHTML = "<img align='center' src='images/ajax-loader.gif' />";
                    } else    {
                    
                    }
                } catch (e) { 
                    document.getElementById("warning_region").innerHTML = "Ошибка получения элемента-контейнера!";
                    return;
                }

                
	}
    try	{
		XMLHttp = getXMLHttp_();
	} catch ( e ) {
		//alert("Ошибка получения объекта AJAX-запроса!");
                container_object.innerHTML = "Ошибка получения объекта AJAX-запроса!";
		return;
	}
	
	//var ssearch = document.getElementById("ssearch").value; //получаем значение из формы
	//составляем линк и отправляем запрос
	try	{
        url = request_base+"?";
        url = url + "&class_name=" + class_name;
        url = url + "&request_mode=" + request_mode;
        url = url + "&part_num=" + part_num;
                
        for(var key in get_params_array) {
            
            try {
                if (request_base=="out_detail.php") {
                    prop_value=get_params_array[key];
                }   else
                prop_value=document.getElementById(get_params_array[key]).value;
            }
            catch ( e ) {
		          //container_object.innerHTML = container_object.innerHTML+
                          //      "<br/>Ошибка получения параметра ("+key+") по ID ("+get_params_array[key]+")!";
		 if (isNaN(parseInt(get_params_array[key]))) {
                     prop_value = "null";
                 }
                 else   {
                     prop_value = get_params_array[key];
                 }
                          
            }
			// key - название свойства
			url = url + "&" + key + "=" + encodeURIComponent(prop_value);
			// object[key] - значение свойства
            }
	} catch ( e ) {
		container_object.innerHTML = "Ошибка цикла разбора массива параметров!";
		return;
	}

//document.getElementById("warning_region").innerHTML = "-----!"+url;
	try	{
		XMLHttp.open("GET", url, true);
        //container_object.innerHTML = url;
	} catch ( e ) {
		container_object.innerHTML = "Ошибка инициализации объекта AJAX-запроса!";
		return;
	}
	XMLHttp.onreadystatechange = function()	{
            var input_field;
		try {
			if (XMLHttp.readyState != 4) {
				container_object.innerHTML = "<img align='center' src='images/busy.gif' /><br/>"+
                    "<center>Получение данных, статус: "+XMLHttp.readyState+"</center>";
				return;
			}
            
			if(XMLHttp.status != 200)
			{
				container_object.innerHTML = "Error invalid status: " + 
                    XMLHttp.responseText + " status: " + XMLHttp.status;
				delete XMLHttp;
				return;
			}
		} catch ( e ) {
			container_object.innerHTML = "Ошибка обработки состояния ответа сервера!";
			return;
		}
		
		try	{
                            //document.getElementById("warning_region").innerHTML = "-----!"+XMLHttp.responseText;
                            container_object.innerHTML = XMLHttp.responseText;
                            if (request_base=="out_table.php")    {
                                try {
                                    initSort(class_name+"_dict_table", class_name+"_dict_pager");
                                } catch ( e ) {
                                    document.getElementById("warning_region").innerHTML = "Ошибка сортировки и деления таблицы после запроса!";
                                }
                                linkCalendar();
                            }
                            else if (request_base=="out_detail_table.php") {
                                for(var key in get_params_array) {
            
                                    try {
                                        input_field=document.getElementById(
                                            class_name+"_filt_"+key);
                                        if (isNaN(parseInt(get_params_array[key]))) {
                                            prop_value = "null";
                                        }
                                        else   {
                                            prop_value = parseInt(get_params_array[key]);
                                            //document.getElementById("warning_region").innerHTML = 
                                            //    document.getElementById("warning_region").innerHTML + "kkkk"+key+
                                            //    "ssssss"+prop_value+"aaaa"+input_field.value;
                                            input_field.value = prop_value;
                                        }
                                    }
                                    catch ( e ) {
                                        //container_object.innerHTML = container_object.innerHTML+
                                        //      "<br/>Ошибка получения параметра ("+key+") по ID ("+get_params_array[key]+")!";
                                        
                                        prop_value = "null";
                                    }
                                    // key - название свойства
                                    //url = url + "&" + key + "=" + encodeURIComponent(prop_value);
                                    // object[key] - значение свойства
                                }
                                
                                try {
                                    initSort(class_name+"_dict_table", class_name+"_dict_pager");
                                } catch ( e ) {
                                    document.getElementById("warning_region").innerHTML = "Ошибка сортировки и деления таблицы после запроса!";
                                } 
                                linkCalendar();
                            } else if((request_base=="add_update_delete.php")&&true)  {
                                    try {
                                        if (typeof next_function == 'undefined')    {
                                            if (typeof next_select_params == 'undefined') {
                                                if (request_mode=='partial_update_manip_mode')  {
                                                    
                                                } else    {
                                                    ajaxGetRequest("out_table.php", class_name, "select_mode", {}, "", class_name+"_dict_table_div");
                                                }
                                            }
                                            else    {
                                                ajaxGetRequest("out_table.php", class_name, "select_mode", next_select_params, load_indicator, class_name+"_dict_table_div");
                                            }
                                        }
                                        else    {
                                            if (next_function!=null)    {
                                                next_function();
                                            }
                                        }
                                    } catch ( e ) {
                                        document.getElementById("warning_region").innerHTML = "Ошибка обновления таблицы после манипуляции!";
                                    }
                                    
                            } else    {
                                
                            }
		} catch ( e ) {
			container_object.innerHTML = "Ошибка отображения результата AJAX-запроса в контейнере!";
			return;
		}
	}
	try	{
		XMLHttp.send(null);
	} catch ( e ) {
		container_object.innerHTML = "Ошибка посылки AJAX-запроса!";
		return;
	}
    
    delete XMLHttp;
    
    return false;
}
 
function getAjaxHandlerFunction() {
	//если завершено то прячем картинку и выводим результат в слой result
	if (XMLHttp.readyState == 4) {
		//document.getElementById("find").style.visibility = "hidden";
		//document.getElementById("result").innerHTML = XMLHttp.responseText;
	}
}