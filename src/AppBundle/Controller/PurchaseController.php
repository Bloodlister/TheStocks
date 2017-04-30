<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cart;
use AppBundle\Entity\Purchase;
use AppBundle\Entity\User;
use AppBundle\Form\PurchaseForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PurchaseController extends Controller
{

    /**
     * @Route("/processing", name="processing")
     * @Security("has_role='ROLE_USER'")
     * @Method("POST")
     * @param Request $request
     */
    public function makePurchasesAction(Request $request)
    {
        //Create Purchase Entities
        $em = $this->getDoctrine()->getManager();

        $carts = $em->getRepository('AppBundle:Cart')->getCartsOfUser($this->getUser()->getId());

        $form = $this->createForm(PurchaseForm::class);
        $form->handleRequest($request);

        /** @var User $userInfo */
        $userInfo = $form->getData();

        /** @var Purchase $purchase */
        $purchase = new Purchase();
        $purchase->setInfo($userInfo);

        foreach ($carts as $cart)
        {
            /** @var Cart $cart */
            $purchase->setReceiver($userInfo->getFullName());
            $purchase->setItem($cart->getItem());
            $purchase->setQuantity($cart->getQuantity());
            $purchase->setUser($this->getUser());

            $em->persist($purchase);
        }

        $em->flush();

        //Remove Carts of the purchases
        //Fix the quantities of the bought products

        $totalCost = 0;
        foreach($carts as $cart)
        {
            /** @var Cart $cart */
            $totalCost += $cart->getItem()->getPriceWithDiscount() * $cart->getQuantity();

            $cart->getItem()->setQuantity($cart->getItem()->getQuantity() - $cart->getQuantity());
            $cart->getItem()->getUser()->setCash($cart->getItem()->getUser()->getCash() + $totalCost);

            $em->remove($cart);
        }

        $repo = $em->getRepository('AppBundle:User');

        $user = $repo->find($this->getUser()->getId());
        $user->setCash($user->getCash() - $totalCost);

        $em->flush();

        //Redirect by POST
        return $this->redirectToRoute('purchase_success', [], 307);
    }

    /**
     * @Route("/purchase/success", name="purchase_success")
     * @Security("has_role='ROLE_USER'")
     * @Method("POST")
     * @Template()
     */
    public function purchaseSuccessAction()
    {
        return [];
    }
}
