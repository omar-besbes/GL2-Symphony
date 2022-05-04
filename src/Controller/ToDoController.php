<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ToDoController extends AbstractController
{
	public function indexAction(SessionInterface $session): Response
	{
		if (!$session->has('todos')) {
			$session->set('todos', [
				'symfony' => 'kammel et3almou',
				'database' => 'setup db with symfony',
				'java' => 'aamel recap',
				'rx' => 'juste exercie',
				'algebre' => 'aamel le nécessaire bch tekhdem ds 1'
			]);
		}
		return $this->render('to_do/listTodo.html.twig', [
			'controller_name' => 'ToDoController',
			'todos' => $session->get('todos')
		]);
	}

	public function addToDoAction(SessionInterface $session): Response
	{
		$request = Request::createFromGlobals();
		if (!$session->has('todos'))
			$session->set('todos', []);

		$todos = $session->get('todos');
		if (isset($todos['label']) and isset($todos['todo'])) {
			$this->addFlash('success', "ToDo changed successfully");
		} else $this->addFlash('success', "ToDo added successfully");
		$todos[$request->request->get('label')] = $request->request->get('todo');
		$session->set('todos', $todos);

		return $this->render('to_do/listTodo.html.twig', [
			'controller_name' => 'ToDoController',
			'todos' => $session->get('todos')
		]);
	}

	public function deleteToDoAction(SessionInterface $session)
	{
		$request = Request::createFromGlobals();
		if (!$session->has('todos'))
			$this->addFlash('danger', "la liste n'est pas encore initialisée");
		$todos = $session->get('todos');
		if (isset($todos['delete'])) {
			$todos->unset($todos['delete']);
			$this->addFlash('success', "ToDo deleted successfully");
		} else {
			$this->addFlash('danger', "ToDo doesn't exist");
		}

		return $this->render('to_do/listToDo.html.twig', [
			'controller_name' => 'ToDoController',
			'todos' => $session->get('todos')
		]);
	}

	public function resetToDoAction(SessionInterface $session): Response
	{
		$session->clear();
		$this->addFlash('success', "cleared session");

		return $this->render('to_do/listTodo.html.twig', [
			'controller_name' => 'ToDoController',
			'todos' => $session->get('todos')
		]);
	}
}
