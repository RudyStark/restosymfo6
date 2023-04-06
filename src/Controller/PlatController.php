<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Entity\Plat;
use App\Form\IngredientType;
use App\Form\PlatType;
use App\Repository\PlatRepository;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlatController extends AbstractController
{

	/**
	 * @param Request $request
	 * @param EntityManagerInterface $manager
	 * @return Response
	 * @throws \Symfony\Component\Form\Exception\LogicException
	 */
	#[Route('/plat/{id}/creation', name: 'app_plat_create')]
	public function create(Request $request, EntityManagerInterface $entityManager, RestaurantRepository $restaurantRepository): Response
	{
		$restaurant = $restaurantRepository->find($request->get('id'));

		$ingredient = new Ingredient();
		$formIngredient = $this->createForm(IngredientType::class, $ingredient);
		$formIngredient->handleRequest($request);

		if ($formIngredient->isSubmitted() && $formIngredient->isValid()) {
			$entityManager->persist($ingredient);
			$entityManager->flush();

			$this->addFlash('success', 'L\'ingrédient a bien été ajouté');
			return $this->redirectToRoute('app_plat_create', ['id' => $restaurant->getId()]);
		}

		$plat = new Plat();
		$formPlat = $this->createForm(PlatType::class, $plat);
		$formPlat->handleRequest($request);

		if ($formPlat->isSubmitted() && $formPlat->isValid()) {
			$plat->setRestaurant($restaurant);
			$entityManager->persist($plat);
			$entityManager->flush();

			$this->addFlash('success', 'Le plat a bien été ajouté');
			return $this->redirectToRoute('restaurant_show', ['id' => $restaurant->getId()]);
		}

		return $this->render('plat/create.html.twig', [
			'formIngredient' => $formIngredient->createView(),
			'formPlat' => $formPlat->createView(),
			'restaurant' => $restaurant
		]);
	}

	#[Route('/plat/{id}/delete', name: 'app_plat_delete')]
	public function delete(Request $request, EntityManagerInterface $entityManager, PlatRepository $platRepository, Plat $plat): Response
	{
		// Si c'est un plat qui est supprimé
		if ($request->get('plat')) {
			$plat = $platRepository->find($request->get('plat'));
			$entityManager->remove($plat);
			$entityManager->flush();
			$this->addFlash('success', 'Le plat a bien été supprimé');
		}
		// Si c'est un ingrédient qui est supprimé
		if ($request->get('ingredient')) {
			$ingredient = $platRepository->find($request->get('ingredient'));
			$entityManager->remove($ingredient);
			$entityManager->flush();
			$this->addFlash('success', 'L\'ingrédient a bien été supprimé');
		}
		return $this->redirectToRoute('restaurant_show', ['id' => $plat->getRestaurant()->getId()]);
	}
}
