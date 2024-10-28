<?php declare(strict_types=1);

namespace Danilovl\HashidsBundle\Twig;

use Danilovl\HashidsBundle\Interfaces\HashidsServiceInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class HashidsExtension extends AbstractExtension
{
    public function __construct(private readonly HashidsServiceInterface $hashidsService) {}

    public function getFilters(): array
    {
        return [
            new TwigFilter('hashids_encode', [$this, 'encode']),
            new TwigFilter('hashids_decode', [$this, 'decode'])
        ];
    }

    public function encode(mixed $number): string
    {
        return $this->hashidsService->encode($number);
    }

    public function decode(string $hash): array
    {
        return $this->hashidsService->decode($hash);
    }
}
