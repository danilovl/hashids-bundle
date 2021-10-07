<?php declare(strict_types=1);

namespace Danilovl\HashidsBundle\Tests;

use Danilovl\HashidsBundle\Interfaces\HashidsServiceInterface;
use Danilovl\HashidsBundle\Services\HashidsService;

class HashidsServiceFactory
{
    public static function getHashidsService(): HashidsServiceInterface
    {
        return new HashidsService(
            salt: "ahi3fk7",
            minHashLength: 10,
            alphabet: "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"
        );
    }
}
