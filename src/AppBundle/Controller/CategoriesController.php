<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class CategoriesController extends Controller
{
    /**
     * @Route("/item/category/{id}", name="view_category", requirements={"id": "\d+"})
     * @Template()
     */
    public function categoryAction(Request $request, $id)
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
        {
            $in = 'desc';
        }
        elseif ($requestIn == null)
        {   $in = 'desc'; }
        else
        {   $in = $requestIn; }

        $items = $paginator->paginate(
            $this->getDoctrine()->getRepository('AppBundle:Item')->orderItems($orderBy, $in, $id),
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 9)
        );

        $repo = $this->getDoctrine()->getRepository('AppBundle:Category');
        $category = $repo->find($id);

        return [
            'category' => $category,
            'items' => $items
        ];
    }

}
