<?php

namespace iArcadia\MagicApiPhpWrapper\Cache;

use iArcadia\MagicApiPhpWrapper\RocketLeagueStats;
use iArcadia\MagicApiPhpWrapper\Helpers\Config;
use iArcadia\MagicApiPhpWrapper\Requests\Request;
use Symfony\Component\Yaml\Yaml;
use stdClass;

/**
 * All file caching related methods.
 *
 * @author Kévin Bibollet <bibollet.kevin@gmail.com>
 * @license MIT
 */
class File
{
    /**
     * Gets a MD5-hashed name from an URL.
     *
     * @param string $url
     *
     * @return string
     */
    protected static function getFileName(string $url): string
    {
        return md5($url);
    }
    
    /**
     * Gets the storage directory for the caching system.
     *
     * @return string
     */
    protected static function getDirectory(): string
    {
        $path = trim(Config::get('cache.file.storage_path'), '/');
        
        if (!file_exists($path)) { mkdir($path); }
        if (!file_exists("{$path}/")) { mkdir("{$path}/"); }
        
        return "{$path}/";
    }
    
    /**
     * Gets the storage image directory for the caching system.
     *
     * @return string
     */
    protected static function getImageDirectory(): string
    {
        $path = trim(Config::get('cache.file.storage_path'), '/');
        
        if (!file_exists($path)) { mkdir($path); }
        if (!file_exists("{$path}/images")) { mkdir("{$path}/images"); }
        
        return "{$path}/images";
    }
    
    /**
     * Gets the relative path of the file.
     *
     * @param string $url
     *
     * @return string
     */
    protected static function getFilePath(string $url): string
    {
        $fileName = self::getFileName($url);
        $dir = self::getDirectory();
        $ext = Config::get('cache.file.extension');
        
        return "{$dir}/{$fileName}.{$ext}";
    }
    
    /**
     * Gets the relative path of the image file.
     *
     * @param string $imageURL
     *
     * @return string
     */
    protected static function getImagePath(string $imageURL): string
    {
        $fileName = self::getFileName($imageURL);
        $dir = self::getImageDirectory();
        
        return "{$dir}/{$fileName}.png";
    }
    
    /**
     * Gets content of already cached data.
     *
     * @param string $url
     * @param bool $force
     *
     * @return object|array|null
     */
    public static function get(string $url, bool $force = false)
    {
        $file = self::getFilePath($url);
        $data = null;
        
        if (file_exists($file))
        {
            if (filemtime($file) + Config::get('cache.expiration_time') < time()
                || $force)
            {
                unlink($file);
            }
            else
            {
                $data = file_get_contents($file);
                
                switch (Config::get('cache.file.format'))
                {
                    case 'json': $data = self::parseJson($data); break;
                    case 'yaml': $data = self::parseYaml($data); break;
                }
            }
        }
        
        return $data;
    }
    
    /**
     * Creates a file which will store given data.
     *
     * @param string $url
     * @param string $data
     *
     * @return bool
     */
    public static function cache(string $url, string &$data): bool
    {
        $file = self::getFilePath($url);
        
        self::cacheImages($url, $data);
        
        switch (Config::get('cache.file.format'))
        {
            case 'json': $data = self::cacheJson($data); break;
            case 'yaml': $data = self::cacheYaml($data); break;
        }
        
        return file_put_contents($file, $data);
    }
    
    /**
     * Clears the cache directory.
     */
    public static function clear()
    {
        $path = Config::get('cache.file.storage_path');
        
        if (!file_exists($path)) { mkdir($path); }
        else
        {
            self::recursiveClear($path);
            mkdir($path);
        }
    }
    
    /**
     * Navigates through children directories to delete files from a starting point.
     *
     * @param string $start
     *
     * @return bool
     */
    protected static function recursiveClear(string $start): bool
    {
        $files = array_diff(scandir($start), array('.','..')); 
            
        foreach ($files as $file)
        { 
            (is_dir("$start/$file")) ? self::recursiveClear("$start/$file") : unlink("$start/$file"); 
        } 

        return rmdir($start); 
    }
    
    /**
     * Copies image files in cache.
     *
     * @param string $url
     * @param string $data
     */
    protected static function cacheImages(string $url, string &$data)
    {
        foreach (self::searchForImages($url, $data) as $surl)
        {
            $file = self::getImagePath($surl);
            $image = Request::send($surl);
            
            file_put_contents($file, $image);
        }
    }
    
    /**
     * Navigates through data to find image URLs and places them into an array.
     *
     * @param string $url
     * @param string|object|array $data
     *
     * @return array
     */
    protected static function searchForImages(string $url, &$data): array
    {
        $images = [];
        
        if (is_string($data)) { $data = json_decode($data); }
        
        if (!empty($data))
        {
            foreach($data as &$item)
            {
                if (is_object($item) || is_array($item))
                {
                    $images = array_merge($images, self::searchForImages($url, $item));
                }
                else if (preg_match('/.+\.(?:png)|(?:jpg)|(?:jpeg)/', $item))
                {
                    array_push($images, $item);
                    $item = self::getImagePath($item);
                }
            }
        }
        
        return $images;
    }
    
    /**
     * Converts JSON string to PHP object or array.
     *
     * @param string $data
     *
     * @return stdClass|array
     */
    protected static function parseJson(string $data)
    {
        return json_decode($data);
    }
    
    /**
     * Converts YAML string to PHP object.
     *
     * @param string $data
     *
     * @return stdClass
     */
    protected static function parseYaml(string $data): stdClass
    {
        return Yaml::parse($data);
    }
    
    /**
     * Converts data into JSON string.
     *
     * @param mixed $data
     *
     * @return string
     */
    protected static function cacheJson($data): string
    {
        if (!is_string($data)) { $data = json_encode($data); }
        
        return $data;
    }
    
    /**
     * Converts data into YAML string.
     *
     * @param mixed $data
     *
     * @return string
     */
    protected static function cacheYaml($data): string
    {
        if (is_string($data)) { $data = json_decode($data); }
        
        if (is_array($data)) { $data = Yaml::dump($data); }
        if (is_object($data)) { $data = Yaml::dump($data, 2, 4, Yaml::DUMP_OBJECT); }
        
        return $data;
    }
}