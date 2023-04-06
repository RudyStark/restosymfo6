<?php

namespace App\Controller;

use App\Repository\IngredientRepository;
use App\Repository\RestaurantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IngredientController extends AbstractController
{
    #[Route('/ingredient/find/{id}', name: 'app_ingredient')]
    public function index(string $id, IngredientRepository $ingredientRepository, RestaurantRepository $restaurantRepository): Response
	{
		$ingredients = $ingredientRepository->findBy(['id' => $id]);


		return $this->render('ingredient/index.html.twig', [
			'ingredients' => $ingredients,
		]);
	}
}
