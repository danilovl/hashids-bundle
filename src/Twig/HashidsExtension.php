<?php declare(strict_types=1);

namespace Danilovl\HashidsBundle\Twig;

use Danilovl\HashidsBundle\Services\HashidsService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class HashidsExtension extends AbstractExtension
{
    public function __construct(private HashidsService $hashidsService)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('hashids_encode', [$this, 'encode']),
            new TwigFilter('hashids_decode', [$this, 'decode']),
        ];
    }

    public function encode(mixed $number): string
    {
        return $this->hashidsService->encode($number);
    }

    public function decode(mixed $hash): array
    {
        return $this->hashidsService->decode($hash);
    }
}
