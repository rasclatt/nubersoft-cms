<?php
namespace NubersoftCms\Model;

use \Nubersoft\nQuery;
use \NubersoftCms\Dto\Model\Database\AttributesResponse;

class Database
{
    /**
     *	@description	
     *	@param	
     */
    public static function getTables(): array
    {
        return array_map(function($v){
            return $v['Tables_in_'.base64_decode(DB_NAME)];
        }, (new nQuery)->query("show tables")->getResults());
    }
    /**
     *	@description	
     *	@param	
     */
    public static function getAttributes(string $table): array
    {
        return array_map(function($v) {
            return new AttributesResponse($v);
        }, (new nQuery)->describe($table));
    }
}