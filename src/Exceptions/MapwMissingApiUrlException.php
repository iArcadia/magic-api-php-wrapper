<?php

namespace iArcadia\MagicApiPhpWrapper\Exceptions;

use Exception;

/**
 * MagicApiPhpWrapper exception throwed when the API base URL is unknown.
 *
 * @author Kévin Bibollet <bibollet.kevin@gmail.com>
 * @license MIT
 *
 * @extends Exception
 */
class MapwMissingApiUrlException extends Exception
{
    /**
     * Constructor method.
     *
     * @param string $message The displayed error message.
     * @param int $code The error code.
     * @param Exception|null $previous The previous exception.
     */
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}