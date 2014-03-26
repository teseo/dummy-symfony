<?php

namespace Projects\PersonalWebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ProjectsPersonalWebsiteBundle:web:home/index.html.twig', array('name' => $name));
    }
}
