<?php

namespace Dayspring\SecurityBundle\Controller;

use Dayspring\SecurityBundle\Entity\ChangePasswordEntity;
use Dayspring\SecurityBundle\Form\Type\ChangePasswordType;
use Dayspring\SecurityBundle\Form\Type\ResetPasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class ForgotResetController extends Controller
{

    /**
     * @Route("/forgot-password", name="forgot_password")
     * @Template()
     */
    public function forgotPasswordAction(Request $request)
    {
        $userProvider = $this->get('dayspring_security.user_provider');

        $form = $this->createFormBuilder(array())
            ->add('email', EmailType::class)
            ->getForm();
        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            $data = $form->getData();
            $email = $data['email'];

            try {
                $user = $userProvider->loadUserByUsername($email);

                $user->generateResetToken();

                $subject = "Reset Password";
                $data = array(
                    'user' => $user
                );
                $message = \Swift_Message::newInstance()
                    ->setSubject($subject)
                    ->setFrom(array('jwong@dayspring-tech.com' => 'Test Application'))
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                            'DayspringSecurityBundle:Emails:reset_password.html.twig',
                            $data
                        ),
                        'text/html'
                    );
                $this->get('mailer')->send($message);

                $request->getSession()->getFlashBag()->add(
                    "success",
                    "Check your email for instructions on how to reset your password."
                );
                return $this->redirect($this->generateUrl('_login'));
            } catch (UsernameNotFoundException $e) {
                $request->getSession()->getFlashBag()->add(
                    "error",
                    $e->getMessage()
                );
            }
        }
        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/reset-password/{resetToken}", name="reset_password", defaults={"resetToken"=null})
     * @Template()
     */
    public function resetPasswordAction(Request $request, $resetToken)
    {
        $userProvider = $this->get('dayspring_security.user_provider');
        $encoder = $this->get('security.password_encoder');

        $user = $userProvider->loadUserByResetToken($resetToken);
        if ($user) {
            $form = $this->createForm(ResetPasswordType::class, $user);
            if ($request->getMethod() == 'POST') {
                $form->handleRequest($request);
                if ($form->isValid()) {
                    $data = $form->getData();

                    $encoded = $encoder->encodePassword($user, $data->getPassword());
                    $user->setPassword($encoded);
                    $user->save();

                    $data->setResetToken(null);
                    $data->setResetTokenExpire(null);
                    $data->save();
                    $request->getSession()->getFlashBag()->add(
                        'success',
                        'New password has been saved, please login with new password.'
                    );
                    return $this->redirect($this->generateUrl('_login'));
                }
            }
            return array(
                'form' => $form->createView()
            );
        } else {
            throw new AccessDeniedHttpException("No User found with this reset token.");
        }
    }

    /**
     * @Route("/account/change-password", name="change_password")
     * @Template()
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function changePasswordAction(Request $request)
    {
        $session = $this->get('session');
        $authenticationManager = $this->get('security.authentication.manager');
        $encoder = $this->get('security.password_encoder');

        $currentUser = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, new ChangePasswordEntity());
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                $encoded = $encoder->encodePassword($currentUser, $data->getNewPassword());
                $currentUser->setPassword($encoded);
                $currentUser->save();

                $token = new UsernamePasswordToken(
                    $currentUser,
                    $data->getNewPassword(),
                    "secured_area",
                    $currentUser->getRoles()
                );
                $token = $authenticationManager->authenticate($token);
                $this->get("security.token_storage")->setToken($token);

                $session->getFlashBag()->add('success', 'New password has been saved.');

                return $this->redirect($this->generateUrl("account_dashboard"));
            }
        }
        return array(
            'form' => $form->createView()
        );
    }
}
