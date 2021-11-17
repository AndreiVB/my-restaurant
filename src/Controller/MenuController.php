<?php

namespace App\Controller;

use App\Entity\Dish;
use App\Repository\DishRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MenuController extends AbstractController
{
    /**
     * @Route("/menu", name="menu")
     */
     public function menu(DishRepository $dr) {

        $dishes = $dr->findAll();
       return $this->render('menu/index.html.twig', [
            'dishes' => $dishes
        ]);
     }
}