<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\EditUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
     * @Route("/", defaults={"id" = null})
     * @Template()
     */
    public function profileAction(Request $request, $id)
    {
        if (!$this->getUser())
            return $this->redirectToRoute('item_all');

        if ($id != null)
            $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        else
            $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($this->getUser()->getId());

        if ($user == null)
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
     * @Route("/{id}/purchases", name="profile_purchases")
     * @Security("has_role('ROLE_USER')")
     * @Template()
     */
    public function profilePurchasesAction(User $user)
    {

        if (!$this->getUser()->isAdmin())
        {
            if ($user->getId() != $this->getUser()->getId())
            {
                return $this->redirectToRoute('item_all');
            }
        }

        $purchases = $this->getDoctrine()->getRepository('AppBundle:Purchase')->getPurchasesByUser($user->getId());
        return [
            'user' => $user,
            'purchases' => $purchases
        ];
    }

    /**
     * @Route("/{id}/edit", name="edit_profile")
     * @Security("has_role='ROLE_USER'")
     * @Template()
     */
    public function editProfileAction($id, Request $request)
    {

        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);

        if (!$this->getUser()->isAdmin())
        {
            if ($user->getId() != $this->getUser()->getId() || $user == null)
            {
                return $this->redirectToRoute('item_all');
            }
        }

        $form = $this->createForm(EditUser::class, $user);

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
     * @Route("{id}/addcash", name="add_cash")
     * @Security("has_role='ROLE_USER'")
     * @Method("POST")
     */
    public function addCashAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')->find($id);

        if (!$this->getUser()->isAdmin())
        {
            if ($user->getId() != $this->getUser()->getId() || $user == null)
            {
                return $this->redirectToRoute('user_profile', [ 'id' => $this->getUser()->getId() ]);
            }
        }

        $cash = $request->get('cash');

        $user->setCash($user->getCash() + $cash);

        $em->flush();

        return $this->redirectToRoute('user_profile', [ 'id' => $this->getUser()->getId()]);
    }
}
