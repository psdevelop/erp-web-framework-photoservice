<?php

/**
 * @author Poltarokov SP
 * @copyright 2011
 */

class DbConnector
{
    protected $driver="mysql";
    protected $db;
    public $dbhost="localhost";
    public $dbname="";
    public $db_login="";//root";
    public $db_password="";//123456";
    protected $dbport=3306; //$dsn .= 'dbport=3306;';
    protected $charset="utf8"; //$dsn .= 'charset=utf8;';
    
    function __construct($dbhost, $dbname, $db_login, $db_password)    {
        $this->dbhost = $dbhost;
        $this->dbname = $dbname;
        $this->db_login = $db_login;
        $this->db_password = $db_password;
    }
    
    function createConnection() {
        $this->disconnect();
        try {
            $db = new PDO($this->driver.":host=".$this->dbhost.
                ";dbname=".$this->dbname.";charset=".$this->charset,
                $this->db_login,$this->db_password);
            $this->db = $db;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }
        return $this->db;
    }
    
    function disconnect()   {
        $this->db = null;
    }
    
    function exec_by_param_result() {
        
    }
    
    function exec_with_prepare_and_params($instruction, $params)    {
        $param_keys = array_keys($params);
        foreach($param_keys as $param_key)  {
            if(strlen($params[$param_key])==0)
                $params[$param_key]=NULL;   
            //echo "--".$params[$param_key];     
        }
        
        //print_r($params); 
        try {
            //unset($params[':order_date']);
            //unset($params[':shooting_date']);
            //unset($params[':shooting_time']);
            //unset($params[':planned_child_count']);
            //unset($params[':code']);
            //unset($params[':order_comment']);
            //unset($params[':code']);
            //unset($params[':shooting_time']);
            //$params[':sur_name']=NULL;
            $stmt  = $this->db->prepare("SET NAMES utf8; ".$instruction);
            //$stmt  = $this->db->prepare("SET @ord_id=NULL; call `add_update_order` (:plot_id,:kg_id,:manager_id,:stock_id,
		//      NULL,NULL,NULL, 0, NULL,@ord_id); ");
        } catch (PDOException $e) { 
            echo "Ошибка подготовки SQL-команды. Сообщение:".$e -> getMessage();
            return null; 
        } //'SELECT * FROM `table` WHERE `pole` = :value AND `pole2` = :value2;'); 
            
        try {
            $stmt -> execute($params);
            //$stmt -> execute();
            //echo "Сообщение:";
        } catch (PDOException $e) { 
            echo "Ошибка выполнения SQL-команды. Сообщение:".$e -> getMessage();
            return null; 
        } 
        
        //echo $instruction;
        //print_r($params);
        $arr = $stmt -> fetchAll(PDO :: FETCH_ASSOC);
        return "";
    }
    
    function query_both_to_array($query_instruction)  {
        try {
            
            //echo $query_instruction;
            
            $this->exec_with_prepare_and_params("SET NAMES utf8;", array());
            $result = $this->db->query($query_instruction);
            //print_r($result);
            if ($result!=null)  {
            try {
            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            //print_r($rows);
            return $rows;
            }
            catch(PDOException $e)  {
                echo "Ошибка разбора результата выполнения SQL-команды. Сообщение:".$e -> getMessage();
                return null;
            }
            } else {
                //echo "ssssssssssssssssssssss";
                return null;
            }
        } catch (PDOException $e) { 
            echo $e -> getMessage();
            return null; 
        }    
    }
    
}

?>