<?php

namespace Drupal\hello\Routing;

use \Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

class HelloRoutingSubscriber extends RouteSubscriberBase
{
    public function alterRoutes(RouteCollection $collection)
    {
        $collection->get('entity.user.canonical')->setRequirements(['_access_hello' => '48']);
    }
}