<?php
namespace NubersoftCms\Model;

use \Nubersoft\DataNode;
use \NubersoftCms\Dto\Model\Routing\GetInfoResponse;

class Routing extends DataNode
{
    /**
     *	@description	
     *	@param	
     */
    public static function getInfo(): GetInfoResponse
    {
        return new GetInfoResponse((new Routing)->getDataNode('routing_info'));
    }
}