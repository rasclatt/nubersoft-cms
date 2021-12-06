<?php
namespace NubersoftCms\Service;

class Admin extends \NubersoftCms\Api\Observer
{
    protected $db;
    /**
     *	@description	
     *	@param	
     */
    public function __construct(\Nubersoft\nQuery $db)
    {
        $this->db = $db;
    }
    protected function getDatabaseContents($request)
    {
        $sql    =   [];
        foreach($this->db->query("SHOW TABLES")->getResults() as $table) {
            $tble   =   $table[key($table)];
            $create =   array_values($this->db->query("SHOW CREATE TABLE ".$tble)->getResults(1));
            $sql[]  =   $create[1];
            
            $insert =   $this->db->query("SELECT * FROM ".$tble)->getResults();
            if($insert) {
                $inserts   =    "INSERT IGNORE INTO `".$tble."` (`".implode('`, `', array_keys($insert[0]))."`) VALUES ";
                foreach($insert as $row){
                    $sql[]  =   $inserts."('".implode("', '", array_map(function($v){ return str_replace(['\\',"'"],["\\\\", "\'"],$v); }, array_values($row)))."')";
                }
            }
        }
        return $sql;
    }
}