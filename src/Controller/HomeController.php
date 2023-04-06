<?php

namespace App\Controller;

use App\Repository\RestaurantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 */
class HomeController extends AbstractController
{

	/**
	 * @param RestaurantRepository $restaurantRepository
	 * @return Response
	 */
	#[Route('/home/', name: 'app_home')]
    public function index( RestaurantRepository $restaurantRepository): Response
    {
        $restaurants = $restaurantRepository->findAll();


        return $this->render('home/index.html.twig', [
            'restaurants' => $restaurants
        ]);
    }

}
