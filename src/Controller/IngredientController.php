<?php

namespace App\Controller;

use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class IngredientController extends AbstractController
{
	private EntityManagerInterface $entityManager;
	#[ORM\Column(type: 'string')]
	private IngredientRepository $IngredientRepository;

	public function __construct(EntityManagerInterface $entityManager, IngredientRepository $ingredientRepository)
	{
		$this->entityManager = $entityManager;
		$this->IngredientRepository = $ingredientRepository;
	}
    #[Route('/ingredient/{id}', name: 'app_ingredient')]
    public function index(): Response
	{
		return $this->render('ingredient/index.html.twig', [
			'controller_name' => 'IngredientController',
		]);
	}

	/**
	 * @param Request $request
	 * @param EntityManagerInterface $entityManager
	 * @return Response
	 * @throws \Symfony\Component\Form\Exception\LogicException
	 */
	#[Route('/ingredient/update/{id}', name: 'app_ingredient_update')]
	public function update(Request $request, EntityManagerInterface $entityManager): Response
	{
		$ingredient = $this->IngredientRepository->find($request->get('id'));

		$form = $this->createForm(IngredientType::class, $ingredient);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager->persist($ingredient);
			$entityManager->flush();

			$this->addFlash('success', 'L\'ingrédient a bien été modifié');
			return $this->redirectToRoute('app_ingredient', ['id' => $ingredient->getId()]);
		}

		return $this->render('ingredient/edit.html.twig', [
			'form' => $form->createView(),
			'ingredient' => $ingredient
		]);
	}

	/**
	 * @param Request $request
	 * @param EntityManagerInterface $entityManager
	 * @return Response
	 */
	#[Route('/ingredient/delete/{id}', name: 'app_ingredient_delete')]
	public function delete(Request $request, EntityManagerInterface $entityManager): Response
	{
		$ingredient = $this->IngredientRepository->find($request->get('id'));
		$entityManager->remove($ingredient);
		$entityManager->flush();

		$this->addFlash('success', 'L\'ingrédient a bien été supprimé');
		return $this->redirectToRoute('app_ingredient');
	}
}
