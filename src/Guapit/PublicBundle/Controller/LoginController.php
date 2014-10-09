<?php

namespace Guapit\PublicBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Guapit\PublicBundle\Entity\User;

class LoginController extends Controller
{
    public function indexAction()
    {
        return $this->render(
            'GuapitPublicBundle:Login:index.html.twig'
        );
    }

    public function loginAction(Request $Request)
    {
        $Session = $Request->getSession();
        $email = $Request->get('email');
        $password = $Request->get('password');

        $encodePassword = User::encodePassword($password);
        $Repository = $this->getDoctrine()->getRepository("GuapitPublicBundle:User");

        /** @var User|null $User */
        $User = $Repository->findOneBy(array(
            'email' => $email,
            'password' => $encodePassword
        ));
        if ($User) {
            $Session->set('user_id', $User->getId());
            $Session->set('user_info', $User->serialize());
            $url = $this->generateUrl('guapit_public_cabinet');
            return $this->redirect($url);
        } else {
            echo "Неверный логин/пароль";
        }
    }


    public function logoutAction()
    {
        $session = $this->getRequest()->getSession();
        $session->set('user_id', "");
        $session->set('user_info', "");
        return $this->redirect($this->generateUrl('guapit_public_homepage'));
    }

}
