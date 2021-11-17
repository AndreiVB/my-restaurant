<?php

namespace App\Controller;

use App\Entity\Dish;
use App\Form\DishType;
use App\Repository\DishRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


    /**
     * @Route("/dish", name="dish.")
     */
class DishController extends AbstractController
{
    /**
     * @Route("/", name="edit")
     */
    public function index(DishRepository $dr): Response
    {
        $dish = $dr->findAll();
        return $this->render('dish/index.html.twig', [
            'dishes' => $dish,
        ]);
    }

     /**
     * @Route("/create", name="create")
     */
    public function create(Request $request) {
        $dish = new Dish();

        //Form
        $form = $this->createForm(DishType::class, $dish);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            //EntityManager
        $em = $this->getDoctrine()->getManager();
        $image = $request->files->get('dish')['attachement'];

        if($image) {
            $filename = md5(uniqid()) . '.' . $image->guessClientExtension();
        }

        $image->move(
            $this->getParameter('images_folder'),
            $filename
        );

        $dish->setImage($filename);
        
        $em->persist($dish);
        $em->flush();

        return $this->redirect($this->generateUrl('dish.edit'));
        }
        //Response
        return $this->render('dish/create.html.twig', [
            'createForm' => $form->createView()
        ]);
        
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */

     public function delete($id, DishRepository $dr) {
         $em = $this->getDoctrine()->getManager();
         $dish = $dr->find($id);
         $em->remove($dish);
         $em->flush();

         //message
        $this->addFlash('succes', 'dish was removed');
         return $this->redirect($this->generateUrl('dish.edit'));
     }


       /**
     * @Route("/show/{id}", name="show")
     */
    //param converter
     public function show(Dish $dish) {
       return $this->render('dish/show.html.twig', [
            'dish' => $dish,
        ]);
     }
}