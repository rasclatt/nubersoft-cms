<?php
namespace NubersoftCms\Dto\Model\Database;

class AttributesResponse extends \SmartDto\Dto
{
    public string $field = '';
    public string $type = '';
    public string $null = '';
    public string $key = '';
    public string $default = '';
    public string $extra = '';
    /**
     *	@description	
     *	@param	
     */
    protected function beforeConstruct($array)
    {
        return array_change_key_case($array, CASE_LOWER);
    }
}