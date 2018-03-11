<?php


namespace App\Controller\Front;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="front.default.index")
     *
     * @return Response
     *
     * @throws \InvalidArgumentException
     */
    public function indexController(): Response
    {
        return new Response('Wallet index controller');
    }
}
