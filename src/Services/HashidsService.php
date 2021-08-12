<?php declare(strict_types=1);

namespace Danilovl\HashidsBundle\Services;

use Danilovl\HashidsBundle\Interfaces\HashidsServiceInterface;
use Hashids\Hashids;
use Symfony\Component\DependencyInjection\ContainerInterface;

class HashidsService extends Hashids implements HashidsServiceInterface
{
}
