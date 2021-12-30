<?php

declare(strict_types=1);

namespace am0n\Config;

use am0n\Config\Adapter\Grouped;
use am0n\Config\Adapter\Json;
use am0n\Config\Adapter\Php;

use function is_array;
use function is_object;
use function is_string;
use function lcfirst;
use function pathinfo;
use function strtolower;

class ConfigFactory
{

    /**
     * ConfigFactory constructor.
     *
     * @param array $services
     */
    public function __construct(array $services = [])
    {
//        $this->init($services);
    }

    /**
     * Load a config to create a new instance
     *
     * @param string|array|Config $config = [
     *                                    'adapter'   => 'ini',
     *                                    'filePath'  => 'config.ini',
     *                                    'mode'      => null,
     *                                    'callbacks' => null
     *                                    ]
     *
     * @return ConfigInterface
     * @throws Exception
     */
    public function load($config): ConfigInterface
    {
        $configArray = $this->parseConfig($config);

        $adapter  = strtolower($configArray['adapter']);
        $filePath = $configArray['filePath'];

        if (true === empty(pathinfo($filePath, PATHINFO_EXTENSION))) {
            $filePath .= '.' . lcfirst($adapter);
        }

        return $this->newInstance($adapter, $filePath);
    }

    /**
     * Returns a new Config instance
     *
     * @param string     $name
     * @param string     $fileName
     * @param mixed|null $params
     *
     * @return ConfigInterface
     * @throws Exception
     */
    public function newInstance(
        string $name,
        string $fileName,
        $params = null
    ): ConfigInterface {
        $definition = $this->getService($name);

        switch ($definition) {
            case Grouped::class:
                $adapter = null === $params ? 'php' : $params;
                return new $definition($fileName, $adapter);
        }

        return new $definition($fileName);
    }

    /**
     * @return string
     */
    protected function getExceptionClass(): string
    {
        return Exception::class;
    }

    /**
     * @return array
     */
    protected function getServices(): array
    {
        return [
            'grouped' => Grouped::class,
            'json'    => Json::class,
            'php'     => Php::class,
        ];
    }

    /**
     * @param mixed $config
     *
     * @return array
     * @throws Exception
     */
    protected function parseConfig($config): array
    {
        if (false !== is_string($config)) {
            $oldConfig = $config;
            $extension = pathinfo($config, PATHINFO_EXTENSION);

            if (true == empty($extension)) {
                throw new Exception(
                    'You need to provide the extension in the file path'
                );
            }

            $config = [
                'adapter'  => $extension,
                'filePath' => $oldConfig
            ];
        }

        if (true === is_object($config) && $config instanceof ConfigInterface) {
            $config = $config->toArray();
        }

        if (true !== is_array($config)) {
            throw new Exception(
                'Config must be array or Phalcon\\Config\\Config object'
            );
        }

        $this->checkConfigArray($config);

        return $config;
    }

    /**
     * @param array $config
     *
     * @throws Exception
     */
    private function checkConfigArray(array $config): void
    {
        if (true !== isset($config['filePath'])) {
            throw new Exception(
                "You must provide 'filePath' option in factory config parameter."
            );
        }

        if (true !== isset($config['adapter'])) {
            throw new Exception(
                "You must provide 'adapter' option in factory config parameter."
            );
        }
    }
}

?>