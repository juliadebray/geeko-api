<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CustomerController extends AbstractController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function __invoke(Customer $data): Customer
    {
        return $this->userService->makeUser($data, ["ROLE_CUSTOMER"], 'pending');
    }
}
