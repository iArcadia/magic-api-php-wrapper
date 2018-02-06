<?php

namespace iArcadia\MagicApiPhpWrapper\Helpers;

use iArcadia\MagicApiPhpWrapper\Exceptions\ApiPhpWrapperFileException;

/**
 * Gathers all configuration related methods.
 *
 * @author Kévin Bibollet <bibollet.kevin@gmail.com>
 * @license MIT
 */
class Config
{
    /** @var array The default configuration. */
    const DEFAULT =
    [
        /* API related. */
        'api' =>
        [
            /* string Base url of the API. */
            'base_url' => null,
            
            /* string API key used by some APIs. */
            'key' => null,
            
            /* string API version. Not needed. */
            'version' => null,
        ],

        /* HTTP request related. */
        'request' =>
        [
            /*
             * string The used request system
             * Choices: curl, file
             */
            'system' => 'curl',
        ],

        /* Caching system related. */
        'cache' =>
        [
            /* boolean Set true for using the caching system. */
            'use' => true,
            
            /*
             * string The used caching system.
             * Choices: file
             */
            'system' => 'file',
            
            /* int The expiration time of cached files in ms. */
            'expiration_time' => 60 * 60 * 24 * 30,
            
            /* File caching system related. */
            'file' =>
            [
                /* string Where cached files will be stored. */
                'storage_path' => 'cache/magicapiphpwrapper',
                
                /*
                 * string In which format the received data will be.
                 * Choices: json, yaml
                 */
                'format' => 'json',
                
                /* string The extension of cached files. */
                'extension' => 'json',
            ],
        ],
    ];
    
    /**
     * Initializes MAPW configuration.
     *
     * @param array? $options
     *
     * @return array
     */
    public static function initialize(array $options = null): array
    {
        $configVarName = self::getConfigGlobalVarName();
        $GLOBALS[$configVarName] = self::DEFAULT;
        
        if ($options)
        {
            foreach ($options as $config => $value)
            {
                self::set($config, $value);
            }
        }
        
        return $GLOBALS[$configVarName];
    }
    
    /**
     * Gets a value from the configuration.
     *
     * Navigate through arrays with ".", "/", " " or "->".
     * Like "file.myarray" or "cache->file->ext".
     *
     * @param string? $config
     *
     * @return mixed
     */
    public static function get(string $config = null)
    {
        $configVarName = self::getConfigGlobalVarName();
        $result = $GLOBALS[$configVarName];
        
        if ($config)
        {
            $path = explode('.', $config);

            if (sizeof($path) === 1) { $path = explode('/', $config); }
            if (sizeof($path) === 1) { $path = explode(' ', $config); }
            if (sizeof($path) === 1) { $path = explode('->', $config); }

            $keys = $path;

            if (!empty($keys))
            {
                foreach ($keys as $key)
                {
                    if (isset($result[$key])) { $result = $result[$key]; }
                    else { $result = null; break; }
                }
            }
        }
        
        return $result;
    }
    
    /**
     * Sets a value from the configuration.
     *
     * Navigate through arrays with ".", "/", " " or "->".
     * Like "file.myarray" or "cache->file->ext".
     *
     * @param string $config
     * @param mixed $value
     *
     * @return array
     */
    public static function set(string $config, $value): array
    {
        $configVarName = self::getConfigGlobalVarName();
        $path = explode('.', $config);
        
        if (sizeof($path) === 1) { $path = explode('/', $config); }
        if (sizeof($path) === 1) { $path = explode(' ', $config); }
        if (sizeof($path) === 1) { $path = explode('->', $config); }
        
        $keys = $path;
        $e = "\$GLOBALS[$configVarName]";
        
        if (!empty($keys))
        {
            foreach ($keys as $key)
            {
                $e .= "['$key']";
            }
        }
        
        switch (gettype($value))
        {
            case 'string':
                eval($e .= " = '$value';");
                break;
                
            case 'NULL':
                eval($e .= " = null;");
                break;
                
            case 'boolean':
                if ($value === false) { eval($e .= " = false;"); }
                if ($value === true) { eval($e .= " = true;"); }
                break;
                
            default:
                eval($e .= " = $value;");
                break;
        }
        
        return $GLOBALS['MAPW_CONFIG'];
    }
    
    public static function getConfigGlobalVarName() { return 'MAPW_CONFIG'; }
    public static function getInstanceGlobalVarName() { return 'MAPW_INSTANCE'; }
}