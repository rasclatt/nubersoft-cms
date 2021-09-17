<?php
namespace NubersoftCms\Dto\Index;

use \Nubersoft\ {
    nApp,
    nAutomator,
    nGlobal,
    nRouter,
    nSession,
    Settings
};

class CreateContainerRequest extends \SmartDto\Dto
{
    public $nApp, $Session, $nGlobal, $Automator, $Router, $Settings;
    /**
     *	@description	
     *	@param	
     */
    protected function beforeConstruct($array)
    {
        $array['nApp'] = new nApp;
        $array['Session'] = new nSession;
        $array['nGlobal'] = new nGlobal;
        $array['Automator'] = new nAutomator;
        $array['Router'] = new nRouter;
        $array['Settings'] = new Settings;
    }
}