<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */

require_once("classes/navigation_menu.class.php");

class MainMenuClass extends NavigationMenu  {
    
    function __construct()  {
        parent::__construct("myslidemenu", "jqueryslidemenu");
    }
    
    function writeMenu()    {
        $this->addChildItemJSAndHref("Звонки", "", "", "", "index.php?class_name=Call");
        //$meetings_item = $this->addChildItemJS("Встречи", "", "", "");
        $this->addChildItemJSAndHref("Встречи", "", "", "", "index.php?class_name=Meeting");
        //$meetings_item->addChildItemJSAndHref("Результаты", "", "", "", "index.php?class_name=MeetingResult");
        $this->addChildItemJSAndHref("Заказы", "", "", "", "index.php?class_name=Order");
        $this->addChildItemJSAndHref("Съемки", "", "", "", "index.php?class_name=Shooting");
        $oth_dict_menu = $this->addChildItemJS("Справочники", "", "", "");
        $oth_dict_menu->addChildItemJSAndHref("Должности", "", "", "", "index.php?class_name=PersonType");
        $personal_dict_menu = $oth_dict_menu->addChildItemJSAndHref("Персонал", "", "", "", "index.php?class_name=Person");
        //$personal_dict_menu->addChildItemJS("Операторы", "", "", "");
        //$personal_dict_menu->addChildItemJS("Менеджеры", "", "", "");
        $oth_dict_menu->addChildItemJSAndHref("Акции", "", "", "", "index.php?class_name=Stock");
        $oth_dict_menu->addChildItemJSAndHref("Детские сады", "", "", "", "index.php?class_name=KinderGarten");
        $oth_dict_menu->addChildItemJSAndHref("Сюжеты", "", "", "", "index.php?class_name=Plot");
        
        $oth_dict_menu->addChildItemJSAndHref("Районы", "", "", "", "index.php?class_name=Sector");
        $oth_dict_menu->addChildItemJSAndHref("Округа", "", "", "", "index.php?class_name=District");
        $oth_dict_menu->addChildItemJSAndHref("Области", "", "", "", "index.php?class_name=State");
        $oth_dict_menu->addChildItemJSAndHref("Виды задач", "", "", "", "index.php?class_name=TeamType");
        $statuses_menu = $this->addChildItemJS("Статусы", "", "", "");
        $statuses_menu->addChildItemJSAndHref("Статусы звонков", "", "", "", "index.php?class_name=CallStatus");
        $statuses_menu->addChildItemJSAndHref("Статусы заказов", "", "", "", "index.php?class_name=OrderStatus");
        $statuses_menu->addChildItemJSAndHref("Типы результатов встреч", "", "", "", "index.php?class_name=MeetingResultType");
        $statuses_menu->addChildItemJSAndHref("Статусы съемок", "", "", "", "index.php?class_name=ShootingStatus");
        $reports_menu = $this->addChildItemJS("Отчеты", "", "", "");
        $reports_menu->addChildItemJSAndHref("Звонки за период", "", "", "", "index.php?class_name=Call&report_mode");
        $reports_menu->addChildItemJSAndHref("Отчет по встречам", "", "", "", "index.php?class_name=Meeting&report_mode");
        $reports_menu->addChildItemJSAndHref("Заказы - отчет", "", "", "", "index.php?class_name=Order&report_mode");
        $reports_menu->addChildItemJSAndHref("Съемки за период", "", "", "", "index.php?class_name=Shooting&report_mode");
        $this->addChildItemJSAndHref("Администрирование", "", "", "", "index.php?class_name=User");
        $this->addChildItemJS("Настройки", "", "", "");
        $this->addChildItemJSAndHref("Выход", "", "", "", "index.php?action=logout");
        echo "<div class=\"main_menu_default\">";
        $this->generateMenu();
        echo "</div>";
    }
}

?>