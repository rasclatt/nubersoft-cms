<?php
namespace NubersoftCms\Action;

use \Nubersoft\ {
    nApp,
    Helper\FolderWorks
};
use \Nubersoft\Dto\Helper\FolderWorks\IsDirRequest;
use \NubersoftCms\Dto\Action\Plugin\InstallRequest;

class Plugin implements \Nubersoft\nObserver
{
    private $nApp, $request;
    /**
     *	@description	
     *	@param	
     */
    public function __construct(nApp $nApp)
    {
        $this->nApp = $nApp;
        $this->request = $nApp->getPost();
    }
    /**
     *	@description	
     *	@param	
     */
    public function listen()
    {
        $action = $this->request['subaction'];
        $dtoName = ucwords($action);
        $dtoRequest = "\\NubersoftCms\\Dto\\Action\\Plugin\\{$dtoName}Request";
        $response = $this->{$action}((class_exists($dtoRequest))? new $dtoRequest($this->request) : $this->request);
        if($this->nApp->isAjaxRequest())
            $this->nApp->ajaxResponse($response);
    }
    /**
     *	@description	Uploads a zip archive into the plugins folder in the client directory
     */
    private function install(InstallRequest $request)
    {
        if(!class_exists('ZipArchive'))
            throw new \Exception('ZipArchive is not installed.', 500);
    
        $name = $request->plugin->name;
        $from = NBR_CLIENT_CACHE.DS.'zipper'.time();
        $pluginsDir = NBR_CLIENT_DIR.DS.'template'.DS.'plugins';
        $isDirDto = new IsDirRequest();
        $uploadedFile = $from.DS.$name;
        $isDirDto->dir = $from;
        $isDirDto->create = true;
        $isClientDto = new IsDirRequest();
        $isClientDto->dir = $pluginsDir;
        
        if(!FolderWorks::isDir($isDirDto) || !FolderWorks::isDir($isClientDto))
            throw new \Exception('Cache folder could not be created', 500);
        
        move_uploaded_file($request->plugin->tmp_name, $uploadedFile);
        chmod($uploadedFile, 0777);

        $Zipper = new \ZipArchive();
        $Zipper->open($uploadedFile);
        $Zipper->extractTo($pluginsDir);
        $Zipper->close();
        unlink($uploadedFile);
        rmdir(pathinfo($uploadedFile, PATHINFO_DIRNAME));

        $this->nApp->toSuccess('Plugin Installed');
    }
}