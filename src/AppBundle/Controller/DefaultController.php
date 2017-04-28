<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{

    /**
     * @Route("/testarea", name="test")
     * @Template()
     */
    public function debugAction()
    {
        $items = $this->getDoctrine()->getRepository('AppBundle:Item')->getAllItems();

        dump($items);
        exit;

    }
}