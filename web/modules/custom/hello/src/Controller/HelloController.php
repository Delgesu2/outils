<?php
namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;

class HelloController extends ControllerBase {

    public function content()
    {
        $message = $this->t('Hello @username !', [
            '@username' => $this->currentUser()->getDisplayName()
        ]);
        return ['#markup' => $message];
    }
}
