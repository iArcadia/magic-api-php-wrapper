<?php

use iArcadia\MagicApiPhpWrapper\MAPW;
use iArcadia\MagicApiPhpWrapper\Helpers\Config;

if (!function_exists('mapw_config'))
{
    function mapw_config(string $config)
    {
        return Config::get($config);
    }
}

if (!function_exists('mapw_config_set'))
{
    function mapw_config_set(string $config, $value)
    {
        return Config::set($config, $value);
    }
}

if (!function_exists('mapw_initiliaze'))
{
    function mapw_initialize(array $options)
    {
        $instanceGlobalVarName = Config::getInstanceGlobalVarName();
        return $GLOBALS[$instanceGlobalVarName] = new MAPW($options);
    }
}

if (!function_exists('mapw_get'))
{
    function mapw_get(string $endpoint = null, array $params = null)
    {
        $instanceGlobalVarName = Config::getInstanceGlobalVarName();
        $data = $GLOBALS[$instanceGlobalVarName]->get($endpoint, $params);
        
        return $data;
    }
}

if (!function_exists('mapw_destroy'))
{
    function mapw_destroy()
    {
        $instanceGlobalVarName = Config::getInstanceGlobalVarName();
        unset($GLOBALS[$instanceGlobalVarName]);
    }
}