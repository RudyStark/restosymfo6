<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Form\MenuType;
use App\Repository\MenuRepository;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{

	/**
	 * @param string $id
	 * @param RestaurantRepository $restaurantRepository
	 * @return Response
	 */
	#[Route('/menu/find/{id}', name: 'app_menu_index')]
	public function index(string $id, RestaurantRepository $restaurantRepository): Response
	{
		$restaurants = $restaurantRepository->findBy(['id' => $id]);

		return $this->render('menu/index.html.twig', [
			'restaurants' => $restaurants,
		]);
	}
	/**
	 * @param Request $request
	 * @param EntityManagerInterface $entityManager
	 * @param RestaurantRepository $restaurantRepository
	 * @return Response
	 * @throws \Symfony\Component\Form\Exception\LogicException
	 */
	#[Route('/menu/{id}/creation', name: 'app_menu_create')]
    public function create(Request $request, EntityManagerInterface $entityManager, RestaurantRepository $restaurantRepository): Response
	{
		$restaurant = $restaurantRepository->find($request->get('id'));

		$menu = new Menu();
		$form = $this->createForm(MenuType::class, $menu);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$menu->setRestaurant($restaurant);
			$entityManager->persist($menu);
			$entityManager->flush();

			$this->addFlash('success', 'Le menu a bien été ajouté');
			return $this->redirectToRoute('restaurant_show', ['id' => $restaurant->getId()]);
		}

		return $this->render('menu/create.html.twig', [
			'form' => $form->createView(),
			'restaurant' => $restaurant
		]);
	}

	/**
	 * @param Request $request
	 * @param EntityManagerInterface $entityManager
	 * @param MenuRepository $menuRepository
	 * @return Response
	 * @throws \Symfony\Component\Form\Exception\LogicException
	 */
	#[Route('/menu/{id}/update', name: 'app_menu_update')]
	public function update(Request $request, EntityManagerInterface $entityManager,Menu $menu, MenuRepository $menuRepository): Response
	{
		$menus = $menu->getRestaurant();

		$form = $this->createForm(MenuType::class, $menu);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager->persist($menu);
			$entityManager->flush();

			$this->addFlash('success', 'Le menu a bien été modifié');
			return $this->redirectToRoute('app_menu_index', ['id' => $menus->getId()]);
		}

		return $this->render('menu/edit.html.twig', [
			'form' => $form->createView()
		]);
	}

	/**
	 * @param Request $request
	 * @param EntityManagerInterface $entityManager
	 * @param RestaurantRepository $restaurantRepository
	 * @return Response
	 */
	#[Route('/menu/{id}/delete', name: 'app_menu_delete')]
	public function delete(Request $request, EntityManagerInterface $entityManager, Menu $menu): Response
	{
		$restaurant = $menu->getRestaurant();
		$entityManager->remove($menu);
		$entityManager->flush();

		$this->addFlash('success', 'Le menu a bien été supprimé');
		return $this->redirectToRoute('app_menu_index', ['id' => $restaurant->getId()]);
	}
}
