<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Item;
use AppBundle\Entity\Purchase;
use AppBundle\Form\EditorItemEditType;
use AppBundle\Form\ItemType;
use AppBundle\Form\ResellItemType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class ItemController extends Controller
{
    private function uploadPicture(Item $item)
    {
        $file = $item->getImagePath();
        $path = '/../web/images/items/';
        $filename = md5($item->getName() . $item->getCategory() . $this->getUser()->getUsername());
        if (file_exists($this->get('kernel')->getRootDir() . $path . $filename . '.png'))
        {
            unlink($this->get('kernel')->getRootDir() . $path . $filename . '.png');
        }
        $file->move(
            $this->get('kernel')->getRootDir() . $path,
            $filename . '.png'
        );
        $item->setImagePath('images/items/' . $filename . '.png');
    }

    /**
     * @Route("/item/add", name="item_add")
     * @Template()
     *
     * @param Request $request
     */
    public function itemAddAction(Request $request)
    {

        if (!$this->getUser()->isEditor())
        {
            return $this->redirectToRoute('item_all');
        }

        $form = $this->createForm(ItemType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            /** @var Item $item */
            $item = $form->getData();

            $item->setCreatedAt(new \DateTime('now'));

            $item->setUser($this->getUser());

            if ($item->getImagePath() != null)
            {
                $this->uploadPicture($item);
            } else {
                $item->setImagePath('images/items/default.jpg');
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();

            return $this->redirectToRoute('item_all', [
                'info' => $item
            ]);
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/", name="item_all")
     * @Template()
     */
    public function itemShowAllAction(Request $request)
    {
        $paginator = $this->get('knp_paginator');

        $requestOrder = $request->query->get('orderBy');
        $requestIn = $request->query->get('in');

        if( $requestOrder != 'createdAt' &&
            $requestOrder != 'price' &&
            $requestOrder != 'name')
        {
            $orderBy = 'createdAt';
        }
        elseif ($requestOrder == null)
        {   $orderBy = 'createdAt'; }
        else
        {   $orderBy = $requestOrder; }

        if ($requestIn != 'asc' && $requestIn != 'desc')
        {   $in = 'desc'; }
        elseif ($requestIn == null)
        {   $in = 'desc'; }
        else
        {   $in = $requestIn; }


        $items = $paginator->paginate(
            $this->getDoctrine()->getRepository('AppBundle:Item')->orderItems($orderBy, $in),
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 9)
        );

        return [
            'items' => $items
        ];
    }

    /**
     * @Route("/item/{id}", name="item_show", requirements={"id": "\d+"})
     * @Template()
     */
    public function itemShowAction($id)
    {

        /** @var Item $item */
        $item = $this->getDoctrine()->getRepository('AppBundle:Item')->find($id);

        if ($item == null)
        {
            return $this->redirectToRoute('item_all');
        }

        if (!$this->getUser()->isEditor() && !$item->isLive())
        {
            return $this->redirectToRoute('item_all');
        }

        return [
            'item' => $item
        ];
    }

    /**
     * @Route("/item/{id}/edit", name="item_edit", requirements={"id": "\d+"})
     * @Template()
     */
    public function itemEditAction(Request $request, $id)
    {

        $item = $this->getDoctrine()->getRepository('AppBundle:Item')->find($id);

        if (!$this->getUser()->isEditor())
        {
            if ($item->getUser()->getId() != $this->getUser()->getId())
            {
                return $this->redirectToRoute('item_show', [ 'id' => $id ]);
            }
        }

        $itemPath = $item->getImagePath();

        if ($item == null) { $this->redirectToRoute('item_all'); }

        $user = $item->getUser();
        if ($user->getId() != $item->getUser()->getId()) { $this->redirectToRoute('item_all'); }

        if ($this->getUser()->isAdmin())
        {
            $form = $this->createForm(ItemType::class, $item);
        }
        elseif (!$this->getUser()->isAdmin() && $this->getUser()->isEditor())
        {
            $form = $this->createForm(EditorItemEditType::class, $item);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            if ($form->has('imagePath') &&
                $form->get('imagePath')->getData() != '' && $form->get('imagePath')->getData() != null) {
                $this->uploadPicture($item);
            }
            else {
                $item->setImagePath($itemPath);
            }

            $item->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();

            return $this->redirectToRoute('item_show', [ 'id' => $item->getId() ]);
        }

        return [
            'item' => $item,
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/item/{id}/delete", name="item_delete")
     * @Method("POST")
     */
    public function itemDeleteProcess(Item $item)
    {
        if (!$this->getUser()->isEditor())
        {
            return $this->redirectToRoute('item_all');
        }

        $em = $this->getDoctrine()->getManager();

        $carts = $em->getRepository('AppBundle:Cart')->findBy([ 'item' => $item->getId()]);

        foreach ($carts as $cart)
        {
            $em->remove($cart);
        }

        $item->setDeletedAt(new \DateTime('now'));

        $em->persist($item);
        $em->flush();
        return $this->redirectToRoute("user_profile", [ 'id' => $this->getUser()->getId() ]);
    }

    /**
     * @Route("/item/{id}/status", name="change_item_status")
     * @Method("POST")
     */
    public function setIsLiveAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $item = $em->getRepository('AppBundle:Item')->find($id);

        $item->setIsLive(!$item->isLive());
        $em->persist($item);
        $em->flush();

        return $this->redirectToRoute('item_all');
    }


    /**
     * @Route("/item/{id}/resell", name="item_resell")
     * @Method("POST")
     * @Template()
     */
    public function reSellAction(Purchase $purchase, Request $request)
    {
        if ($purchase->isResold())
        {
            return $this->redirectToRoute('profile_purchases', [ 'id' => $this->getUser()->getId() ]);
        }

        $em = $this->getDoctrine()->getManager();

        $item = clone $purchase->getItem();

        $item->setId(null);
        $item->setQuantity($purchase->getQuantity());
        $item->setUser($this->getUser());

        $form = $this->createForm(ResellItemType::class, $item);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $item = $form->getData();

            $purchase->setResold(true);
            $item->setName('[Resold] ' . $item->getName());

            $em->persist($item);
            $em->flush();

            return $this->redirectToRoute('profile_purchases', ['id' => $this->getUser()->getId()]);
        }

        return [
            'form' => $form->createView()
        ];
    }

}
