<?php
namespace NubersoftCms\Dto\Model\Routing;

class GetInfoResponse extends \SmartDto\Dto
{
    public bool $ssl = false;
    public bool $ajax = false;
    public string $host = '';
    public string $subdomain = '';
    public string $tld = '';
    public string $domain = '';
    public string $path = '';
    public string $locale = '';
    public string $locale_lang = '';
    public ?array $query = null;
}