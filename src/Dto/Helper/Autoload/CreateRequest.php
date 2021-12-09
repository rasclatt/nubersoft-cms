<?php
namespace NubersoftCms\Dto\Helper\Autoload;

class CreateRequest extends \SmartDto\Dto
{
    public string $path;
    public ?string $regex;
    public ?string $source_folder;
}