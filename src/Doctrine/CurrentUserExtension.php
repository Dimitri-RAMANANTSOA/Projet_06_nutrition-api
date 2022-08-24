<?php
// api/src/Doctrine/CurrentUserExtension.php

namespace App\Doctrine;

use App\Entity\Recipes;
use App\Entity\Plantypes;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;

final class CurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null): void
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, string $operationName = null, array $context = []): void
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass): void
    {
        if (Recipes::class !== $resourceClass || $this->security->isGranted('ROLE_ADMIN')) {
            return;
        }

        if (Recipes::class !== $resourceClass || $this->security->isGranted('ROLE_USER')) {

            $user = $this->security->getUser();

            $rootAlias = $queryBuilder->getRootAliases()[0];
            $queryBuilder
                ->join(sprintf('%s.plantype', $rootAlias), 'p')
                ->join(sprintf('%s.ingredients', $rootAlias), 'i')
                ;

            $firstelem = true;
            $query = "i.id NOT IN (";

            foreach ($user->getAllergen() as $ingredient)
            {
                $param = $ingredient->getId();
                if ($firstelem){
                    $query = $query . "$param";
                    $firstelem = false;
                }
                else{
                    $query = $query . ",$param";
                }
            }
            $query = $query . ")";
            //dd($query);
            //$queryBuilder->Where($query);

            $firstelem = true;
            $query = $query . " AND p.id IN (";
            
            foreach ($user->getPlan() as $plan)
            {
                $param = $plan->getId(); 
                
                if ($firstelem){
                    $query = $query . "$param";
                    $firstelem = false;
                }
                else{
                    $query = $query . ",$param";
                }
            }
            
            $query = $query . ")";
            dd($query);
            $queryBuilder->Where($query);
            
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere(sprintf('%s.isPublic = :isPublic', $rootAlias));
        $queryBuilder->setParameter('isPublic', 1);
    }
}