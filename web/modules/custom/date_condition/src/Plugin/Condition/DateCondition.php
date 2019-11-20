<?php

namespace Drupal\date_condition\Plugin\Condition;

use Drupal\Core\Condition\ConditionPluginBase;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Date' condition to enable a condition based in module selected status.
 *
 * @Condition(
 *   id = "date_condition",
 *   label = @Translation("Date")
 * )
 *
 */
class DateCondition extends ConditionPluginBase {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition
    );
  }

  /**
   * Creates a new DateCondition object.
   *
   * @param array $configuration
   *   The plugin configuration, i.e. an array with configuration values keyed
   *   by configuration option name. The special key 'context' may be used to
   *   initialize the defined contexts by setting it to an array of context
   *   values keyed by context names.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {

    $form['date1'] = [
        '#type' => 'date',
        '#title' => $this->t('Choisir une date de début'),
        '#default_value' => [
            'month' => format_date(time(), 'custom', 'n'),
            'day'   => format_date(time(), 'custom', 'j'),
            'year'  => format_date(time(), 'custom', 'Y')
        ]
        ];


    $form['date2'] = [
            '#type' => 'date',
            '#title' => $this->t('Choisir une date de fin'),
            '#default_value' => [
                'month' => format_date(time(), 'custom', 'n'),
                'day'   => format_date(time(), 'custom', 'j'),
                'year'  => format_date(time(), 'custom', 'Y')
            ],
        ];

      return $form;

  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration['date1'] = $form_state->getValue('date1');
    $this->configuration['date2'] = $form_state->getValue('date2');
    parent::submitConfigurationForm($form, $form_state);
  }

    /**
     * @param array $form
     * @param FormStateInterface $form_state
     */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state)
  {
      if (!empty($form_state->getValue('date1')) && !empty($form_state->getValue('date2'))) {
          $start_date = new DrupalDateTime($form_state->getValue('date1'));
          $end_date = new DrupalDateTime($form_state->getValue('date2'));

          if ($end_date < $start_date) {
              $form_state->setErrorByName('date2', $this->t('End date error !'));
          }
      }
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
        'date1' => '',
        'date2' => '',
    ] + parent::defaultConfiguration();
  }

  /**
   * Evaluates the condition and returns TRUE or FALSE accordingly.
   *
   * @return bool
   *   TRUE if the condition has been met, FALSE otherwise.
   */
  public function evaluate() {

      $today = new DrupalDateTime('today');

      $start = $this->configuration['date1'] ? new DrupalDateTime($this->configuration['date1']) : NULL;
      $end = $this->configuration['date2'] ? new DrupalDateTime($this->configuration['date2']) : NULL;

      return (!$start || ($start <= $today)) && (!$end || ($end >= $today));
    }

  /**
   * Provides a human readable summary of the condition's configuration.
   */
  public function summary() {
    $module = $this->getContextValue('module');
    $modules = system_rebuild_module_data();

    $status = ($modules[$module]->status)?t('enabled'):t('disabled');

    return t('The module @module is @status.', ['@module' => $module, '@status' => $status]);
  }

}
