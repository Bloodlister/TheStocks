<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cart;
use AppBundle\Entity\Item;
use AppBundle\Entity\Purchase;
use AppBundle\Form\PurchaseForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CartController extends Controller
{
    /**
     * @Route("/cart", name="cart_show")
     * @Template()
     */
    public function cartShowAction()
    {
        $em = $this->getDoctrine()->getManager();
        $items = $em->getRepository('AppBundle:Cart')->getCartsOfUser($this->getUser()->getId());

        $totalCost = 0;

        if ($items != null)
        {
            foreach ($items as $item)
            {
                /** @var $item Cart */
                $totalCost += $item->getItem()->getPrice() * $item->getQuantity();
            }
        }

        return [
            'cart' => $items,
            'totalCost' => $totalCost
        ];
    }

    /**
     * @Route("/cart/add/{id}", name="cart_add")
     * @Method("POST")
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function cardAddItemAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();

        $cart = new Cart();
        $cart->setUser($this->getUser());

        $item = $em->getRepository('AppBundle:Item')->find($id);

        if ($request->get('quantity') > $item->getQuantity() || $request->get('quantity') < 1)
        {
            return $this->redirectToRoute('item_show', [ 'id' => $id] );
        }

        $cart->setItem($item);
        $cart->setQuantity($request->get('quantity'));

        $em->persist($cart);
        $em->flush();

        return $this->redirectToRoute('cart_show');
    }

    /**
     * @Route("/cart", name="make_purchase")
     * @Method("POST")
     */
    public function makePurchaseAction()
    {
        $this->redirectToRoute('user_profile', [ 'id' => $this->getUser() ]);
    }

    /**
     * @Route("/cart/remove/{id}", name="cart_item_remove")
     * @Method("POST")
     *
     * @param Cart $cart
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function cartRemoveItemAction(Cart $cart)
    {
        if ($cart == null)
        {
            $this->redirectToRoute('cart_show');
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($cart);
        $em->flush();
        return $this->redirectToRoute('cart_show');
    }


    /**
     * @Route("/cart/finalize", name="cart_finalize")
     * @Method("POST")
     * @Template()
     */
    public function cartFinalizeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(PurchaseForm::class, $this->getUser());

        $form->handleRequest($request);

        return [
            'form' => $form->createView(),
            'totalCost' => $this->getUser()->getCartTotal($em),
            'user' => $this->getUser()
        ];
    }

}
