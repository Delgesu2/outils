<?php

namespace Drupal\hello\Plugin\Block;

use Drupal\Core\Block\BlockBase;

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
}