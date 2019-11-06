<?php
/**
 * Created by PhpStorm.
 * User: POE10
 * Date: 06/11/2019
 * Time: 14:57
 */

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\user\UserInterface;

class UserController extends ControllerBase
{
    public function content(UserInterface $user)
    {
        $connects = \Drupal::database()->select('hello_user_statistics', 'hellouser')
            ->fields('hellouser', ['action','time'])
            ->condition('uid', $user->id())
            ->execute();


            $connexions = [];

            foreach ($connects as $connect) {
                $connexions[] = [
                    $connect->action == 1 ? $this->t('Login') : $this->t('Logout'),
                    \Drupal::service('date.formatter')->format($connect->time),
                ];
            }

            $user_type_list = [
                '#type' => 'table',
                '#header' => ['time', 'action'],
                '#rows' => $connexions
            ];

            if (!empty ($user_type_list['#rows'])) {
                return $user_type_list;
            }

            $build = ['#markup' => $this->t('Pas de connexions')];
            return $build;
    }

}