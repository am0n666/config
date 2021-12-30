<?php

declare(strict_types=1);

namespace am0n\Config\Adapter;

use am0n\Config\Config;

/**
 * Exceptions thrown in Phalcon\Config will use this class
 */
class Php extends Config
{
    /**
     * Php constructor.
     *
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        parent::__construct(
            require $filePath
        );
    }
}

?>