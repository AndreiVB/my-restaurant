<?php

namespace App\Controller;

use App\Entity\Dish;
use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    /**
     * @Route("/order", name="order")
     */
    public function index(OrderRepository $orderRepository): Response
    {

        $order = $orderRepository->findBy(
            ['meal' => 'meal1']
        );
        return $this->render('order/index.html.twig', [
            'orders' => $order
        ]);
    }

    /**
     * @Route("/orderMeal/{id}", name="orderMeal")
     */

    public function orderMeal(Dish $dish) {
        $order = new Order();
        $order->setMeal("meal1");
        $order->setName($dish->getName());
        $order->setOrderNr($dish->getId());
        $order->setPrice($dish->getPrice());
        $order->setStatus("open");

        //Entity manager
        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();   

        $this->addFlash('order', $order->getName(). 'was added to the order');
        
        return $this->redirect($this->generateUrl('menu'));
    }

    /**
     * @Route("/status/{id}, {status}", name="status")
     */
    public function status($id, $status) {
        
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository(Order::class)->find($id);

        $order->setStatus($status);
        $em->flush();

        return $this->redirect($this->generateUrl('order'));
        
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */

     public function delete($id, OrderRepository $or) {
         $em = $this->getDoctrine()->getManager();
         $order = $or->find($id);
         $em->remove($order);
         $em->flush();
         return $this->redirect($this->generateUrl('order'));
     }
}