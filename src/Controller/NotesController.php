<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class NotesController extends AbstractController
{
    public function index($number): Response
    {
		$tab = array();
		for ($i = 0; $i < $number; $i++)
			try {
				$tab[] = random_int(0, 100);
			} catch (\Exception $e) {
				echo $e->getMessage();
			}
		return $this->render('notes/notes.html.twig', [
            'controller_name' => 'NotesController',
			'table' => $tab,
			'table_size' => $number
        ]);
    }
}
