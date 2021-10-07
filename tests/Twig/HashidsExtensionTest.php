<?php declare(strict_types=1);

namespace Danilovl\ApplyFilterTwigExtensionBundle\Tests\Twig;

use Danilovl\HashidsBundle\Tests\HashidsServiceFactory;
use Danilovl\HashidsBundle\Twig\HashidsExtension;
use Generator;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class HashidsExtensionTest extends TestCase
{
    private Environment $twig;

    public function setUp(): void
    {
        $this->twig = new Environment(new FilesystemLoader, [
            'cache' => __DIR__ . '/../../var/cache/twig-test',
        ]);

        $hashidsExtension = new HashidsExtension(HashidsServiceFactory::getHashidsService());
        $this->twig->addExtension($hashidsExtension);
    }

    /**
     * @dataProvider filtersProvider
     */
    public function testFilters(string $template, string $result): void
    {
        $output = $this->twig->createTemplate($template)->render();

        $this->assertEquals($output, $result);
    }

    public function filtersProvider(): Generator
    {
        yield ["{{ 1 | hashids_encode }}", 'JjzrKkrqKQ'];
        yield ["{{ 283 | hashids_encode }}", 'gBAd03KoG2'];
        yield ["{{ [333, 7777] | hashids_encode }}", '4dVP6TQxLN'];
        yield ["{{ [333, 48932, 942380] | hashids_encode }}", '0m1twZ1SvV2M'];
        yield ["{{ 'JjzrKkrqKQ' | hashids_decode | join(', ') }}", '1'];
        yield ["{{ 'gBAd03KoG2'| hashids_decode | join(', ') }}", '283'];
        yield ["{{ '1Gr951Oyrl'| hashids_decode | join(', ') }}", '93835'];
        yield ["{{ '4dVP6TQxLN'| hashids_decode | join(', ') }}", '333, 7777'];
        yield ["{{ '0m1twZ1SvV2M'| hashids_decode | join(', ') }}", '333, 48932, 942380'];
    }
}
