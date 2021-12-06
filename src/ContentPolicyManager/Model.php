<?php
namespace NubersoftCms\ContentPolicyManager;

use \Nubersoft\Settings;
use \NubersoftCms\Dto\ContentPolicyManager\GetPolicy;

class Model
{
    private $Settings;
    
    public $def = [
        'default-src' => [
            "'self'"
        ],
        'frame-src' => [
            "'self'"
        ],
        'img-src' => [
            "'self'"
        ],
        'script-src' => [
            "'self'"
        ],
        'font-src' => [
            "'self'"
        ],
        'style-src' => [
            "'self'"
        ]
    ];

    private $defPolicyKey = [
        'ref_anchor' => 'content_security_policy'
    ];
    /**
     *	@description	
     *	@param	
     */
    public function __construct()
    {
        $this->Settings = new Settings;
    }
    /**
     *	@description	
     *	@param	
     */
    public function create(array $array, $active = false)
    {
        $this->Settings->deleteComponentBy($this->defPolicyKey);
        $type = [];
        foreach($array['policy'] as $key => $value) {
            $policyVal = ($array['type'][$key])?? null;
            if(empty($value) || empty($policyVal))
                continue;
            $type[$value][] = $policyVal;
        }
        if(!empty($type))
            $this->Settings->addComponent(array_merge($this->defPolicyKey, [ 'content' => json_encode($type), 'page_live' => $active? 'on' : 'off' ]));
        return $this;
    }
    /**
     *	@description	
     *	@param	
     */
    public function get(): GetPolicy
    {
        $p = $this->Settings->getComponentBy($this->defPolicyKey);
        if(empty($p))
            return new GetPolicy;
        return new GetPolicy([
            'policies' => $meta = json_decode(($p[0]['content'])?? null),
            'active' => (($p[0]['page_live'])?? null) == 'on',
            'content' => $this->getMeta($meta)
        ]);
    }
    /**
     *	@description	
     *	@param	
     */
    public function getMeta($data = null): string
    {
        if(empty($data))
            $data = $this->get()->policies;

        if(empty($data))
            return '';

        $str = [];

        foreach ($data as $key => $value) {
            $str[] = $key.' '.implode(' ', $value);
        }

        return trim(implode('; ', $str));
    }
    /**
     *	@description	
     *	@param	
     */
    public function __toString()
    {
        return $this->getMeta();
    }
}