<?php

declare(strict_types=1);

namespace am0n\Config;

interface ConfigInterface
{
    /**
     * @return string
     */
    public function getPathDelimiter(): string;

    /**
     * @param mixed $toMerge
     *
     * @return ConfigInterface
     */
    public function merge($toMerge): ConfigInterface;

    /**
     * @param string      $path
     * @param mixed|null  $defaultValue
     * @param string|null $delimiter
     *
     * @return mixed
     */
    public function path(string $path, $defaultValue = null, string $delimiter = null);

    /**
     * @param string|null $delimiter
     *
     * @return ConfigInterface
     */
    public function setPathDelimiter(string $delimiter = null): ConfigInterface;
}

?>