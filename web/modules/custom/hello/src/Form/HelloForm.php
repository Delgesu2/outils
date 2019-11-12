<?php

namespace Drupal\hello\Form;

use Drupal\Core\Ajax\AfterCommand;
use Drupal\Core\Ajax\AnnounceCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * Class HelloForm
 *
 * @package Drupal\hello\Form
 */
class HelloForm extends FormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'hello_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        if (isset($form_state->getRebuildInfo()['result'])) {

            $form['result'] = [
                '#type'  => 'html_tag',
                '#tag'   => 'h2',
                '#value' => $this->t('Result: ') . $form_state->getRebuildInfo()['result'],
            ];
        }

        $form['first_value'] = [
            '#type'  => 'textfield',
            '#title' => $this->t('First value'),
            '#ajax'  => [
                'callback' => [
                    $this,
                    'validateTextAjax'
                ],
                'event' => 'change'
            ],
            '#suffix' => '<span class="message"></span>'
        ];

        $form['operation'] = [
            '#type'    => 'radios',
            '#title'   => $this->t('Operation'),
            '#options' => [
                0 => $this->t('Ajouter'),
                1 => $this->t('Soustract'),
                2 => $this->t('Multiply'),
                3 => $this->t('Divide')
                ]
            ];

        $form['second_value'] = [
            '#type'  => 'textfield',
            '#title' => $this->t('Second value'),
            '#ajax'  => [
                'callback' => [
                    $this,
                    'validateTextAjax'
                ],
                'event' => 'change'
            ],
            '#suffix' => '<span class="message"></span>'
        ];

        $form['submit'] = [
            '#type'  => 'submit',
            '#value' => 'Calculate'
        ];

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateTextAjax(array $form, FormStateInterface $form_state)
    {
        $response = new AjaxResponse();

        $field = $form_state->getTriggeringElement()['#name'];

        if (is_numeric($form_state->getValue($field))) {
            $css = ['border' => '2px solid green'];
            $message = $this->t('Ok !');
        } else {

            $css = ['border' => '2px solid red'];
            $message = $this->t('%field must be numeric', ['%field' => $form[$field]['#title']]);
        }

        $response->AddCommand(new CssCommand("[name=$field]", $css));
        $response->AddCommand(new HtmlCommand('#error-message-' . $field, $message));

        return $response;

    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        $first_field_value  = $form_state->getValue('first_value');
        if (!is_numeric($first_field_value)) {
            $form_state->setErrorByName('first_value', $this->t('First value must be numeric'));
        }

        $second_field_value = $form_state->getValue('second_value');
        if (!is_numeric($second_field_value)) {
            $form_state->setErrorByName('second_value', $this->t('Second value must be numeric'));
        }

        $operation = $form_state->getValue('operation');
        if ($form_state->getValue('second_value') == '0' && $operation == 3 ) {
            $form_state->setErrorByName('second_value', $this->t('Second valut must be an integer'));
        }

    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $first_field_value  = $form_state->getValue('first_value');
        $second_field_value = $form_state->getValue('second_value');
        $operation = $form_state->getValue('operation');

        $result = '';
        switch ($operation) {
            case 0:
                $result =  $first_field_value + $second_field_value;
                break;
            case 1:
                $result = $first_field_value - $second_field_value;
                break;
            case 3:
                $result = $first_field_value * $second_field_value;
                break;
            case 4:
                $result = $first_field_value / $second_field_value;
                break;
        }

        //\Drupal::messenger()->addMessage($result);

        $form_state->addRebuildInfo('result', $result);

        $form_state->setRebuild();

    }

}