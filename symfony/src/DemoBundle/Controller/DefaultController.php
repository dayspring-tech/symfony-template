<?php

namespace DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('DemoBundle:Default:index.html.twig', array());
    }

    /**
     * @Route("/angular")
     */
    public function angularAction()
    {
        return $this->render('DemoBundle:Default:angular.html.twig', array());
    }

    /**
     * @Route("/secure", name="demo_secure")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function secureAction()
    {
        return $this->render('DemoBundle:Default:angular.html.twig', array());
    }
}
