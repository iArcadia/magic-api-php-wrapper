<?php

namespace iArcadia\MagicApiPhpWrapper\Requests;

use iArcadia\MagicApiPhpWrapper\MAPW;
use iArcadia\MagicApiPhpWrapper\Helpers\Config;
use iArcadia\MagicApiPhpWrapper\Exceptions\MapwCUrlException;

/**
 * HTTP requests builder with cURL.
 *
 * @author Kévin Bibollet <bibollet.kevin@gmail.com>
 * @license MIT
 */
class CURL extends Request
{
    /**
     * Send a HTML request thanks to cURL.
     *
     * @throws MapwCUrlException if the request returns a code different than 200 (OK).
     *
     * @param string $url
     *
     * @return string
     */
    public static function send(string $url): string
    {
        $curl = curl_init();
        $data = null;
        $responseCode = null;
        $key = Config::get('api.key');
        
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        /*
         * If an API key is given, sets in the HTTP header.
         */
        if ($key) { curl_setopt($curl, CURLOPT_HTTPHEADER, ["Authorization: {$key}"]); }
        
        $data = curl_exec($curl);
        $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        
        curl_close($curl);
        
        if ($responseCode !== 200)
        {
            throw new MapwCUrlException($responseCode);
        }
        
        return $data;
    }
}