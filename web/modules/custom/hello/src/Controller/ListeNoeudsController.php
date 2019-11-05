<?php
/**
 * Created by PhpStorm.
 * User: POE10
 * Date: 05/11/2019
 * Time: 15:54
 */

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class ListeNoeudsController
 *
 * @package Drupal\hello\Controller
 */
class ListeNoeudsController extends ControllerBase
{
    /**
     * @param null $nodetype
     *
     * @return array
     *
     * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
     * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
     * @throws \Drupal\Core\Entity\EntityMalformedException
     */
    public function content($nodetype = NULL)
    {
        // Manipuler les noeuds
        $node_storage = $this->entityTypeManager()->getStorage('node');

        // Faire requêtes sur les noeuds
        $query = $node_storage->getQuery();

        // Filtre si argument dans URL
        if ($nodetype) {
            $query->condition('type', $nodetype);
        }

        // Récupère les ids des noeuds
        $nids = $query->pager(5)->execute();

        // Récupère les noeuds
        $nodes = $node_storage->loadMultiple($nids);

        $items = [];

        foreach ($nodes as $node) {
            $items[] = $node->toLink();
        }

        $list = [
            '#theme'  => 'item_list',
            '#items'  => $items
            ];

        $pager = ['#type' => 'pager'];

        return [$pager, $list];
    }
}