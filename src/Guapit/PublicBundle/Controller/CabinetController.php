<?php

namespace Guapit\PublicBundle\Controller;
use Guapit\PublicBundle\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Guapit\PublicBundle\Entity\User;

class CabinetController extends Controller
{
    private $_request;
    protected $_user;

    /**
     * @return null|User
     */
    private function _getUser()
    {
        if ($this->_user)
        {
            return $this->_user;
        }

        $session = $this->_request->getSession();
        $user_id = $session->get('user_id');
        if (!$user_id)
        {
            return null;
        }
        $repository = $this->getDoctrine()->getRepository("GuapitPublicBundle:User");
        $user = $repository->findOneBy(array(
            'id' => $user_id,
        ));
        $this->_user = $user;
        return $user;

    }

    private function _checkLoggedIn()
    {
        $user = $this->_getUser();
        if (!$user)
        {
            return false;
            //redirect to forbidden
    //        throw new AccessDeniedException();
        }
        return true;
    }

    public function indexAction(Request $Request)
    {
        $this->_request = $Request;
        $this->_checkLoggedIn();
        $user = $this->_getUser();

        $repository = $this->getDoctrine()->getRepository("GuapitPublicBundle:Project");
        $projects = $repository->findBy(array(
            'owner_id' => $user->getId(),
        ));
        return $this->render(
            'GuapitPublicBundle:Cabinet:index.html.twig', array('projects' => $projects)
        );
    }

    public function participantsAction(Request $request)
    {
        $this->_request = $request;
        $logged = $this->_checkLoggedIn();
        if (!$logged)
        {
            return $this->redirect($this->generateUrl('guapit_public_login'));
        }
        $user = $this->_getUser();

        $repository = $this->getDoctrine()->getRepository("GuapitPublicBundle:User");

        $users = $repository->findAll();

        $repository = $this->getDoctrine()->getRepository("GuapitPublicBundle:Project");
        $projects = $repository->findBy(array(
            'owner_id' => $user->getId(),
        ));

        $user_ids = array();
        foreach ($projects as $project)
        {
            $members = $project->getMembers();
            foreach ($members as $member)
            {
                $user_ids[] = $member->getId();
            }
        }
        //@TODO выводить только разработчиков и дизайнеров
        return $this->render(
            'GuapitPublicBundle:Cabinet:participants.html.twig', array('participants' => $users, 'projects' => $projects, 'projects_users' => $user_ids)
        );
    }

    public function viewAction($id, Request $request)
    {
        //@TODO проверка доступа

        $this->_request = $request;
        $this->_checkLoggedIn();

        $repository = $this->getDoctrine()->getRepository("GuapitPublicBundle:User");
        $user = $repository->findOneBy(array(
            'id' => $id,
        ));

        if (!$user)
        {
            $this->redirect($this->generateUrl('guapit_public_404'));
            // redirect to 404
        }

        return $this->render(
            'GuapitPublicBundle:Cabinet:view.html.twig', array('user' => $user)
        );
    }

    public function projectAction($id)
    {
        if ($id && $id != 0)
        {

            $repository = $this->getDoctrine()->getRepository("GuapitPublicBundle:Project");
            $project = $repository->findOneBy(array(
                'id' => $id,
            ));
        }
        else
        {
            $project = null;
        }
        return $this->render(
            'GuapitPublicBundle:Cabinet:edit.html.twig', array('project' => $project)
        );
    }

    public function editProjectAction(Request $request)
    {
        //@TODO проверка доступа
        //@TODO проверка типа проекта
        //@TODO вывод ошибок

        $this->_request = $request;
        $this->_checkLoggedIn();

        $errors = array();
        $em = $this->getDoctrine()->getManager();
        $name = $request->get('name');
        $description = $request->get('description');
        if ($name && $description) {
            $project = $this->getProjectEntity($request);
            $em->persist($project);
            $em->flush();
            return $this->redirect($this->generateUrl('guapit_public_cabinet'));
        }

        return $this->render(
            'GuapitPublicBundle:Cabinet:edit.html.twig', array('project' => $project, 'errors' => $errors)
        );
    }

    private function getProjectEntity($request)
    {
        $id = $request->get('project_id');
        if ($id)
        {
            $repository = $this->getDoctrine()->getRepository("GuapitPublicBundle:Project");
            $project = $repository->findOneBy(array(
                'id' => $id,
            ));
            if (!$project)
            {
                $project = new Project();
            }
        }
        else
        {
            $project = new Project();
        }

        $name = $request->get('name');
        $description = $request->get('description');
        $type = $request->get('type');
        $project->setName($name);
        $project->setDescription($description);
        $project->setOwnerId($this->_getUser());
        $project->setType($type);
        return $project;
    }

    public function addUserAction(Request $request)
    {
        $user_id = $request->get('user_id');
        $project_id = $request->get('project_id');
        $project = $this->getDoctrine()->getRepository("GuapitPublicBundle:Project")->find($project_id);
        $user= $this->getDoctrine()->getRepository("GuapitPublicBundle:User")->find($user_id);
        $user->addProject($project);

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($user);
        $em->flush();
        return $this->redirect($this->generateUrl('guapit_public_participants'));
    }

}
