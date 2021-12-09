<?php
namespace Nubersoft\Helper;

class Autoloader
{
    /**
     *	@description	Adds an autoload register
     *	@param	$path   [string]    Root path where namespace/class lives
     *  @param  $regex   [string|null]   Used to replace a namespace value
     *  @param  $source_folder   [string|null]   Used to replace the above namespace value with the root source folder
     */
    public static function create(\NubersoftCms\Dto\Helper\Autoload\CreateRequest $request)
    {
        self::createDynamic(function ($class) use ($request) {
            $inc = $request->path . DS . str_replace('\\', DS, ((!empty($request->regex))? preg_replace($request->regex, $request->source_folder, $class) : $class)) . '.php';
            if (is_file($inc))
                include($inc);
        });
    }
    /**
     *	@description	Alias for spl_autoload_register()
     */
    public static function createDynamic(callable $func)
    {
        spl_autoload_register($func);
    }
}