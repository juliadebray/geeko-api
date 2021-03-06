<?php

namespace App\Controller;

use App\Entity\Potion;
use App\Entity\Recipe;
use App\Repository\CustomerRepository;
use App\Repository\RecipeRepository;
use App\Service\PotionService;
use Cassandra\Custom;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use DateTime;

class PotionController extends AbstractController
{
    private PotionService $potionService;

    public function __construct(PotionService $potionService)
    {
        $this->potionService = $potionService;
    }

    public function __invoke(Potion $data): Potion
    {
        /** Ajoute l'auteur de la potion et la date à laquelle elle est créée */
        $user = $this->getUser();
        $this->potionService->makePotion($data, $user);

        return $data;
    }
}
