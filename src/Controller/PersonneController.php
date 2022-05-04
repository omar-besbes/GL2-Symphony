<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Repository\PersonneRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
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
		$personnes = $this->repository->findBy(array(),null,10);
		return $this->render('personnes/personnes.html.twig', [
			'controller_name' => 'PersonneController',
			'personnes' => $personnes
		]);
	}

	public function add($nom, $prenom, $age, $cin, $path = null, ValidatorInterface $validator): Response
	{
		$personne = new Personne($nom, $prenom, intval($age), intval($cin), $path);
		$errors = $validator->validate($personne);
		if(count($errors) > 0) {
			$this->addFlash('danger', (string) $errors);
			return $this->redirectToRoute('app_personne');
		}
		$this->manager->persist($personne);
		$this->manager->flush();
		$this->addFlash('success', 'Personne '.$nom.' '.$prenom.' ajoutée avec succès.');
		return $this->redirectToRoute('app_personne');
	}

	public function delete($id):Response {
		$personne = $this->repository->findOneBy(['id' => $id]);
		if($personne == null) {
			$this->addFlash('danger', "Aucune personne portant l'id ". $id ." trouvée. L'avez-vous déja supprimée ?");
			return $this->redirectToRoute('app_personne');
		}
		$this->manager->remove($personne);
		$this->manager->flush();
		$this->addFlash('success', 'Personne '.$personne->getNom().' '.$personne->getPrenom().' supprimée avec succès.');
		return $this->redirectToRoute('app_personne');
	}

	public function update($id, $criteria, $newValue, ValidatorInterface $validator):Response {
		$personne = $this->repository->findOneBy(['id' => $id]);
		switch ($criteria) {
			case 'nom':
			{
				$personne->setNom($newValue);
				break;
			}
			case 'prenom':
			{
				$personne->setPrenom($newValue);
				break;
			}
			case 'age':
			{
				$personne->setAge(intval($newValue));
				break;
			}
			case 'cin':
			{
				$personne->setCin(intval($newValue));
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
				return $this->redirectToRoute('app_personne');
			}
		}
		$errors = $validator->validate($personne);
		if(count($errors) > 0) {
			$this->addFlash('danger', (string) $errors);
			return $this->redirectToRoute('app_personne');
		}
		$this->manager->persist($personne);
		$this->manager->flush();
		$this->addFlash('success', $criteria.' de la personne modifié avec succès.');
		return $this->redirectToRoute('app_personne');
	}
}
