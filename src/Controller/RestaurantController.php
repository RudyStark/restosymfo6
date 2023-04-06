<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Form\RestaurantType;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RestaurantController extends AbstractController
{

	/**
	 * @param string $id
	 * @param RestaurantRepository $restaurantRepository
	 * @return Response
	 */
	#[Route('/restaurant/find/{id}', name: 'restaurant_show')]
	public function show(string $id, RestaurantRepository $restaurantRepository): Response
	{
		$restaurants = $restaurantRepository->findBy(['id' => $id]);

		return $this->render('restaurant/show.html.twig', [
			'restaurants' => $restaurants,
		]);
	}

	/**
	 * @param Request $request
	 * @param EntityManagerInterface $manager
	 * @return Response
	 * @throws \Symfony\Component\Form\Exception\LogicException
	 */
	#[Route('/restaurant/create', name: 'restaurant_create')]
	public function create(Request $request, EntityManagerInterface $manager): Response
	{
		$restaurant = new Restaurant();
		$form = $this->createForm(RestaurantType::class, $restaurant);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$manager->persist($restaurant);
			$manager->flush();

			$this->addFlash('success', 'Le restaurant a bien été ajouté');
			return $this->redirectToRoute('app_home');
		}
		return $this->render('restaurant/new.html.twig', [
			'form' => $form->createView(),
		]);
	}

	/**
	 * @param Request $request
	 * @param EntityManagerInterface $manager
	 * @param Restaurant $restaurant
	 * @return Response
	 * @throws \Symfony\Component\Form\Exception\LogicException
	 */
	#[Route('/restaurant/update/{id}', name: 'restaurant_update')]
	public function update(Request $request, EntityManagerInterface $manager, Restaurant $restaurant): Response
	{
		$form = $this->createForm(RestaurantType::class, $restaurant);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$manager->persist($restaurant);
			$manager->flush();

			$this->addFlash('success', 'Le restaurant a bien été modifié');
			return $this->redirectToRoute('app_home');
		}
		return $this->render('restaurant/edit.html.twig', [
			'form' => $form->createView(),
		]);
	}

	/**
	 * @param Request $request
	 * @param EntityManagerInterface $manager
	 * @param Restaurant $restaurant
	 * @return Response
	 */
	#[Route('/restaurant/delete/{id}', name: 'restaurant_delete', methods: ['GET'])]
	public function delete( EntityManagerInterface $manager, Restaurant $restaurant): Response
	{
		$manager->remove($restaurant);
		$manager->flush();

		$this->addFlash('success', 'Le restaurant a bien été supprimé');
		return $this->redirectToRoute('app_home');
	}

}
