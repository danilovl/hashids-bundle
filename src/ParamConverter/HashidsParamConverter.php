<?php declare(strict_types=1);

namespace Danilovl\HashidsBundle\ParamConverter;

use Danilovl\HashidsBundle\Interfaces\HashidsServiceInterface;
use Doctrine\ORM\Mapping\{
    Id,
    Entity
};
use ReflectionClass;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Throwable;

class HashidsParamConverter implements ValueResolverInterface
{
    public function __construct(
        private readonly bool $enable,
        private readonly HashidsServiceInterface $hashidsService
    ) {}

    public function resolve(Request $request, ArgumentMetadata $argument): array
    {
        if (!$this->enable) {
            return [];
        }

        if (is_object($request->attributes->get($argument->getName()))) {
            return [];
        }

        $mappingAttributes = [];

        try {
            $className = $argument->getType();
            $reflection = (new ReflectionClass($className));
            $attributes = $reflection->getAttributes(Entity::class);

            if (count($attributes) !== 0) {
                foreach ($reflection->getProperties() as $reflectionProperty) {
                    $reflectionPropertyAttributes = $reflectionProperty->getAttributes(Id::class);
                    if (count($reflectionPropertyAttributes) !== 0) {
                        $mappingAttributes[] = $reflectionProperty->getName();
                    }
                }
            }
        } catch (Throwable) {}

        $mapEntity = $argument->getAttributes(MapEntity::class, ArgumentMetadata::IS_INSTANCEOF);
        $mapEntity = $mapEntity[0] ?? null;

        if ($mapEntity !== null) {
            $mappingAttributes = array_merge($mappingAttributes, array_keys($mapEntity->mapping));
        }

        if (!class_exists($argument->getName())) {
            $mappingAttributes[] = $argument->getName();
        }

        $this->setHashid($request, $mappingAttributes);

        return [];
    }

    private function setHashid(Request $request, array $mappingIds): void
    {
        foreach ($mappingIds as $mappingId) {
            $hash = $request->attributes->get($mappingId);
            if ($hash === null) {
                continue;
            }

            $hash = (string) $hash;
            $hashids = $this->hashidsService->decode($hash);

            if ($this->hasHashidDecoded($hashids)) {
                $request->attributes->set($mappingId, current($hashids));
            }
        }
    }

    private function hasHashidDecoded(mixed $hashids): bool
    {
        return $hashids && is_iterable($hashids);
    }
}
