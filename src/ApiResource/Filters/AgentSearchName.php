<?php

namespace App\ApiResource\Filters;

use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\PropertyInfo\Type;

class AgentSearchName extends AbstractFilter
{

    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        if (
            !$this->isPropertyEnabled($property, $resourceClass) ||
            !$this->isPropertyMapped($property, $resourceClass)
        ) {
            return;
        }

        $parameterName = $queryNameGenerator->generateParameterName($property);

        $queryBuilder->Where(sprintf('o.nom LIKE :%s', $parameterName))
                    ->orWhere(sprintf('o.postnom LIKE :%s', $parameterName))
                    ->orWhere(sprintf('o.prenom LIKE :%s', $parameterName))
                    ->setParameter($parameterName, "%" .$value. "%");
    }

    public function getDescription(string $resourceClass): array
    {
        if (!$this->properties) {
            return [];
        }

        $description = [];

        foreach ($this->properties as $property => $strategy) {
            $description["$property"] = [
                'property' => $property,
                'type' => Type::BUILTIN_TYPE_STRING,
                'required' => false,
                'description' => 'Filtre de recherche des agents!',
                'openapi' => [
                    'allowEmptyValue' => true,
                ]
            ];
        }

        return $description;
    }
}