<?php
namespace NubersoftCms\Dto\Index;

class GetServerSettings extends \SmartDto\Dto
{
    public $mode = 'dev';
    /**
     *	@description	
     *	@param	
     */
    protected function beforeConstruct($array)
    {
        $arr =  ($array['devmode'])?? [];
        $new['mode'] = $arr['option_attribute']?? 'dev';
        return $new;
    }
    /**
     *	@description	
     *	@param	
     */
    public function reportMode()
    {
        $dev = ($this->mode == 'dev');

        if ($dev)
            error_reporting(E_ALL);

        ini_set('display_errors', ($dev? 1 : 0));
    }
}