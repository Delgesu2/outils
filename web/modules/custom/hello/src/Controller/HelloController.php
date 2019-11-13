<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class HelloController
 *
 * @package Drupal\hello\Controller
 */
class HelloController extends ControllerBase {

    /**
     * @return array
     */
    public function content()
    {
        $message = $this->t('Hello @username !', [
            '@username' => $this->currentUser()->getDisplayName()
        ]);
        return ['#markup' => $message];
    }
}
