<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Form\TodoType;
use App\Repository\TodoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/todo")]
class TodoController extends AbstractController
{
    #[Route('/', name: 'app_todo')]
    public function index(TodoRepository $todoRepository): Response
    {

        $todos = $todoRepository->findAll();

        return $this->render('todo/index.html.twig', [
            'todos' => $todos,
        ]);
    }

    #[Route("/delete/{id}", name:"delete_todo")]
    public function delete(Todo $todo, EntityManagerInterface $manager) :Response
    {

        if($todo){
            $manager->remove($todo);
            $manager->flush();
        }


        return $this->redirectToRoute("app_todo");
    }

   #[Route("/create", name:"create_todo")]
    public function create(Request $request, EntityManagerInterface $manager){

        $todo = new Todo();
        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ){


            $todo->setCreatedAt(new \DateTime());
            $todo->setStatus(false);

            $manager->persist($todo);

            $manager->flush();

            return $this->redirectToRoute("app_todo");

        }


        return $this->renderForm("todo/create.html.twig",[
            "form"=>$form
        ]);
    }

    #[Route("/edit/{id}", name:"edit_todo")]
    public function edit(Todo $todo,Request $request, EntityManagerInterface $manager){


        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ){



            $manager->persist($todo);

            $manager->flush();

            return $this->redirectToRoute("app_todo");

        }


        return $this->renderForm("todo/create.html.twig",[
            "form"=>$form
        ]);
    }
}
