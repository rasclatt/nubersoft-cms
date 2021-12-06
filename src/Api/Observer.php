<?php
namespace NubersoftCms\Api;

use Nubersoft\ {
    JWT\Controller as JWT,
    JWTFactory,
    Dto\Server,
    nObserver
};
use ReflectionObject;

abstract class Observer implements nObserver
{
    protected $request;
    /**
     *	@description	
     *	@param	
     */
    public function listen()
    {
        $args = func_get_args();
        $this->request = $args[1];
        $method = $args[0];
        $class = $args[2];
        
        if(!method_exists($this, $method))
            return ['error' => 'Invalid request', 'code' => 404];
        
        $Reflection = new ReflectionObject($this);
        $Method = $Reflection->getMethod($method);
        $methodDto = ucwords($method);
        $dto = "\\NubersoftCms\\Dto\\Api\\{$class}\\{$methodDto}Request";
        $execute = false;
        if($Method->isPublic()) {
           $execute = true; 
        }
        else {
            $token = str_ireplace('Bearer ', '', (new Server)->HTTP_AUTHORIZATION);
            $jwt = JWTFactory::get()->valid($token)['token']?? null;
            if(empty($jwt))
                return ['error' => 'Invalid request. Bad permissions.', 'code' => 403];
            $execute = password_verify(JWT::getJwtPath(), $jwt);
        }
        if($execute)
            return $this->{$method}((class_exists($dto))? new $dto($this->request) : $this->request);
        else
            return ['error' => 'Invalid request. Bad permissions.', 'code' => 403];
    }
}