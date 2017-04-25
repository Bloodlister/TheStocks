<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cart;
use AppBundle\Entity\Purchase;
use AppBundle\Entity\User;
use AppBundle\Form\PurchaseForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PurchaseController extends Controller
{

    /**
     * @Route("/processing", name="processing")
     * @Method("POST")
     *
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
            $purchase->setItem($cart->getItem());
            $purchase->setUser($this->getUser());
            $purchase->setQuantity($cart->getQuantity());
            $purchase->setMadeOn(new \DateTime('now'));

            $em->persist($purchase);
        }

        $em->flush();

        //Remove Carts of the purchases
        //Fix the quantities of the bought products

        $totalCost = 0;
        foreach($carts as $cart)
        {
            /** @var Cart $cart */
            $totalCost += $cart->getItem()->getPrice() * $cart->getQuantity();

            $cart->getItem()->setQuantity($cart->getItem()->getQuantity() - $cart->getQuantity());

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
     * @Method("POST")
     * @Template()
     */
    public function purchaseSuccessAction()
    {
        return [];
    }
}
