<?php

namespace App\Filter;

use ApiPlatform\...\AbstractContextAwareFilter;
use ApiPlatform\...\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;

final class DogArchivedStatusFilter
extends AbstractContextAwareFilter
{
    public const FILTER_NAME = 'archived_status';

    protected function filterProperty(
        string $property,
        $value,
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null
    ) {
        if (
            self::FILTER_NAME !== $property
        ) {
            return;
        }

        $requestedArchivedStatus = $this->normalizeValue($value);
        if (null === $requestedArchivedStatus) {
            return;
        }

        $joinAliasName = $queryNameGenerator->generateJoinAlias('user');
        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->innerJoin(
            sprintf('%s.company', $rootAlias),
            $joinAliasName
        );

        if (false === $requestedArchivedStatus) {
            $queryBuilder
                ->andWhere(
                    sprintf('%s.archivedDate IS NULL', $rootAlias)
                )
                ->andWhere(
                    sprintf('%s.archivedDate IS NULL', $joinAliasName)
                );
        }

        if (true === $requestedArchivedStatus) {
            $queryBuilder->andWhere(
                sprintf(
                    '%s.archivedDate IS NOT NULL 
                    OR %s.archivedDate IS NOT NULL',
                    $joinAliasName,
                    $rootAlias
                )
            );
        }
    }

    public function getDescription(string $resourceClass): array
    {
        if (!$this->properties) {
            return [];
        }

        $description = [];
        foreach ($this->properties as $property => $strategy) {
            $description[self::FILTER_NAME] = [
                'property' => 'null',
                'type' => 'bool',
                'required' => false,
                'swagger' => [
                    'description' => 'Filter archived status',
                    'name' => self::FILTER_NAME,
                    'type' => 'boolean',
                ],
            ];
        }

        return $description;
    }

    private function normalizeValue($value): ?bool
    {
        if (\in_array($value, [true, 'true', '1', '', null], true)) {
            return true;
        }

        if (\in_array($value, [false, 'false', '0'], true)) {
            return false;
        }

        return null;
    }
}
