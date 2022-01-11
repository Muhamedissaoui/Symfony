<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\src\SecurityController;
use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Things;
use App\Entity\User;
use App\Entity\Category;
use App\Form\ThingsType;
use App\Repository\CategoryRepository;
use App\Repository\ThingsRepository;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
class ProjectAController extends AbstractController
{
    /**
     * @Route("/projecta", name="project_a")
     */
    public function index(): Response
    {
        return $this->render('project_a/index.html.twig', [
            'controller_name' => 'ProjectAController',
        ]);
    }
      /**
     * @Route("/", name="home")
     */
    public function home(): RedirectResponse
    {
        
        return $this->redirect('/login');
    }
    /**
     * @Route("/add", name="add")
     */
    public function add ( Request $request)
    {
        $things = new things();
        $user = $this->getUser();
        $things->setUser($user);
        $form = $this->createForm(ThingsType::class,$things);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
          $em=$this->getDoctrine()->getManager();
          $em->persist($things);
          $em->flush();
          $session = new session();
          $session->getFlashbag()->add('notice','thing added successfully');
          return $this->redirectToRoute('project_a');
        }   
        return $this->render('project_a/add.html.twig', ['ThingsForm' => $form->createView()]);
    }
      /**
     * @Route("/show", name="show")
     */
    public function show()
    {
        $em=$this->getDoctrine()->getManager()->getRepository(Things::class);
        $things = $em->findAll();
        return $this->render('project_a/show.html.twig', [
            'Things' => $things
        ]);
    }    
          /**
     * @Route("/choose", name="choose")
     */
    public function choose()
    {
        $em=$this->getDoctrine()->getManager()->getRepository(Things::class);
        $things = $em->findAll();
        return $this->render('project_a/choose.html.twig', ['Things'=> $things]);
    } 
     /**
     * @Route("/update{name}", name="update")
     */
    public function update(string $name, Request $request)
    {
        $em=$this->getDoctrine()->getManager()->getRepository(Things::class);
        $things = $em->find($name);
        if ( is_null($things) == false )
        {
        $form = $this->createForm(ThingsType::class,$things);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
          $em=$this->getDoctrine()->getManager();
          $em->persist($things);
          $em->flush();
          $session = new session();
          $session->getFlashbag()->add('notice','thing updated successfully');
          return $this->redirectToRoute('project_a');
        }  
        return $this->render('project_a/update.html.twig', ['Things' => $things, 'ThingsForm' => $form->createView()]);
        }
        else 
        return $this->redirectToRoute('project_a');

    }
}
