<?php

namespace App\Helpers;

use Noodlehaus\Config;

/**
 * Class ConfigWorker
 * @package App\Helpers
 */
class ConfigWorker
{
    /**
     * @var array
     */
    protected static $config = [];

    /**
     * @var \stdClass
     */
    protected static $folders;


    /**
     * Initialization method and return config array
     * @param array $arConfig
     * @param bool|false $recreateCache
     * @return array
     */
    public static function init($arConfig = [], $recreateCache = false)
    {
        self::loadEnvFiles($arConfig);

        if (self::$config == [])
            self::cacheConfig($recreateCache);

        return self::$config;
    }

    /**
     * Load configuration files
     * @params arConfig array
     * @return void|null
     */
    public static function loadEnvFiles($arConfig = [])
    {
        if (is_object(self::$folders))
            return;

        $arDefault = [
            "enviroment" => "local",
            "configFolderName" => "config",
            "compileFolderName" => "config",
            "blockCacheFile" => ".blockCache"
        ];

        $arConfig = array_merge($arDefault, $arConfig);

        self::$folders = new \stdClass();

        self::$folders->environment = $arConfig["enviroment"];

        if (is_file(ROOT_PATH . '.env')) {
            self::$folders->environment = file_get_contents(ROOT_PATH . '.env');
        }

        self::$folders->baseConfigPath = APP_PATH . $arConfig["configFolderName"] . '/';
        self::$folders->realConfigPath = self::$folders->baseConfigPath . self::$folders->environment . "/";

        if (!is_dir(self::$folders->realConfigPath)) {
            self::$folders->realConfigPath = self::$folders->baseConfigPath . $arConfig["enviroment"] . "/";
        }

        self::$folders->cacheConfigPath = CACHE_PATH . $arConfig["compileFolderName"] . "/";
        self::$folders->cacheConfigFile = self::$folders->cacheConfigPath . self::$folders->environment . ".php";
        self::$folders->blockConfigCache = is_file(self::$folders->cacheConfigPath . $arConfig["blockCacheFile"]);
    }

    /**
     * @param bool|false $reCreate
     */
    protected static function cacheConfig($reCreate = false)
    {
        if ($reCreate || self::$folders->blockConfigCache || !is_file(self::$folders->cacheConfigFile)) {
            self::$config = new Config(self::$folders->realConfigPath);
            if (!self::$folders->blockConfigCache) {
                self::makeCacheConfig(self::$config->all());
            }
        } else {
            self::$config = new Config(self::$folders->cacheConfigFile);
        }
    }

    /**
     * @param $allConfig
     */
    protected static function makeCacheConfig($allConfig)
    {
        $strData = var_export($allConfig, true);
        $content = sprintf('<?php ' . PHP_EOL . PHP_EOL . 'return %s;', $strData);
        if (!is_dir(self::$folders->cacheConfigPath)) {
            mkdir(self::$folders->cacheConfigPath);
        }
        file_put_contents(self::$folders->cacheConfigFile, $content);
    }

    /**
     * Get loaded config
     * @return array
     */
    public static function getConfig()
    {
        return self::$config;
    }

    /**
     * Clear Env variable
     */
    public static function clearEnvFiles()
    {
        self::$folders = null;
    }

    /**
     * @return \stdClass
     */
    public static function getEnvFiles()
    {
        return self::$folders;
    }
}