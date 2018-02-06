<?php

namespace iArcadia\MagicApiPhpWrapper;

use iArcadia\MagicApiPhpWrapper\Requests\Request;
use iArcadia\MagicApiPhpWrapper\Cache\Cache;

use iArcadia\MagicApiPhpWrapper\Helpers\URLBuilder;
use iArcadia\MagicApiPhpWrapper\Helpers\Config;

/**
 * Constructs URL and HTTP Requests in order to get data.
 *
 * @author Kévin Bibollet <bibollet.kevin@gmail.com>
 * @license MIT
 */
class MAPW
{
    /**
     * Constructor method.
     *
     * @param array|null $options Properties to set at instance creation.
     */
    public function __construct(array $options = null)
    {
        Config::initialize($options);
    }
    
    /**
     * Gets the asked data from the API server.
     *
     * @param string? $endpoint
     * @param array? $params
     *
     * @return object|array
     */
    public function get(string $endpoint = null, array $params = null)
    {
        $response = null;
        $url = null;
        
        if (!URLBuilder::isURL($endpoint)) { $url = URLBuilder::build($endpoint, $params); }
        else { $url = $endpoint; }
        
        if (Config::get('cache.use'))
        {
            $response = Cache::get($url);
            
            if (!$response)
            {
                $response = Request::send($url);
                Cache::cache($url, $response);
            }
        }
        else
        {
            $response = Request::send($url);
        }
        
        $response = (is_string($response)) ? json_decode($response) : $response;
        
        return $response;
    }
}