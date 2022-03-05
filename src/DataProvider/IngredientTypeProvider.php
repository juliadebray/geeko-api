<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\IngredientType;
use App\Exception\IngredientTypeNotFoundException;
use App\Repository\IngredientTypeRepository;
use Symfony\Component\Security\Core\Security;
use App\Constants\Constant;
use App\Constants\ErrorMessage;

class IngredientTypeProvider implements ContextAwareCollectionDataProviderInterface,
    RestrictedDataProviderInterface, ItemDataProviderInterface
{
    private IngredientTypeRepository $ingredientTypeRepository;
    private Security $security;

    public function __construct(IngredientTypeRepository $ingredientTypeRepository, Security $security)
    {
        $this->ingredientTypeRepository = $ingredientTypeRepository;
        $this->security = $security;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        $user = $this->security->getUser();

        if($user && $user->getRoles() === Constant::ROLE_ADMIN)
        {
            return $this->ingredientTypeRepository->findAll();
        }

        return $this->ingredientTypeRepository->findByStatus(Constant::STATUS_ACTIVATED);
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): IngredientType|null
    {
        $user = $this->security->getUser();
        $ingredientType = $this->ingredientTypeRepository->find($id);

        if(!$ingredientType)
        {
            return throw new IngredientTypeNotFoundException(ErrorMessage::INGREDIENT_TYPE_NOT_FOUND);
        }

        if ($user && $user->getRoles() === Constant::ROLE_ADMIN) {
            return $ingredientType;
        }

        if ($ingredientType->getStatus() === Constant::STATUS_DISABLED) {
            return throw new IngredientTypeNotFoundException(ErrorMessage::INGREDIENT_TYPE_NOT_FOUND);
        }

        return $ingredientType;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return IngredientType::class === $resourceClass;
    }
}
