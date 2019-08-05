<?php

namespace App\DoctrineExtension;

use ApiPlatform\...\QueryCollectionExtensionInterface;
use ApiPlatform\...\QueryNameGeneratorInterface;
use App\Entity\Dog;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;

class ACLFilterDogExtension
implements QueryCollectionExtensionInterface
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null
    ) {
        if (Dog::class !== $resourceClass) {
            return;
        }

        if ($this->security->isGranted(User::ROLE_DOCTOR)) {
            return;
        }

        $this->filterOnOwner($queryBuilder);

        return;
    }

    private function filterOnOwner(QueryBuilder $queryBuilder): void
    {
        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder
            ->andWhere(
                sprintf('%s.owner = :current_user', $rootAlias)
            )
            ->setParameter(
                'current_user', $this->security->getUser()
            );
    }
}
