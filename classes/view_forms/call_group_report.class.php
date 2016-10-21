<?php

/**01-02-2012
 * @author Poltarokov SP
 * @copyright 2012
 */

require_once("classes/view_forms/report.class.php");
require_once("include/pChart/pChart/pData.class");
require_once("include/pChart/pChart/pChart.class");

class CallGroupReport extends Report  {

    function __construct($dbconnector)    {
        parent::__construct($dbconnector, "Call", "CallGroup");
        $this->master_table_adapter->setCustomSelectViewName("calls_with_relative");
        $this->master_table_adapter->setGroupExpression(" group by call_status_id ");
        //$this->report_mode =  $GLOBALS['graphical_report_mode'];
        $this->master_table_adapter->setAggregateFields(array(
            //"cat_summ"=>"sum((`fca_view_detail`.`fca_summ` * if((`fca_view_detail`.`account_type` = 1),1,-(1))))",
            //"cat_income"=>"sum((`fca_view_detail`.`fca_summ` * if((`fca_view_detail`.`account_type` = 1),1,0)))",
            "status_count"=>"count(id)",
            ));
        $this->master_table_adapter->base_filter=" (closed<>1) ";
    }
    
    
    function generateReport()   {
        if($this->default_report_name==null)    {
            
        }   else    {
            $this->generateReportByName($this->default_report_name);
        }
            
    }
    
