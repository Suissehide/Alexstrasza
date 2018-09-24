<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index()
    {
        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'controller_name' => 'SecurityController',
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em)
    {
        $session = $request->getSession();
        $user = new Utilisateur();

        $form = $this->createForm(UtilisateurType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setRoles(array('ROLE_USER'));

            $em->persist($user);
            $em->flush();
            $session->getFlashBag()->add('success', 'Félicitations ! Votre compte a été créé avec succès !');
            return $this->redirectToRoute('login');
        }

        return $this->render('security/register.html.twig', [
            'controller_name' => 'SecurityController',
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/logout", name="logout", methods="GET")
     */
    public function logout(): Response
    {
        return $this->redirectToRoute('login');
    }

        /**
     * @Route("/check_last_login", name="check_last_login")
     */
    public function checkLastLogin(EntityManagerInterface $em) {

        $user = $this->getUser();
        $roles = $user->getRoles();

        if (in_array("ROLE_USER", $roles)) {
            return $this->redirectToRoute('index');
        }

        return $this->redirectToRoute('index');

    }
}
