<?php

namespace AppBundle\Controller;

use AppBundle\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EditorController extends Controller
{
    /**
     * @Route("/category/add", name="add_category")
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
}
