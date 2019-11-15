<?php

namespace Drupal\hello\Access;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Access\AccessCheckInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Access\AccessResult;


class HelloAccessCheck implements AccessCheckInterface
{
    /**
     * @var TimeInterface
     */
    protected $time;

    /**
     * HelloAccessCheck constructor.
     * @param TimeInterface $time
     */
    public function __construct(TimeInterface $time)
    {
        $this->time = $time;
    }


    /**
     * @param Route $route
     *
     * @return array|null
     */
    public function applies(Route $route)
    {
        return NULL;
    }

    /**
     * @param Route $route
     * @param Request|NULL $request
     * @param AccountInterface $account
     *
     * @return \Drupal\Core\Access\AccessResultAllowed|\Drupal\Core\Access\AccessResultForbidden
     *
     * Autorisation de voir statistique si utilisateur inscrit
     * depuis plus de 48 heures
     */
    public function access(
        Route $route,
        Request $request = NULL,
        AccountInterface $account
    )
    {
        $param = $route->getRequirement('_access_hello');
        $forbidden = AccessResult::forbidden();
        $created = $account->getAccount()->created;

        if (!$account->isAnonymous() && $created < ($this->time->getCurrentTime() - $param*3600 )) {
                return AccessResult::allowed()->cachePerUser();
            }

            return $forbidden->cachePerUser();
    }

}