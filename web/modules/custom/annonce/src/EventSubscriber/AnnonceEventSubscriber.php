<?php

namespace Drupal\annonce\EventSubscriber;

use Drupal\Component\Datetime\Time;
use Drupal\Core\Database\Connection;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class AnnonceEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var AccountProxyInterface currentUser
     */
    protected $currentUser;

    /**
     * @var CurrentRouteMatch
     */
    protected $currentRouteMatch;

    /**
     * @var Connection
     */
    protected $database;

    /**
     * @var Time
     */
    protected  $datetime;

    /**
     * AnnonceEventSubscriber constructor.
     *
     * @param AccountProxyInterface $currentUser
     * @param CurrentRouteMatch $currentRouteMatch
     * @param Connection $database
     * @param Time $datetime
     */
    public function __construct(
        AccountProxyInterface $currentUser,
        CurrentRouteMatch     $currentRouteMatch,
        Connection            $database,
        Time                  $datetime
    )
    {
        $this->currentUser       = $currentUser;
        $this->currentRouteMatch = $currentRouteMatch;
        $this->database          = $database;
        $this->datetime          = $datetime;
    }


    /**
     * @return array|mixed
     */
    static function getSubscribedEvents()
    {
        $events[KernelEvents::REQUEST][] = ['onRequest'];
        return $events;
    }

    /**
     * @param Event $event
     * @throws \Exception
     */
    public function onRequest(Event $event)
    {
        if ($this->currentRouteMatch->getRouteName() == 'entity.annonce.canonical') {

            drupal_set_message('test ' . $this->currentUser->getAccountName());
            $this->database->insert('annonce_user_views')
                ->fields([
                        'time' => $this->datetime->getCurrentTime(),
                        'uid'  => $this->currentUser->id(),
                        'aid'  => $this->currentRouteMatch->getParameter('annonce')->id()
                    ]
                )
                ->execute();
        }
    }
}