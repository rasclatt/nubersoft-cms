<?php
namespace NubersoftCms\Dto\Index;

use \Nubersoft\ {
    nApp,
    nAutomator\Controller as nAutomator,
    nGlobal\Observer as nGlobal,
    nRouter,
    nSession,
    Settings
};

class CreateContainerRequest extends \SmartDto\Dto
{
    public $nApp, $Session, $nGlobal, $Automator, $Router, $Settings;
    /**
     *	@description	Creates core functions for the cms start up process
     */
    protected function beforeConstruct($array)
    {
        $array['nApp'] = new nApp;
        $array['Session'] = new nSession;
        $array['nGlobal'] = new nGlobal;
        $array['Automator'] = new nAutomator;
        $array['Router'] = new nRouter;
        $array['Settings'] = new Settings;

        return $array;
    }
    /**
     *	@description	Redirects to program start up
     */
    public function initStartUp()
    {
        if (!is_file(NBR_CLIENT_SETTINGS.DS.'dbcreds.php')) {
            $this->Router->redirect('/domain/core/installer/index.php');
        }
    }
    /**
     *	@description	Starts the default application
     */
    public function runApplication()
    {
        $this->Automator->createWorkflow('default');
    }
}