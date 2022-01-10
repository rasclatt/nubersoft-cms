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
            $SERVER = new Server;
            $token = trim(str_ireplace('Bearer ', '', $SERVER->HTTP_AUTHORIZATION));
            $jwt = null;
            try {
                $jwt = JWTFactory::get()->get($token)['token']?? null;
            }
            catch (\Exception $e) {

            }
            if(empty($jwt))
                return ['error' => 'Invalid request. Bad permissions.', 'code' => 403];

            $execute = password_verify(JWT::getJwtTokenSecret(), $jwt);
        }
        if($execute)
            return $this->{$method}((class_exists($dto))? new $dto($this->request) : $this->request);
        else
            return ['error' => 'Invalid request. Bad permissions.', 'code' => 403];
    }
}
