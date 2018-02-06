<?php

namespace iArcadia\MagicApiPhpWrapper\Requests;

use iArcadia\MagicApiPhpWrapper\MAPW;
use iArcadia\MagicApiPhpWrapper\Helpers\Config;

/**
 * HTTP requests builder with PHP default methods.
 *
 * @author Kévin Bibollet <bibollet.kevin@gmail.com>
 * @license MIT
 */
class File extends Request
{
    /**
     * Sends a HTML request and gets a data response.
     *
     * @param string $url
     *
     * @return string
     */
    public static function send(string $url): string
    {
        $data = null;
        $key = Config::get('api.key');
        
        /*
         * If an API key is given, sets it in the HTTP header.
         */
        if ($key)
        {
            $options =
            [
                'header' => "Authorization: {$key}",
            ];

            $context = stream_context_create(['http' => $options]);

            $data = file_get_contents($url, false, $context);
        }
        else
        {
            $data = file_get_contents($url);
        }
        
        return $data;
    }
}