<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ToDoController extends AbstractController
{
    public function indexAction(SessionInterface $session): Response
    {
		if(!$session->has('todos')) {
			$session->set('todos', [
				'symfony' => 'kammel et3almou',
				'database' => 'setup db with symfony',
				'java' => 'aamel recap',
				'rx' => 'juste exercie',
				'algebre' => 'aamel le nécessaire bch tekhdem ds 1'
			]);
		}
        return $this->render('to_do/listToDo.html.twig', [
            'controller_name' => 'ToDoController',
			'todos' => $session->get('todos')
        ]);
    }
}
