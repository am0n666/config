<?php

declare(strict_types=1);

namespace am0n\Config\Adapter;

use am0n\Config\Config;

use function file_get_contents;

/**
 * Exceptions thrown in Phalcon\Config will use this class
 */
class Json extends Config
{
    /**
     * Json constructor.
     *
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        parent::__construct(
            json_decode(
                file_get_contents($filePath),
                true
            )
        );
    }
}

?>