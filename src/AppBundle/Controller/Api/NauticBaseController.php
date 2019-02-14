<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\NauticBase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NauticBaseController extends Controller
{

    /**
     * @Route("/api/nauticbases")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
        return new Response();
    }
}
