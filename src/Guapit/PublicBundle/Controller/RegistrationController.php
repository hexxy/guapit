<?php
namespace Guapit\PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Guapit\PublicBundle\Entity\User;

class RegistrationController extends Controller
{
    public function indexAction()
    {
        return $this->render(
            'GuapitPublicBundle:Registration:index.html.twig'
        );
    }

    public function successAction()
    {
        return $this->render(
            'GuapitPublicBundle:Registration:success.html.twig'
        );
    }

    public function createAction(Request $request)
    {
        //@TODO вывод ошибок

        //@TODO посылать письмо после успешной реги


        $em = $this->getDoctrine()->getManager();
        $validationResult = $this->validate($request);
        if ($validationResult['result']) {
            $user = $this->getUserEntity($request);
            $em->persist($user);
            $em->flush();
            return $this->redirect($this->generateUrl('guapit_public_reg_success'));
        }

        return $this->render(
            'GuapitPublicBundle:Registration:index.html.twig',
            array('errors' => $validationResult['errors'],
                  'request' => $request
            )
        );
    }

    private function validate($request)
    {
        $result = true;
        $errors = array();

        // name
        $name =  $request->request->get('first_name');
        if (!$name)
        {
            $result = false;
            $errors['first_name'] = 'Имя не должно быть пустым';
        }

        // lastname
        $lastname =  $request->request->get('last_name');
        if (!$lastname)
        {
            $result = false;
            $errors['last_name'] = 'Фамилия не должна быть пустой';
        }

        // university
        $uni =  $request->request->get('university');
        if (!$uni)
        {
            $result = false;
            $errors['university'] = 'Пожалуйста, заполните название вашего университета';
        }
        // speciality
        $spec =  $request->request->get('speciality');
        if (!$spec)
        {
            $result = false;
            $errors['speciality'] = 'Пожалуйста, заполните название вашей специальности';
        }
        // course
        $course =  $request->request->get('course');
        if (!is_numeric($course))
        {
            $result = false;
            $errors['speciality'] = "Значение поля 'Курс' должно быть числовым";
        }
        
        // email
        $email =  $request->request->get('email');
        if (!$email)
        {
            $result = false;
            $errors['speciality'] = "Поле 'Email' обязательно для заполнения";
        }
        else
        {
            // check unique
            $repo = $this->getDoctrine()->getRepository("GuapitPublicBundle:User");
            $user = $repo->findOneByEmail($email);
            if ($user)
            {
                $result = false;
                $errors['email'] = "Пользователь с таким адресом электронной почты уже зарегистрирован";
            }
        }

        // password
        $pass =  $request->request->get('password');
        if (!$pass)
        {
            $result = false;
            $errors['password'] = "Поле 'Пароль' обязательно для заполнения";
        }
        else
        {
            $repeat =  $request->request->get('password_repeat');
            if ($pass !== $repeat)
            {
                $result = false;
                $errors['password'] = "Введенные пароли не совпадают";
            }
        }

        return array('result' => $result, 'errors' => $errors);
    }

    private function getUserEntity($request)
    {
        $user = new User();
        $user->setEmail($request->request->get('email'));
        $user->setFirstName($request->request->get('first_name'));
        $user->setLastName($request->request->get('last_name'));
        $user->setUniversity($request->request->get('university'));
        $user->setSpeciality($request->request->get('speciality'));
        $user->setCourse($request->request->get('course'));
        $user->setPhone($request->request->get('phone'));
        $user->setRoleId($request->request->get('role'));
        $user->setAbout($request->request->get('about'));
        $pass = $request->request->get('password');
        $pass = User::encodePassword($pass);
        $user->setPassword($pass);
        return $user;
    }

}