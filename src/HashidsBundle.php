<?php declare(strict_types=1);

namespace Danilovl\HashidsBundle;

use Danilovl\HashidsBundle\DependencyInjection\HashidsExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class HashidsBundle extends Bundle
{
    public function getContainerExtension(): HashidsExtension
    {
        return new HashidsExtension;
    }
}
