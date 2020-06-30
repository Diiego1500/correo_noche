<?php

namespace App\Controller;

use App\Entity\EpaycoTransaction;
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
            return $this->redirectToRoute('client_sales', ['hash'=>$user->getHash()]);

        }


        return $this->render('shopping_car/index.html.twig', [
            'order' => $order,
            'form'=>$form->createView(),
            'user'=>$user
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
            $orders = $em->getRepository(Order::class)->FindRecentDispachesSales();
        }
        return $this->render('shopping_car/admin_sales.html.twig', ['orders'=>$orders, 'search'=>$search]);
    }


    /**
     * @Route("/shopping/car/finish/sale/{id}", name="finish_sale")
     */
    public function finishSale(Order $order){
        $em = $this->getDoctrine()->getManager();
        $order->setStatus(Order::STATUS[0]);
        $order->setDispatchDate(new \DateTime());
        $em->flush();
        return $this->redirectToRoute('clients_sales', ['search'=>'recents']);
    }

    /**
     *  @Route("/show/transactions/epayco/", name="show_transactions")
     */
    public function ShowTransactions(){
        $em = $this->getDoctrine()->getManager();
        $transactions = $em->getRepository(EpaycoTransaction::class)->findAll();
        return $this->render('shopping_car/show_transactions.html.twig',['transactions'=>$transactions]);
    }


    /**
     * @Route("/epayco/success/", name="epayco_success")
     */
    public function EpaycoSuccess(Request $request){
        $em = $this->getDoctrine()->getManager();
        $p_cust_id_cliente = '80247';
        $p_key             = '1d83674120c9afc3f2bc0244c1fb4f5702991755';

        $x_ref_payco      = $_REQUEST['x_ref_payco'];
        $x_transaction_id = $_REQUEST['x_transaction_id'];
        $x_amount         = $_REQUEST['x_amount'];
        $x_currency_code  = $_REQUEST['x_currency_code'];
        $x_signature      = $_REQUEST['x_signature'];

        $signature = hash('sha256', $p_cust_id_cliente . '^' . $p_key . '^' . $x_ref_payco . '^' . $x_transaction_id . '^' . $x_amount . '^' . $x_currency_code);

        $x_response     = $_REQUEST['x_response'];
        $x_motivo       = $_REQUEST['x_response_reason_text'];
        $x_id_invoice   = $_REQUEST['x_id_invoice'];
        $x_autorizacion = $_REQUEST['x_approval_code'];
        //====================================================
        $x_cust_id_cliente = $_REQUEST['x_cust_id_cliente'];
        $x_ref_payco = $_REQUEST['x_ref_payco'];
        $x_id_factura = $_REQUEST['x_id_factura'];
        $x_id_invoice = $_REQUEST['x_id_invoice'];
        $x_description = $_REQUEST['x_description'];
        $x_amount = $_REQUEST['x_amount'];
        $x_respuesta = $_REQUEST['x_respuesta'];
        $x_fecha_transaccion = $_REQUEST['x_fecha_transaccion'];


        //Validamos la firma
        if ($x_signature == $signature) {
            /*Si la firma esta bien podemos verificar los estado de la transacción*/
            $x_cod_response = $_REQUEST['x_cod_response'];
            switch ((int) $x_cod_response) {
                case 1:
                    $em = $this->getDoctrine()->getManager();
                    $epayco_transaction = new EpaycoTransaction($x_cust_id_cliente,$x_ref_payco,$x_id_factura,$x_id_invoice,$x_description,$x_amount,$x_respuesta,$x_fecha_transaccion);
                    $em->persist($epayco_transaction);
                    $em->flush();
                    break;
                case 2:
                    # code transacción rechazada
                    echo "transacción rechazada";
                    break;
                case 3:
                    # code transacción pendiente
                    echo "transacción pendiente";
                    break;
                case 4:
                    # code transacción fallida
                    echo "transacción fallida";
                    break;

            }
        } else {
            die("Firma no valida");
        }

    }

}
