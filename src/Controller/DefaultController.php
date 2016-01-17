<?php

namespace Dspacelabs\Bundle\ShopifyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DspacelabsShopifyBundle:Default:index.html.twig');
    }
}
