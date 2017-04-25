<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\EditUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class UserController
 * @package AppBundle\Controller
 *
 * @Route("/profile")
 */
class UserController extends Controller
{
    /**
     * @Route("/{id}", name="user_profile", requirements={"id"= "\d+"})
     * @Template()
     */
    public function profileAction(Request $request, $id)
    {
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);

        if (!is_numeric($id) || $user == null)
        {
            return $this->redirectToRoute('user_profile', ['id' => $this->getUser()->getId()]);
        }

        $paginator = $this->get('knp_paginator');

        $items = $paginator->paginate(
            $this->getDoctrine()->getRepository('AppBundle:Item')->userItems($user->getId()),
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 5)
        );

        return [
            'user' => $user,
            'items' => $items
        ];
    }


    /**
     * @Route("/purchases", name="user_purchases")
     * @Template()
     *
     * Display all the purchases of the currently logged user
     */
    public function purchasesAction()
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:Item');
        $inventory = $repo->createQueryBuilder('c')
            ->select('c')
            ->where('c.user = :user_id')
            ->setParameter('user_id', $this->getUser()->getId())
            ->orderBy('c.name')
            ->getQuery()
            ->getResult();

        return [
            'purchases' => $inventory
        ];
    }

    /**
     * @Route("/{id}/purchases", name="profile_purchases")
     * @Template()
     */
    public function profilePurchasesAction(User $user)
    {

        if ($user->getId() != $this->getUser()->getId() &&
            $user->getRoles() != 'ROLE_ADMIN')
        {
            return $this->redirectToRoute('item_all');
        }

        $purchases = $this->getDoctrine()->getRepository('AppBundle:Purchase')->getPurchasesByUser($user->getId());
        return [
            'user' => $user,
            'purchases' => $purchases
        ];
    }

    /**
     * @Route("/edit", name="edit_profile")
     * @Template()
     */
    public function editProfileAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(EditUser::class, $this->getUser());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }

        return [
            'form' => $form->createView(),
            'user' => $this->getUser()
        ];
    }

    /**
     * @Route("/addcash", name="add_cash")
     * @Method("POST")
     *
     * @param Request $request
     */
    public function addCashAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($this->getUser()->getId());

        $cash = $request->get('cash');

        $user->setCash($user->getCash() + $cash);

        $em->flush();
        return $this->redirectToRoute('user_profile', [ 'id' => $this->getUser()->getId()]);
    }
}
