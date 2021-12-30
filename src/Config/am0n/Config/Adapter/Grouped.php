<?php

declare(strict_types=1);

namespace am0n\Config\Adapter;

use am0n\Config\Config;
use am0n\Config\ConfigFactory;
use am0n\Config\ConfigInterface;
use am0n\Config\Exception;

use function is_object;
use function is_string;

/**
 * Exceptions thrown in Phalcon\Config will use this class
 */
class Grouped extends Config
{
    /**
     * Grouped constructor.
     *
     * @param array  $arrayConfig
     * @param string $defaultAdapter
     *
     * @throws Exception
     */
    public function __construct(array $arrayConfig, string $defaultAdapter = 'php')
    {
        parent::__construct([]);

        foreach ($arrayConfig as $configName) {
            $configInstance = $configName;

            // Set to default adapter if passed as string
            if (is_object($configName) && $configName instanceof ConfigInterface) {
                $this->merge($configInstance);

                continue;
            }

            if (false !== is_string($configName)) {
                if ('' === $defaultAdapter) {
                    $this->merge(
                        (new ConfigFactory())->load($configName)
                    );

                    continue;
                }

                $configInstance = [
                    'filePath' => $configName,
                    'adapter'  => $defaultAdapter,
                ];
            } elseif (true !== isset($configInstance['adapter'])) {
                $configInstance['adapter'] = $defaultAdapter;
            }

            if ('array' === $configInstance['adapter']) {
                if (true !== isset($configInstance['config'])) {
                    throw new Exception(
                        "To use 'array' adapter you have to specify " .
                        "the 'config' as an array."
                    );
                }

                $configArray    = $configInstance['config'];
                $configInstance = new Config($configArray);

                $this->merge($configInstance);

                continue;
            }

            $configInstance = (new ConfigFactory())->load($configInstance);

            $this->merge($configInstance);
        }
    }
}

?>