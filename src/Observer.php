<?php
namespace NubersoftCms;

class Observer implements \Nubersoft\nObserver
{
    protected $request, $nApp;
    /**
     *	@description	Base controller
     */
    public function listen()
    {
        $this->nApp = \Nubersoft\nApp::call();   
        # Set the request
        $this->request = $this->nApp->getRequest();
        # See if an api service is being called
        if(!empty($this->request['service']))
            return $this;
        # Set request to get service
        $request = new \NubersoftCms\Dto\Observer\ListenRequest($this->request);
        # Stop if there is no service
        if(empty($request->service))
            return $this;
        # Set method
        $method = $request->service;
        # Stop if not calling a valid method
        if(!method_exists($this, $method))
            return $this;
        # Get attributes of the object
        $Reflector = new \ReflectionObject($this);
        $Method = $Reflector->getMethod($method);
        # See if you need to be logged in for it
        if($Method->isProtected()) {
            # If logged in, run the method
            if($this->nApp->isLoggedIn())
                $this->{$method}(...$this->inject($Method));
        }
        # If public, method can just be run
        elseif($Method->isPublic()) {
            $this->{$method}(...$this->inject($Method));
        }
        # Send back self for chaining
        return $this;
    }
    /**
     *	@description	Auto injection
     */
    private function inject($method): array
    {
        # Set parameter container
        $arr = [];
        # If there are no parameters, return empty parameters
        if($method->getNumberOfParameters() == 0)
            return $arr;
        # Go through parameters
        foreach($method->getParameters() as $obj) {
            # See if this is a class
            $class = $obj->getClass();
            # If is a class
            if(!empty($class) && !empty($class->getName())) {
                # Create instance and auto inject the construct
                $className = $class->getName();
                $RefClass = new \ReflectionClass($className);
                $constructObj = $RefClass->getMethod('__construct');
                $arr[] = new $className(...$this->inject($constructObj));
            }
            else {
                # Fetch the default values from the parameters
                $arr[] = $obj->getDefaultValue();
            }
        }
        # Send back the initiated parameters
        return $arr;
    }
}