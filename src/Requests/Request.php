<?php

namespace iArcadia\MagicApiPhpWrapper\Requests;

use iArcadia\MagicApiPhpWrapper\Requests\CURL;
use iArcadia\MagicApiPhpWrapper\Requests\File;

use iArcadia\MagicApiPhpWrapper\Helpers\Config;

/**
 * Wraps all request system classes.
 *
 * @author Kévin Bibollet <bibollet.kevin@gmail.com>
 * @license MIT
 */
class Request
{
    const REQUEST_CLASSES =
    [
        'curl' => 'CURL',
        'file' => 'File',
    ];
    
    /**
     * Gets the class of the used request system.
     *
     * @return string
     */
    protected static function getSystemClass(): string
    {
        $system = Config::get('request.system');
        return 'iArcadia\\MagicApiPhpWrapper\\Requests\\' . self::REQUEST_CLASSES[$system];
    }
    
    /**
     * Sends a HTML request and gets a data response.
     *
     * @param string $url
     *
     * @return string
     */
    public static function send(string $url): string
    {
        $class = self::getSystemClass();
        
        return $class::send($url);
    }
}