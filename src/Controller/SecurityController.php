<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $encoder)
{
    $user = new User();
    $form = $this->createForm(RegistrationType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        dump($form->getData());
        $hash = $encoder->hashPassword($user, $user->getPassword());
        $user->setPassword($hash);
        $manager->persist($user);
        $manager->flush();

        return $this->redirectToRoute('security_login');
    }

    return $this->render('security/registration.html.twig', [
        'form' => $form->createView()
    ]);
}
        /**
         * @Route("/connexion", name="security_login")
         */
        public function login(){
        if($this->getUser()){
            $this->redirectToRoute('blog',[]);
        }
        return $this->render('security/login.html.twig');
        }
        /**
         * @Route("/deconnexion", name="security_logout")
         */
        public function logout(){
        return $this->render('security/logout.html.twig');
        }

}
