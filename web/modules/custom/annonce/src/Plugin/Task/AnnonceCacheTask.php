<?php

namespace Drupal\annonce\Plugin\Task;

use Drupal\Core\Menu\LocalTaskDefault;

class AnnonceCacheTask extends LocalTaskDefault
{
    public function getCacheContexts()
    {
        return ['user'];
    }
}