<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\ProductOrder;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ShoppingCarController extends AbstractController
{
    /**
     * @Route("/shopping/car", name="shopping_car")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $order = $em->getRepository(Order::class)->FindUserOrder($user);
        return $this->render('shopping_car/index.html.twig', [
            'order' => $order,
        ]);
    }

    /**
     * @Route("/shopping/car/add/product",  options={"expose"=true}, name="add_producto_to_shopping_car")
     */
    public function addProductToShoopingCar(Request $request)
    {
        if ($request->isXmlHttpRequest()) {

            $em = $this->getDoctrine()->getManager();
            $product_id = $request->request->get('product_id');
            $user_hash = $request->request->get('hash');
            $ammount = $request->request->get('ammount');

            $user = $em->getRepository(User::class)->findOneBy(['hash' => $user_hash]);
            $order = $em->getRepository(Order::class)->findOneBy(['user' => $user, 'status' => Order::STATUS[1]]);
            $product = $em->getRepository(Product::class)->find($product_id);

            if ($order == null) {
                $order = new Order($user);
                $em->persist($order);
            }

            $product_order = new ProductOrder($ammount, $product, $order);
            $order->addProductOrder($product_order);
            $em->persist($product_order);
            $em->flush();
            return new JsonResponse(
                [
                    'product_name' => $product->getName(),
                    'product_price' => $product->getPrice(),
                    'product_ammount'=>$product_order->getCantidad()
                ]
            );
        }
        throw new \Exception('This is not an ajax Call');
    }


    /**
     * @Route("/shopping/car/delete/product/{id}", name="delete_product")
     */
    public function DeleteProduct(ProductOrder $productOrder){
        $em = $this->getDoctrine()->getManager();
        $em->remove($productOrder);
        $em->flush();
        return $this->redirectToRoute('shopping_car');
    }

    /**
     * @Route("/shopping/car/change/ammount/product/{id}/{ammount}", options={"expose"=true}, name="change_ammount")
     */
    public function ChangeAmmount(ProductOrder $productOrder, $ammount){
        $em = $this->getDoctrine()->getManager();
        $productOrder->setCantidad($ammount);
        $em->flush();
        $this->addFlash('Edited', ProductOrder::EDITED);
        return $this->redirectToRoute('shopping_car');
    }

}
