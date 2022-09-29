<?php

namespace App;

class VendorPublish
{
    public static function publish(): void
    {
        $path      = getcwd();
        $installed = json_decode(file_get_contents($path . '/vendor/composer/installed.json'), true);
        $installed = $installed['packages'] ?? $installed;
        $config    = [];
        foreach ($installed as $package) {
            if (isset($package['extra']['max']['config'])) {
                $configProvider = $package['extra']['max']['config'];
                $configProvider = new $configProvider();
                if (method_exists($configProvider, '__invoke')) {
                    if (is_array($configItem = $configProvider())) {
                        $config = array_merge_recursive($config, $configItem);
                    }
                }
            }
        }

//        if (isset($config['publish'])) {
//            foreach ($config['publish'] as $publish) {
//                $destination = $publish['destination'];
//                if (!file_exists($destination)) {
//                    copy($publish['source'], $publish['destination']);
//                    echo('<info>[DEBUG]</info> Package `' . $publish['name'] . '` config file published.');
//                }
//                print 'File does not exist';
//            }
//        }

        $path .= '/runtime/app/';
        file_exists($path) || mkdir($path);
        file_put_contents($path . 'config.php', sprintf("<?php\n\nreturn %s;", var_export($config, true)));
    }
}
