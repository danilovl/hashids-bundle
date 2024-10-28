<?php declare(strict_types=1);

namespace Danilovl\HashidsBundle\Services;

use Danilovl\HashidsBundle\Interfaces\HashidsServiceInterface;
use Danilovl\HashidsBundle\Tests\HashidsServiceFactory;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class HashidsServiceTest extends TestCase
{
    private HashidsServiceInterface $hashidsService;

    protected function setUp(): void
    {
        $this->hashidsService = HashidsServiceFactory::getHashidsService();
    }

    #[DataProvider('encodeData')]
    public function testEncode(mixed $number, string $expectedValue): void
    {
        $value = $this->hashidsService->encode($number);

        $this->assertEquals($expectedValue, $value);
    }

    #[DataProvider('decodeData')]
    public function testDecode(string $hash, array $expectedValue): void
    {
        $value = $this->hashidsService->decode($hash);

        $this->assertEquals($expectedValue, $value);
    }

    #[DataProvider('encodeHexData')]
    public function testEncodeHex(string $str, string $expectedValue): void
    {
        $value = $this->hashidsService->encodeHex($str);

        $this->assertEquals($expectedValue, $value);
    }

    #[DataProvider('decodeHexData')]
    public function testDecodeHex(string $hash, string $expectedValue): void
    {
        $value = $this->hashidsService->decodeHex($hash);

        $this->assertEquals($expectedValue, $value);
    }

    public static function encodeData(): Generator
    {
        yield [1, 'JjzrKkrqKQ'];
        yield [283, 'gBAd03KoG2'];
        yield [93_835, '1Gr951Oyrl'];
        yield [[333, 7_777], '4dVP6TQxLN'];
        yield [[333, 48_932, 942_380], '0m1twZ1SvV2M'];
    }

    public static function decodeData(): Generator
    {
        yield ['JjzrKkrqKQ', [1]];
        yield ['gBAd03KoG2', [283]];
        yield ['1Gr951Oyrl', [93_835]];
        yield ['4dVP6TQxLN', [333, 7_777]];
        yield ['0m1twZ1SvV2M', [333, 48_932, 942_380]];
    }

    public static function encodeHexData(): Generator
    {
        yield ['8118f5c6a', 'r69OalwAgd'];
        yield ['4c7b6657981d57a', 'KXaAbPxxR6hLej'];
        yield ['64', 'AzBd3gqdl2'];
    }

    public static function decodeHexData(): Generator
    {
        yield ['r69OalwAgd', '8118f5c6a'];
        yield ['KXaAbPxxR6hLej', '4c7b6657981d57a'];
        yield ['AzBd3gqdl2', '64'];
    }
}
