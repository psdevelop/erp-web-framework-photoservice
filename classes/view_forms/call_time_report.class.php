<?php

/**01-02-2012
 * @author Poltarokov SP
 * @copyright 2012
 */

require_once("classes/view_forms/report.class.php");
//require_once("include/pChart/pChart/pCache.class");
require_once("include/pChart/pChart/pData.class");
require_once("include/pChart/pChart/pChart.class");

class CallTimeReport extends Report  {

    function __construct($dbconnector)    {
        parent::__construct($dbconnector, "FinanceActData", "FinanceActData");
        $this->master_table_adapter->setCustomSelectViewName("fca_view");
        $this->master_table_adapter->setGroupExpression(" 
            group by (month(`fca_view`.`fca_date`) + (year(`fca_view`.`fca_date`) * 12)) 
            order by (month(`fca_view`.`fca_date`) + (year(`fca_view`.`fca_date`) * 12)) ASC ");
        $this->report_mode =  $GLOBALS['graphical_report_mode'];
        $this->master_table_adapter->setAggregateFields(array(
            "month_summ"=>"sum((`fca_view`.`fca_summ` * if((`fca_view`.`account_type` = 1),1,-(1))))",
            "month_income"=>"sum((`fca_view`.`fca_summ` * if((`fca_view`.`account_type` = 1),1,0)))",
            "month_outcome"=>"sum((`fca_view`.`fca_summ` * if((`fca_view`.`account_type` = 1),0,1)))",
            ));
        $this->master_table_adapter->setAdditionalFields(array(
            "month_date_point"=>"concat(month(`fca_view`.`fca_date`),'.',year(`fca_view`.`fca_date`))",
            ));
    }
    
    function generateReport()   {
        //echo "lll";
        
        if (!isset($this->default_report_name)) {
            $this->default_report_name = "balance_chart";
        }
        
        if ($this->report_mode == $GLOBALS['table_report_mode'])    {
            //$this->master_table_adapter->selectFullWithRelative();
            //$this->master_table_adapter->generateReportDictHeader();
            //$this->master_table_adapter->writeTable();
        }   else if ($this->report_mode == $GLOBALS['graphical_report_mode'])   {
            //echo "ggg";
            $summ_column_name = "month_summ";
                
            if ($this->default_report_name == "salary_percents_chart")  {
                $this->master_table_adapter->setAggregateFields(array(
                    //"month_summ"=>"sum((`fca_view`.`fca_summ` * if((`fca_view`.`account_type` = 1),1,-(1))))",
                    "month_salary_income"=>"sum((`fca_view`.`fca_summ` * if((`fca_view`.`account_type` = 1) AND ((`fca_view`.`category_entity_id`={$GLOBALS['salary_with_taxes_cat_entity_id']}) OR (`fca_view`.`category_entity_id`={$GLOBALS['salary_without_taxes_cat_entity_id']}) OR (`fca_view`.`category_entity_id`={$GLOBALS['base_salary_cat_entity_id']})),1,0)))",
                    "month_income"=>"sum((`fca_view`.`fca_summ` * if((`fca_view`.`account_type` = 1),1,0)))",
                    "month_outcome"=>"sum((`fca_view`.`fca_summ` * if((`fca_view`.`account_type` = 1),0,1)))",
                ));
                $summ_column_name = "month_salary_income";
            }
            
            $series1Arr = array(); $series2Arr = array(); $series3Arr = array();
            $absciseLblSerie = array();
            $query_result = $this->master_table_adapter->selectFullWithRelativeGroupMode();
            
            if((count($query_result)>0)||$this->json_out_type)  {
            
            if ($this->default_report_name == "salary_percents_chart")  {
                for($c=0;$c<count($query_result);$c++)  {
                    $series1Arr[] = $query_result[$c]['month_income'];
                    $series2Arr[] = -$query_result[$c]['month_outcome'];
                    $series3Arr[] = $query_result[$c]['month_salary_income'];
                    $absciseLblSerie[] = $query_result[$c]['month_date_point'];

                }
            }
            else    {
                
                    for($c=0;$c<count($query_result);$c++)  {
                        $series1Arr[] = $query_result[$c]['month_income'];
                        $series2Arr[] = -$query_result[$c]['month_outcome'];
                        $series3Arr[] = $query_result[$c]['month_summ'];
                        $absciseLblSerie[] = $query_result[$c]['month_date_point'];

                    }
                
            }
            
            if($this->json_out_type)    {
                    exit(json_encode($this->gen_json_data(array(
                            "titleField"=>array("month_date_point", $absciseLblSerie),
                            "valueField"=>array($summ_column_name, $series3Arr),
                            "valueInField"=>array("month_income", $series1Arr),
                            "valueOutField"=>array("month_outcome", $series2Arr)
                        )
                    )));
                }
            else    {
                 // Dataset definition 
                $DataSet = new pData;
                $DataSet->AddPoint($series1Arr,"Serie1");
                $DataSet->AddPoint($series2Arr,"Serie2");
                $DataSet->AddPoint($series3Arr,"Serie3");
                $DataSet->AddPoint($absciseLblSerie,"Serie4");
                $DataSet->AddAllSeries();
                $DataSet->SetAbsciseLabelSerie("Serie4");
                $DataSet->SetSerieName("Приход","Serie1");
                $DataSet->SetSerieName("Расход","Serie2");
                $DataSet->SetSerieName("Баланс","Serie3");
                $DataSet->SetYAxisName("Баланс");
                $DataSet->SetXAxisName("Месяцы");

                 // Initialise the graph
                $Test = new pChart(700,230);
                $Test->setFontProperties("Fonts/tahoma.ttf",8);
                $Test->setGraphArea(50,30,585,200);
                $Test->drawFilledRoundedRectangle(7,7,693,223,5,240,240,240);
                $Test->drawRoundedRectangle(5,5,695,225,5,230,230,230);
                $Test->drawGraphArea(255,255,255,TRUE);
                $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2);
                $Test->drawGrid(4,TRUE,230,230,230,50);

                // Draw the 0 line
                $Test->setFontProperties("Fonts/tahoma.ttf",6);
                $Test->drawTreshold(0,143,55,72,TRUE,TRUE);

                // Draw the line graph
                $Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());
                $Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);

                // Set labels
                $Test->setFontProperties("Fonts/tahoma.ttf",8);
                //$Test->setLabel($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie1","2","Daily incomes",221,230,174);
                //$Test->setLabel($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie2","6","Production break",239,233,195);

                // Finish the graph
                $Test->setFontProperties("Fonts/tahoma.ttf",8);
                $Test->drawLegend(600,30,$DataSet->GetDataDescription(),255,255,255);
                $Test->setFontProperties("Fonts/tahoma.ttf",10);
                $Test->drawTitle(50,22,"Баланс нарастающим итогом",50,50,50,585);
                $Test->Render("images/gen/fca_in_out_gen.png");

                //echo "<center><img src=\"images/gen/fca_in_out_gen.png\"/></center>";
                echo "<ul id=\"chart_history\"></ul>
                        <div id=\"chart_line\" style=\"width: 100%; height: 400px;\"></div>";
                }
                
            }   else
                echo "<center><img src=\"images/one_bit/onebit_38.png\"/><br/>Пустой результат выборки!</center>";
            
        }   else    {
            
        }
    }
    
    function generateReportByName($report_name) {
        
    }
    
}

?>
