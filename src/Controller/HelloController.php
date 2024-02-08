<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
class HelloController {
    public function index(){
        var_dump("ca fonctionne");
        die();
    }
    public function test(Request $req, $age){
        //$age=$req->query->get('age',0);
    return new Response("Age:  $age");
    }
    /**
     * @Route("/hello/{prenom?world}", name="hello")
     */
    public function hello($prenom){
        return new Response("Hello $prenom");
    }
}