    function generateReportByName($report_name)   {
        if ($report_name == 'call_statuses_pie')   {
            
            //if ($report_name == 'outcome_category_segment')
            //    $this->master_table_adapter->setGroupExpression(" group by fca_category_id ");
            
            $series1Arr = array(); $series2Arr = array(); $series3Arr = array();
            $catIDsSeriesArr = array();
            $absciseLblSerie = array();
            $query_result = $this->master_table_adapter->selectFullWithRelativeGroupMode();
            
            //print_r($query_result);
            
            if((count($query_result)>0)||$this->json_out_type)  {
            
                for($c=0;$c<count($query_result);$c++)  {
                    //if ($query_result[$c]['cat_outcome']>0) {
                    $series1Arr[] = $query_result[$c]['status_count'];
                    
                    if ($report_name == 'call_statuses_pie')   {
                        //if($query_result[$c]['fca_category_id']==0)
                        //    $query_result[$c]['category_name']="Без категории";
                        //if($query_result[$c]['fca_category_id']==null)
                        //    $query_result[$c]['category_name']="Без категории";
                        if ($query_result[$c]['call_status_id']==$GLOBALS['meet_call_status_id'])
                            $series2Arr[] = "Встреча-".$query_result[$c]['status_count'];
                        else if ($query_result[$c]['call_status_id']==$GLOBALS['noanswer_call_status_id'])
                            $series2Arr[] = "Недозвон-".$query_result[$c]['status_count'];
                        else if ($query_result[$c]['call_status_id']==$GLOBALS['abort_call_status_id'])
                            $series2Arr[] = "Отказ-".$query_result[$c]['status_count'];
                        else if ($query_result[$c]['call_status_id']==$GLOBALS['repeat_call_status_id'])
                            $series2Arr[] = "Перезвон-".$query_result[$c]['status_count'];
                        else if ($query_result[$c]['call_status_id']==$GLOBALS['arhived_call_status_id'])
                            $series2Arr[] = "В архиве-".$query_result[$c]['status_count'];
                        else 
                            $series2Arr[] = "Без стутуса-".$query_result[$c]['status_count'];
                        ////$query_result[$c]['category_name'];
                        //$catIDsSeriesArr[] = $query_result[$c]['fca_category_id'];
                    }
                    else    {
                        if($query_result[$c]['root_category_id']==0)
                            $query_result[$c]['root_category_name']="Без категории";
                        if($query_result[$c]['root_category_id']==null)
                            $query_result[$c]['root_category_name']="Без категории";
                        $series2Arr[] = $query_result[$c]['root_category_name'];
                        $catIDsSeriesArr[] = $query_result[$c]['root_category_id'];
                    }
                    //}
                }

                // Dataset definition 
                // print_r($series1Arr);
                if($this->json_out_type)    {
                    exit(json_encode($this->gen_json_data(array(
                            "titleField"=>array("category", $series2Arr),
                            "valueField"=>array("summ", $series1Arr),
                            "idField"=>array("cat_id", $catIDsSeriesArr)
                        )
                    )));
                }
                else    {
                    $DataSet = new pData;
                    $DataSet->AddPoint($series1Arr,"Serie1");
                    $DataSet->AddPoint($series2Arr,"Serie2");
                    $DataSet->AddAllSeries();
                    $DataSet->SetAbsciseLabelSerie("Serie2");

                    // Initialise the graph
                    $Test = new pChart(600,300);
                    $Test->setFontProperties("Fonts/tahoma.ttf",8);
                    $Test->drawFilledRoundedRectangle(7,7,593,293,5,240,240,240);
                    $Test->drawRoundedRectangle(5,5,595,295,5,230,230,230);

                    // Draw the pie chart
                    $Test->AntialiasQuality = 0;
                    $Test->setShadowProperties(2,2,200,200,200);
                    $Test->drawFlatPieGraphWithShadow($DataSet->GetData(),$DataSet->GetDataDescription(),260,165,110,PIE_PERCENTAGE,8);
                    $Test->clearShadow();

                    $Test->drawPieLegend(430,15,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250);
                    $Test->drawTitle(50,22,"Звонки по статусам",50,50,50,585);
                    $Test->Render("images/gen/call_statuses_pie.png");

                    echo "<center><img src=\"images/gen/call_statuses_pie.png\"/></center>";
                    //echo "<ul id=\"chart_history\"></ul>
                    //    <div id=\"chart_pie\" style=\"width: 100%; height: 400px;\"></div>";
                }
                
            }   else
                echo "<center><img src=\"images/one_bit/onebit_38.png\"/><br/>Пустой результат выборки!</center>";
            
        }   else if ($report_name == 'income_categories_diagramm')   {
            $seriesArr = array();
            $absciseLblSerie = array();
            $this->master_table_adapter->setGroupExpression(" group by (month(`fca_view_detail`.`fca_date`) + (year(`fca_view_detail`.`fca_date`) * 12)), fca_category_id ");
            $this->master_table_adapter->setAdditionalFields(array(
            "month_date_point"=>"concat(month(`fca_view_detail`.`fca_date`),'.',year(`fca_view_detail`.`fca_date`))",
                ));
            $query_result = $this->master_table_adapter->selectFullWithRelativeGroupMode();
            //print_r($query_result);
            
            if(count($query_result)>0)  {
                $arr_count = -1;
                $currentArr = null;
                $prev_mon_year=null;
                for($c=0;$c<count($query_result);$c++)  {
                    if (($c==0)||($prev_mon_year<>$query_result[$c]['month_date_point']))   {
                        $arr_count++;
                        $seriesArr[$arr_count] = array();
                        $absciseLblSerie[] = $query_result[$c]['month_date_point'];
                    }
                    //if ($query_result[$c]['cat_income']>0) {
                    $seriesArr[$arr_count][] = $query_result[$c]['cat_income'];
                    //$seriesArr[$arr_count][] = $query_result[$c]['category_name'];
                    //}
                    $prev_mon_year = $query_result[$c]['month_date_point'];
                }
                print_r($seriesArr);
                $arr_count++;
                 // Dataset definition 
                $DataSet = new pData;
                for($c=0;$c<count($seriesArr);$c++)
                    $DataSet->AddPoint($seriesArr[$c],"Serie".($c+1));
                $DataSet->AddPoint($absciseLblSerie,"absSerie");
                $DataSet->AddAllSeries();
                $DataSet->SetAbsciseLabelSerie("absSerie");

                //$DataSet->SetSerieName("January","Serie1");
                //$DataSet->SetSerieName("February","Serie2");

                // Initialise the graph
                $Test = new pChart(700,230);
                $Test->setFontProperties("Fonts/tahoma.ttf",8);
                $Test->setGraphArea(50,30,585,200);
                $Test->drawFilledRoundedRectangle(7,7,693,223,5,240,240,240);
                $Test->drawRoundedRectangle(5,5,695,225,5,230,230,230);
                $Test->drawGraphArea(255,255,255,TRUE);
                $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2,TRUE);
                $Test->drawGrid(4,TRUE,230,230,230,50);

                // Draw the 0 line
                $Test->setFontProperties("Fonts/tahoma.ttf",6);
                $Test->drawTreshold(0,143,55,72,TRUE,TRUE);

                // Draw the bar graph
                $Test->drawOverlayBarGraph($DataSet->GetData(),$DataSet->GetDataDescription());

                // Finish the graph
                $Test->setFontProperties("Fonts/tahoma.ttf",8);
                $Test->drawLegend(600,30,$DataSet->GetDataDescription(),255,255,255);
                $Test->setFontProperties("Fonts/tahoma.ttf",10);
                $Test->drawTitle(50,22,"Example 3",50,50,50,585);
                $Test->Render("images/gen/outcome_cat_gen.png");

                echo "<center><img src=\"images/gen/outcome_cat_gen.png\"/></center>";
            }   else
                echo "<center><img src=\"images/one_bit/onebit_38.png\"/><br/>Пустой результат выборки!</center>";
            
        }
        else    {
            
        }
    }
    
}

?>
