<?php

namespace iArcadia\MagicApiPhpWrapper\Cache;

use iArcadia\MagicApiPhpWrapper\Cache\File;

use iArcadia\MagicApiPhpWrapper\Helpers\Config;

/**
 * Wraps all cache system classes.
 *
 * @author Kévin Bibollet <bibollet.kevin@gmail.com>
 * @license MIT
 */
class Cache
{
    const CACHE_CLASSES =
    [
        'file' => 'File',
    ];
    
    /**
     * Gets the class of the used cache system.
     *
     * @return string
     */
    protected static function getSystemClass(): string
    {
        $system = Config::get('cache.system');
        
        return 'iArcadia\\MagicApiPhpWrapper\\Cache\\' . self::CACHE_CLASSES[$system];
    }
    
    /**
     * Gets content of already cached data.
     *
     * @param string $url
     *
     * @return object|array|null
     */
    public static function get(string $url)
    {
        $class = self::getSystemClass();
        
        return $class::get($url);
    }
    
    /**
     * Caches data.
     *
     * @param string $url
     * @param string $data
     *
     * @return bool
     */
    public static function cache(string $url, string &$data): bool
    {
        $class = self::getSystemClass();
        
        return $class::cache($url, $data);
    }
    
    /**
     * Clears the cache directory.
     */
    public static function clear()
    {
        $class = self::getSystemClass();
        
        return $class::clear();
    }
}