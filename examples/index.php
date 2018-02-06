<?php

/*
 * Autoloading all MagicApiPhpWrapper files.
 */
require_once('vendor/autoload.php');

require_once('../src/MAPW.php');
require_once('../src/ProceduralMAPW.php');

require_once('../src/Requests/Request.php');
require_once('../src/Requests/CURL.php');
require_once('../src/Requests/File.php');

require_once('../src/Cache/Cache.php');
require_once('../src/Cache/File.php');

require_once('../src/Helpers/URLBuilder.php');
require_once('../src/Helpers/Config.php');

require_once('../src/Errors/ErrorMessage.php');
require_once('../src/Exceptions/MapwException.php');
require_once('../src/Exceptions/MapwMissingApiUrlException.php');
require_once('../src/Exceptions/MapwApiException.php');
require_once('../src/Exceptions/MapwCUrlException.php');
/* End autoloading */

use iArcadia\MagicApiPhpWrapper\MAPW;
use iArcadia\MagicApiPhpWrapper\Cache\Cache;

// "MAPW" stands for "Magic API PHP Wrapper"... of course!

// --- POO-ORIENTED STYLE
// First, create your MAPW object with your configuration.
$api = new MAPW(
[
    'api.base_url' => 'https://api.ipify.org/'
]);

// Second, get your data!
// Here, data will be from "https://api.ipify.org/?format=json":
$data = $api->get(null, ['format' => 'json']);
d($data);

// ---
// Clear variables and cache for next example.
unset($api);
unset($data);
Cache::clear();
// ---

// --- PROCEDURAL STYLE
// First, initialize the MAPW object with your configuration.
// In background, it will create a global variable with the newly created object : $GLOBALS['MAPW_INSTANCE'] = new MAPW(...)
mapw_initialize(
[
    'api.base_url' => 'https://api.ipify.org/'
]);

// Second, get your data!
// Here, data will be from "https://api.ipify.org/?format=json":
$data = mapw_get(null, ['format' => 'json']);
d($data);