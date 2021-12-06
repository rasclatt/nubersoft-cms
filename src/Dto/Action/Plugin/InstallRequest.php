<?php
namespace NubersoftCms\Dto\Action\Plugin;

class InstallRequest extends \SmartDto\Dto
{
    public $plugin;
    /**
     *	@description	
     *	@param	
     */
    protected function beforeConstruct($array)
    {
        $upload = \Nubersoft\Helper\File::get();
        if($upload->type != 'application/zip')
            throw new \Exception('File must be a zip document', 500);

        $array['plugin'] = $upload;
        return $array;
    }
}