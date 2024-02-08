<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
class ContactController extends AbstractController
{
    #[Route("/contactus", name: "contactUs")]
    public function contactus(Request $request,  EntityManagerInterface $manager)
    {
        $contactInformation = new Contact();
        $form = $this->createForm(ContactType::class, $contactInformation);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($contactInformation);
            $manager->flush();
    
            return $this->redirectToRoute('blog', []);
        }
    
        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route("/admin/submissions", name: "seeSubmissions")]
    public function submissions(Request $request, ContactRepository $repo) : Response
    {
        $Contactus = $repo->findAll();
    
        return $this->render('contact/submissions.html.twig', [
            'Contactus' => $Contactus,
        ]);
    }
}
