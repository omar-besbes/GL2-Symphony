<?php

namespace App\DataFixtures;

use App\Entity\Personne;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
	public function load(ObjectManager $manager)
	{
		for ($i = 0; $i < 15; $i++) {
			$personne = new Personne('nom'.$i, 'prenom'.$i, random_int(0, 999), random_int(0, 99999999), 'default');
			$manager->persist($personne);
		}
		$manager->flush();
	}
}
