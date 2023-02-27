<?php

namespace App\Controller;

use App\Entity\Classroom;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Form\FormName;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ClassroomController extends AbstractController
{
    #[Route('/classroom', name: 'app_classroom')]
    public function index(): Response
    {
        return $this->render('classroom/index.html.twig', [
            'controller_name' => 'ClassroomController',
        ]);
    }

    #[Route('/list', name: 'list_classroom')]
    public function list(ManagerRegistry $doctrine): Response
    {
        $repo = $doctrine->getRepository(Classroom::class);
        $classroom = $repo->findAll();
        return $this->render('classroom/index.html.twig', [
            'classrooms' => $classroom
        ]);
    }

    #[Route('/add', name: 'add_classroom')]
    public function add(Request $request): Response
    {
        $classroom = new Classroom();
        $form = $this->createForm(FormName::class, $classroom);
        $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($classroom);
            $em->flush();
            return $this-> redirectToRoute("list_classroom");
        }else {
            return $this->render("classroom/add.html.twig",array('form'=>$form->createView()));
        } 
    }

    #[Route('/update/{id}', name: 'update_classroom')]
    public function update($id, Request $request): Response
    {
        $classroom = $this->getDoctrine()->getRepository(Classroom::class)->find($id);
        $form = $this->createForm(FormName::class, $classroom);
        $form->add('Modifier', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this-> redirectToRoute("list_classroom");
        }else {
            return $this->render("classroom/update.html.twig",array('form'=>$form->createView()));
        } 
    }

    #[Route('/delete/{id}', name: 'delete_classroom')]
    public function delete($id, Request $request): Response
    {
        $classroom = $this->getDoctrine()->getRepository(Classroom::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($classroom);
        $em->flush();
        return $this-> redirectToRoute("list_classroom");
    }
}
