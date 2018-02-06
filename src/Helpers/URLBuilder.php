<?php

namespace iArcadia\MagicApiPhpWrapper\Helpers;

use iArcadia\MagicApiPhpWrapper\MAPW;
use iArcadia\MagicApiPhpWrapper\Helpers\Config;

use iArcadia\MagicApiPhpWrapper\Exceptions\MapwMissingApiUrlException;
use iArcadia\MagicApiPhpWrapper\Errors\ErrorMessage;
use TypeError;

/**
 * Builds API URLs.
 *
 * @author Kévin Bibollet <bibollet.kevin@gmail.com>
 * @license MIT
 */
class URLBuilder
{
    
    /**
     * Builds the URL for requests.
     * e.g. "http://www.mysite.com/api/people?country=France&city=Paris"
     *
     * @param string? $endpoint
     * @param array? $params
     *
     * @throws MapwMissingApiUrlException When no API base url has been given.
     *
     * @return string
     */
    public static function build(string $endpoint = null, array $params = null): string
    {
        $url = null;
        
        if (!Config::get('api.base_url'))
        {
            throw new MapwMissingApiUrlException('No API URL has been given');
        }
        
        $base = trim(Config::get('api.base_url'), '/');
        $version = trim(Config::get('api.version'), '/');
        if ($endpoint) { $endpoint = trim($endpoint, '/'); }
        
        if ($version && $endpoint) { $url = join('/', [$base, Config::get('api.version'), $endpoint]); }
        else if ($version) { $url = join('/', [$base, Config::get('api.version')]); }
        else if ($endpoint) { $url = join('/', [$base, $endpoint]); }
        else { $url = join('/', [$base]); }
        
        if ($params)
        {
            $url .= self::parameters($params);
        }
        
        return $url;
    }
    
    /**
     * Builds parameter string for adding to an URL.
     * e.g. "?country=France&city=Paris"
     *
     * @param array $params
     *
     * @return string
     */
    public static function parameters(array $params): string
    {
        $string = '?';
            
        $i = 0;
        foreach ($params as $param => $value)
        {
            if ($i > 0) { $string .= '&'; }

            $string .= "$param=$value";

            $i++;
        }
        
        return $string;
    }
    
    /**
     * Checks if the passed URL is complete or not.
     *
     * @param string $url
     *
     * @return bool
     */
    public static function isComplete(string $url): bool
    {
        if (!Config::get('api.base_url'))
        {
            throw new MapwMissingApiUrlException('No API URL has been given');
        }
        
        $apiURL = str_replace(['/', '.'], ['\/', '\.'], Config::get('api.base_url') . Config::get('api.version') . '/');
        
        return preg_match("/^{$apiURL}/", $url);
    }
    
    /**
     * Checks if the argument is an URL or not.
     *
     * @param string $arg
     *
     * @return bool
     */
    public static function isURL(string $arg = null): bool
    {
        return ($arg) ? preg_match("/https?:\/\//", $arg) : false;
    }
}