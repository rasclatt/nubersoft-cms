<?php
namespace NubersoftCms\Model;

use \Nubersoft\ {
    JWTFactory,
    JWT\Controller as JWT
};

class Api
{
    /**
     *	@description	
     *	@param	
     */
    public static function generateKey(array $body = null)
    {
        $def = ['token' => password_hash(JWT::getJwtTokenSecret(), PASSWORD_BCRYPT)];
        if(is_array($body))
            $def = array_merge($def, $body);

        return JWTFactory::get()->create($def);
    }
}
