<?php

namespace Dspacelabs\Bundle\ShopifyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Dspacelabs\Component\Shopify\Client;

class AuthController extends Controller
{
    public function indexAction(Request $request)
    {
        $hmac = $request->query->get('hmac');
        $shop = $request->query->get('shop');
        $signature = $request->query->get('signature');
        $timestamp = $request->query->get('timestamp');

        $this->get('session')->set('shop', $shop);

        $key = $this->getParameter('shopify.key');
        $secret = $this->getParameter('shopify.secret');
        $client = new Client($key, $secret);
        $client->setShop($shop);
        $client->setScopes('read_products,read_customers,read_orders');

        $authUrl = $client->getAuthorizationUrl(
            $this->generateUrl('dspace_shopify_auth_callback', array(), UrlGeneratorInterface::ABSOLUTE_URL)
        );

        var_dump(
            $request->query->all(),
            $request->getMethod(),
            $authUrl
        );
        die();
        return $this->redirect($authUrl);
    }

    public function callbackAction(Request $request)
    {
        $code      = $request->query->get('code');
        $hmac      = $request->query->get('hmac');
        $shop      = $request->query->get('shop');
        $signature = $request->query->get('signature');
        $state     = $request->query->get('state');
        $timestamp = $request->query->get('timestamp');

        $key    = $this->getParameter('shopify.key');
        $secret = $this->getParameter('shopify.secret');
        $client = new Client($key, $secret);
        $client->setShop($shop);
        $accessToken = $client->getAccessToken($code);

        $customers = $client->call('get', '/admin/customers.json');

        var_dump(
            $request->query->all(),
            $accessToken,
            $customers
        );
        die();
        $this->redirct($client->getBaseUri().'/admin');
    }
}
