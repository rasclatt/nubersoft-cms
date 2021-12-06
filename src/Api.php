<?php
namespace NubersoftCms;

class Api implements \Nubersoft\nObserver
{
    private $nApp, $request;
    /**
     *	@description	
     *	@param	
     */
    public function __construct(\Nubersoft\nApp $nApp)
    {
        $this->nApp = $nApp;
        $this->request = $this->nApp->getPost();
    }
    /**
     *	@description	
     *	@param	
     */
    public function listen()
    {
        $service = explode('.', preg_replace('/[^A-Z\.]/i', '', $this->request['service']?? ''));
        if(count($service) != 2)
            $this->nApp->ajaxResponse(['code' => 404, 'error' => 'Invalid request']);
            
        $class = $service[0];
        $method = $service[1];
        $classPath = "\\NubersoftCms\\Service\\{$class}";
        
        if(!class_exists($classPath))
            $this->nApp->ajaxResponse(['error' => 404, 'error' => 'Invalid request']);

        $this->nApp->ajaxResponse(\Nubersoft\nReflect::instantiate($classPath)->listen($method, $this->request, $class));
    }
}