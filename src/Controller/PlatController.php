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
//	private EntityManagerInterface $entityManager;
//	private PlatRepository $platRepository;
//
//	public function __construct(EntityManagerInterface $entityManager, PlatRepository $platRepository)
//	{
//		$this->entityManager = $entityManager;
//		$this->platRepository = $platRepository;
//	}

	#[Route('/plat/find/{id}', name: 'app_plat_show')]
	// on récupère tout les plats d'un restaurant
	public function show(string $id, RestaurantRepository $restaurantRepository): Response
	{
		$restaurants = $restaurantRepository->findBy(['id' => $id]);

		return $this->render('plat/show.html.twig', [
			'restaurants' => $restaurants,
		]);
	}

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
		$plats = $restaurant->getPlats();

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
			'restaurant' => $restaurant,
			'plats' => $plats
		]);
	}

	#[Route('/plat/{id}/delete', name: 'app_plat_delete')]
	public function delete(Request $request, EntityManagerInterface $entityManager, PlatRepository $platRepository, Plat $plat): Response
	{
		$entityManager->remove($plat);
		$entityManager->flush();

		$this->addFlash('success', 'Le plat a bien été supprimé');
		return $this->redirectToRoute('restaurant_show', ['id' => $plat->getRestaurant()->getId()]);
	}

	/**
	 * @param Request $request
	 * @param EntityManagerInterface $entityManager
	 * @param PlatRepository $platRepository
	 * @param Plat $plat
	 * @return Response
	 * @throws \Symfony\Component\Form\Exception\LogicException
	 */
	#[Route('/plat/{id}/update', name: 'app_plat_update')]
    public function update(Request $request, EntityManagerInterface $entityManager, PlatRepository $platRepository, Plat $plat): Response
	{
		$form = $this->createForm(PlatType::class, $plat);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager->persist($plat);
			$entityManager->flush();

			$this->addFlash('success', 'Le plat a bien été modifié');
			return $this->redirectToRoute('app_plat_show', ['id' => $plat->getRestaurant()->getId()]);
		}

		return $this->render('plat/edit.html.twig', [
			'form' => $form->createView(),
			'plat' => $plat
		]);
	}
}
