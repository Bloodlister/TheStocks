<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ItemPromotion;
use AppBundle\Form\AddPromotionType;
use AppBundle\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EditorController extends Controller
{
    /**
     * @Route("/category/add", name="add_category")
     * @Security("has_role('ROLE_EDITOR')")
     * @Template()
     */
    public function addCategoryAction(Request $request)
    {
        if ($this->getUser())
        {
            if (!$this->getUser()->isEditor())
            {
                return $this->redirectToRoute('item_all');
            }
        }

        $form = $this->createForm(CategoryType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $category = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/item/{id}/promotion", name="add_item_promotion")
     * @Security("has_role='ROLE_EDITOR'")
     * @Method("POST")
     * @Template()
     */
    public function addPromotionAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $item = $this->getDoctrine()->getRepository('AppBundle:Item')->find($id);

        $form = $this->createForm(AddPromotionType::class, $item->getItemDiscount());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            if ($item->getItemDiscount() != null)
            {
                $em->remove($item->getItemDiscount());
            }
            /** @var ItemPromotion $promotion */
            $promotion = $form->getData();
            $promotion->setItem($item);

            $em->persist($promotion);
            $em->flush();
            return $this->redirectToRoute('item_show', ['id' => $item->getId()]);
        }

        return [
            'form' => $form->createView(),
            'item' => $item
        ];

    }
}
