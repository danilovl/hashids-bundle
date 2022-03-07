<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Danilovl\HashidsBundle\Interfaces\HashidsServiceInterface;
use Danilovl\HashidsBundle\Service\HashidsService;
use Danilovl\HashidsBundle\ParamConverter\HashidsParamConverter;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set(HashidsService::class, HashidsService::class)
        ->args([
            param('danilovl.hashids.salt'),
            param('danilovl.hashids.min_hash_length'),
            param('danilovl.hashids.alphabet')
        ])
        ->autowire()
        ->public()
        ->alias(HashidsServiceInterface::class, HashidsService::class);

    $container->services()
        ->set(HashidsParamConverter::class, HashidsParamConverter::class)
        ->arg('$continueNextConverter', param('danilovl.hashids.continue_next_converter'))
        ->autowire()
        ->public()
        ->tag('request.param_converter', ['converter' => 'danilovl.hashids_param_converter', 'priority' => 1]);
};
