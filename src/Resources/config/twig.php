<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Danilovl\HashidsBundle\Twig\HashidsExtension;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('danilovl.twig.hashids', HashidsExtension::class)
        ->autowire()
        ->private()
        ->tag('twig.extension')
        ->alias(HashidsExtension::class, 'danilovl.twig.hashids');
};