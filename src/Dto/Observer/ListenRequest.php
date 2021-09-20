<?php
namespace NubersoftCms\Dto\Observer;

class ListenRequest extends \SmartDto\Dto
{
    public $service = '';
    /**
     *	@description	
     *	@param	
     */
    protected function beforeConstruct($array)
    {
        $array['service'] = trim($array['subaction']?? null);
        return $array;
    }
}