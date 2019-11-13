<?php

namespace Drupal\hello\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;

/**
 * Class Countusers
 *
 * @Block(
 *     id = "countusers_block",
 *     admin_label = @Translation("Count users")
 * )
 */
class Countusers extends BlockBase
{
    /**
     * @return array
     */
    public function build()
    {
        $database = \Drupal::database();

        $session_num = $database->select('sessions', 's')
            ->countQuery()
            ->execute()
            ->fetchField();


        $build = [
            '#markup' => $this->t('Il y a %count connecté(s) sur le site.',
            [
                '%count' => $session_num
            ]

            )
        ];

        return $build;

    }

    /**
     * @param AccountInterface $account
     *
     * @return AccessResult
     */
    protected function blockAccess(AccountInterface $account)
    {
        return AccessResult::allowedIfHasPermission($account, 'hello_permission');
    }

}