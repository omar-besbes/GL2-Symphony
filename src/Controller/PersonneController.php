<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Repository\PersonneRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use function Symfony\Component\String\u;

class PersonneController extends AbstractController
{
	private ObjectManager $manager;


	/**
	 * @param PersonneRepository $repository
	 * @param ManagerRegistry $doctrine
	 */
	public function __construct(private PersonneRepository $repository, private ManagerRegistry $doctrine)
	{
		$this->manager = $doctrine->getManager();
	}

	public function index(): Response
	{
		$personnes = $this->repository->findAll();
		return $this->render('personnes/personnes.html.twig', [
			'controller_name' => 'PersonneController',
			'personnes' => $personnes
		]);
	}

	public function add($nom, $prenom, $age, $cin, $path = null): Response
	{
		$personne = new Personne($nom, $prenom, $age, $cin, $path);
		$this->manager->persist($personne);
		$this->manager->flush();
		$this->addFlash('success', 'Personne '.$nom.' '.$prenom.' ajoutée avec succès.');
		return $this->forward('App\\Controller\\PersonneController::index');
	}

	public function delete($id):Response {
		$personne = $this->repository->findOneBy(['id' => $id]);
		if($personne == null) {
			$this->addFlash('danger', "Aucune personne portant l'id ". $id ." trouvée. L'avez-vous déja supprimée ?");
			return $this->forward('App\\Controller\\PersonneController::index');
		}
		$this->manager->remove($personne);
		$this->manager->flush();
		$this->addFlash('success', 'Personne '.$personne->getNom().' '.$personne->getPrenom().' supprimée avec succès.');
		return $this->forward('App\\Controller\\PersonneController::index');
	}

	public function update($id, $criteria, $newValue):Response {
		$personne = $this->repository->findOneBy(['id' => $id]);
		if($personne == null) {
			$this->addFlash('danger', "Aucune personne portant l'id ". $id ." trouvée. L'avez-vous supprimée ?");
			return $this->forward('App\\Controller\\PersonneController::index');
		}
		switch ($criteria) {
			case 'nom':
			{
				if(!u($criteria)->match('/\w+/')) {
					$this->addFlash('danger', "Format invalide. Un nom se compose seulement de caractères alphabétiques.");
					return $this->forward('App\\Controller\\PersonneController::index');
				}
				$personne->setNom($newValue);
				break;
			}
			case 'prenom':
			{
				if(!u($criteria)->match('/\w+/')) {
					$this->addFlash('danger', "Format invalide. Un prénom se compose seulement de caractères alphabétiques.");
					return $this->forward('App\\Controller\\PersonneController::index');
				}
				$personne->setPrenom($newValue);
				break;
			}
			case 'age':
			{
				if(!u($criteria)->match('/\d{1,3}/')) {
					$this->addFlash('danger', "Format invalide. L'âge contient au plus 3 chiffres et au moins 1 chiffre.");
					return $this->forward('App\\Controller\\PersonneController::index');
				}
				$personne->setAge($newValue);
				break;
			}
			case 'cin':
			{
				if(!u($criteria)->match('/\d{8}/')) {
					$this->addFlash('danger', "Format invalide. Un CIN se compose de 8 chiffres exactement.");
					return $this->forward('App\\Controller\\PersonneController::index');
				}
				$personne->setCin($newValue);
				break;
			}
			case 'path':
			{
				$personne->setPath($newValue);
				break;
			}
			default:
			{
				$this->addFlash('danger', "Critère invalide.");
				return $this->forward('App\\Controller\\PersonneController::index');
			}
		}
		$this->manager->persist($personne);
		$this->manager->flush();
		$this->addFlash('success', $criteria.' de la personne modifié avec succès.');
		return $this->forward('App\\Controller\\PersonneController::index');
	}
}
