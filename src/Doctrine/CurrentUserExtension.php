<?php
// api/src/Doctrine/CurrentUserExtension.php

namespace App\Doctrine;

use App\Entity\Recipes;
use App\Entity\Plantypes;
use App\Entity\Ingredients;
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
            $allergens = $user->getAllergen();
            $plans = $user->getPlan();
            $allergentab = [];
            $plantab = [];

            foreach($allergens as $allergen){
                $allergentab[]= $allergen->getId();
            }

            foreach($plans as $plan){
                $plantab[]= $plan->getId();
            }

            $rootAlias = $queryBuilder->getRootAliases()[0];
            $qb = $queryBuilder->getEntityManager()->createQueryBuilder();
            $recipequery = $qb->select('r')->from(Recipes::class, 'r');

            $recipes = $recipequery->getQuery()->getResult();
            $recipetab = [];

            foreach ($recipes as $recipe) {
                foreach ($recipe->getIngredients() as $ingredient){
                    if (in_array ($ingredient->getId(),$allergentab)){
                        $recipetab[] = $recipe->getId();
                    }     
                }
                foreach ($recipe->getPlantype() as $plan){
                    if (!(in_array ($plan->getId(), $plantab))){
                        $recipetab[] = $recipe->getId();
                    }
                }
            }
            
            $recipelist = implode(',',array_unique($recipetab));

            $queryBuilder->where($queryBuilder->expr()->notIN(sprintf('%s.id', $rootAlias), $recipelist));
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere(sprintf('%s.isPublic = 1', $rootAlias));
    }
}