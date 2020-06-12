<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\ProductOrder;
use App\Entity\User;
use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ShoppingCarController extends AbstractController
{
    /**
     * @Route("/shopping/car", name="shopping_car")
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $order = $em->getRepository(Order::class)->findOneBy(['user'=>$user, 'status'=>Order::STATUS[1]]);
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $paymentMethod = $form->get('paymentMethod')->getData();
            $total_ammount = 0;
            foreach ($order->getProductOrders() as $productorder){
                $product = $productorder->getProduct();
                $subtotal = $product->getPrice() * $productorder->getCantidad();
                $total_ammount += $subtotal;
            }

            $order->setPaymentMethod($paymentMethod);
            $order->setStatus(Order::STATUS[2]);
            $order->setTotalValue($total_ammount);
            $order->setRealizationDate(new \DateTime());
            $em->flush();
            return $this->redirectToRoute('client_sales',['hash'=>$user->getHash()]);

        }


        return $this->render('shopping_car/index.html.twig', [
            'order' => $order,
            'form'=>$form->createView()
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

    /**
     * @Route("/shopping/car/buys/{hash}", name="client_sales")
     */
    public function ClientSales($hash){
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['hash'=>$hash]);
        $orders = $em->getRepository(Order::class)->FindUserOrderSales($user);
        return $this->render('shopping_car/sales.html.twig', ['orders'=>$orders, 'user'=>$user]);
    }

    /**
     * @Route("/shopping/car/sales/{search}", name="clients_sales")
     */
    public function ClientsSales($search){
        $em = $this->getDoctrine()->getManager();
        if($search == 'recents'){
            $orders = $em->getRepository(Order::class)->FindRecentOrderSales();
        }else{
            $orders = $em->getRepository(Order::class)->findAll();
        }
        return $this->render('shopping_car/admin_sales.html.twig', ['orders'=>$orders]);
    }


    /**
     * @Route("/shopping/car/finish/sale/{id}", name="finish_sale")
     */
    public function finishSale(Order $order){
        $em = $this->getDoctrine()->getManager();
        $order->setStatus(Order::STATUS[0]);
        $em->flush();
        return $this->redirectToRoute('clients_sales', ['search'=>'recents']);
    }

}
