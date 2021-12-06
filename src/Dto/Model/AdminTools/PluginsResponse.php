<?php
namespace NubersoftCms\Dto\Model\AdminTools;

class PluginsResponse extends \SmartDto\Dto
{
    public int $count = 0;
    public array $plugins = [];
    /**
    *	@description
    *	@param	
    */
    protected function beforeConstruct($array)
    {
        $new['count'] = (is_array($array))? count($array) : 0;
        $new['plugins'] = (is_array($array))? array_map(function($v){
            return ($v instanceof PluginsResponseObj)? $v : new PluginsResponseObj($v);
        }, $array) : [];

        return $new;
    }
}

class PluginsResponseObj extends \SmartDto\Dto
{
    public string $ui = '';
    public string $ui_button = '';
    public string $name;
}