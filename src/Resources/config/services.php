<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Danilovl\HashidsBundle\Services\HashidsService;
use Danilovl\HashidsBundle\ParamConverter\HashidsParamConverter;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('danilovl.hashids', HashidsService::class)
        ->args([
            param('danilovl.hashids.salt'),
            param('danilovl.hashids.min_hash_length'),
            param('danilovl.hashids.alphabet')
        ])
        ->autowire()
        ->public()
        ->alias(HashidsService::class, 'danilovl.hashids');

    $container->services()
        ->set('danilovl.hashids_param_converter', HashidsParamConverter::class)
        ->arg('$continueNextConverter', param('danilovl.hashids.continue_next_converter'))
        ->autowire()
        ->public()
        ->tag('request.param_converter', ['converter' => 'danilovl.hashids_param_converter', 'priority' => 1])
        ->alias(HashidsParamConverter::class, 'danilovl.hashids_param_converter');
};