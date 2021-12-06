<?php
namespace NubersoftCms\Model;

use \NubersoftCms\Dto\Model\AdminTools\PluginsResponse;

class AdminTools
{
    /**
     *	@description	
     *	@param	
     */
    public static function plugins(string $plugin_folder = null): PluginsResponse
    {
        $array = null;
        foreach(\Nubersoft\nApp::call()->getDataNode('plugins')['paths'] as $path):
            if(!is_dir($path))
                continue;
        
            foreach(scandir($path) as $pdir):
                if(in_array($pdir, ['.','..']))
                    continue;
                
                $ui = $path.DS.$pdir.DS.'admin_ui.php';
                $uib = $path.DS.$pdir.DS.'admin_ui_button.php';
                if(!is_file($ui) && !is_file($uib))
                    continue;

                $array[$pdir] = [
                    'ui' => is_file($ui)? $ui : '',
                    'ui_button' => is_file($uib)? $uib : '',
                    'name' => $pdir
                ];

                if(!empty($plugin_folder) && $plugin_folder == $pdir) {
                    return new PluginsResponse([$pdir => $array[$pdir]]);
                }

            endforeach;
        endforeach;
        return new PluginsResponse($array);
    }
    /**
     *	@description	
     *	@param	
     */
    public static function pluginsWithButtons(): array
    {
        return array_filter(array_map(function($v) {
            return (!empty($v->ui_button))? $v->ui_button : false;
        }, self::plugins()->plugins));
    }
}