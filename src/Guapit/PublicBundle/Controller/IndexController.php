<?php

namespace Guapit\PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        //@TODO сделать восстановление пароля
        //@TODO заменять блок с юзером

        return $this->render('GuapitPublicBundle:Index:index.html.twig');
    }

    public function contestAction()
    {
        return $this->render('GuapitPublicBundle:Index:contest.html.twig');
    }

    public function mobileRulesAction()
    {
        return $this->render('GuapitPublicBundle:Index:rules1.html.twig');
    }

    public function idRulesAction()
    {
        return $this->render('GuapitPublicBundle:Index:rules2.html.twig');
    }

    public function notFoundAction()
    {
        return $this->render('GuapitPublicBundle:Index:404.html.twig');
    }

    public function forbiddenAction()
    {
        return $this->render('GuapitPublicBundle:Index:forbidden.html.twig');
    }

    public function programAction()
    {
        return $this->render('GuapitPublicBundle:Index:program.html.twig');
    }

    public function contactsAction()
    {
        return $this->render('GuapitPublicBundle:Index:contacts.html.twig');
    }

}
