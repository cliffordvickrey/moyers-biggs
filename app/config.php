<?php

declare(strict_types=1);

use CliffordVickrey\MoyersBiggs\Infrastructure\Config\ConfigProvider;

if (is_file('../data/config.cache')) {
    /** @noinspection PhpIncludeInspection */
    return require '../data/config.cache';
}

return call_user_func(function (): array {
    $localConfigFile = '../data/config.local.php';

    $localConfig = [];
    if (is_file($localConfigFile)) {
        $localConfig = require $localConfigFile;
    }

    $config = array_merge((new ConfigProvider())(), $localConfig);

    if (!($config['debug'] ?? false)) {
        $header = <<<PHP
        <?php

        declare(strict_types=1);

        return '%config%';
        PHP;

        file_put_contents('../data/config.cache', str_replace("'%config%'", var_export($config, true), $header));
    }

    return $config;
});
