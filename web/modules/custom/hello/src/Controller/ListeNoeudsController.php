<?php
/**
 * Created by PhpStorm.
 * User: POE10
 * Date: 05/11/2019
 * Time: 15:54
 */

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

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
        // Affichage types de contenu
        $node_types = $this->entityTypeManager()->getStorage('node_type')->loadMultiple();

        $node_type_items = [];

        foreach ($node_types as $node_type) {

            $url = new Url('hello.noeuds', ['nodetype' => $node_type->id()]);
            $node_type_link = new Link($node_type->label(), $url);
            $node_type_items[] = $node_type_link;

        }

        $node_type_list = [
            '#theme' => 'item_list',
            '#items' => $node_type_items,
            '#title' => $this->t('Filter by node type')
        ];


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
            $items[] = $node->toLink();  // création des noeuds
        }

        $list = [
            '#theme'  => 'item_list',
            '#items'  => $items
            ];

        $pager = ['#type' => 'pager'];

        return [$node_type_list, $list, $pager];
    }
}