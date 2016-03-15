<?php

namespace Dayspring\SecurityBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends Controller
{

    /**
     * @Route("/login", name="_login")
     * @Template
     */
    public function loginAction()
    {
        $helper = $this->get('security.authentication_utils');

        return array(
            // last username entered by the user (if any)
            'last_username' => $helper->getLastUsername(),
            // last authentication error (if any)
            'error' => $helper->getLastAuthenticationError(),
        );
    }

    /**
     * @Route("/_login_check", name="_login_check")
     */
    public function loginCheckAction()
    {
        // will never be executed
    }

    /**
     * @Route("/logout", name="_logout")
     */
    public function logoutAction(Request $request)
    {
    }
}
