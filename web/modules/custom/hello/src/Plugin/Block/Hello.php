<?php

namespace Drupal\hello\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockManagerInterface;
//use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use \Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Class Hello
 * Provides a welcome block
 *
 * @Block(
 *     id = "hello_block",
 *     admin_label = @Translation("hello")
 * )
 */
class Hello extends BlockBase
{
    /**
     * @var BlockManagerInterface
     */
   // protected $blockManager;

    /**
     * @var ContainerInterface
     */
  //  protected $container;


    /**
     * @return array
     */
    public function build()
    {
        $date_formatter = \Drupal::service('date.formatter');
        $time = \Drupal::service('datetime.time')->getCurrenttime();
        $user_name = \Drupal::currentUser()->getDisplayName();

        $build = [
            '#markup' => $this->t('Bienvenue %name sur notre site. Il est %time.', [
                     '%name' => $user_name,
                     '%time' => $date_formatter->format(
                     $time,
                     'custom',
                     'H:i s\s'
                     )
                 ]),
            '#cache' => [
                'keys'     => ['hello_block'],
                'contexts' => ['user'],
                'max-age'  => '1000'
            ]
              ];

        return $build;
    }

}