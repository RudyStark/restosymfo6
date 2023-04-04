<?php

namespace App\Controller;

use App\Repository\RestaurantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 */
class RestaurantController extends AbstractController
{

	/**
	 * @param string $id
	 * @param RestaurantRepository $restaurantRepository
	 * @return Response
	 */
	#[Route('/restaurant/{id}', name: 'restaurant_show')]
	public function show(string $id, RestaurantRepository $restaurantRepository): Response
	{
		$restaurants = $restaurantRepository->findBy(['id' => $id]);

		return $this->render('restaurant/show.html.twig', compact('restaurants'));
	}

}
