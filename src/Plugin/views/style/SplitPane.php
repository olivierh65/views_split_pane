<?php

namespace Drupal\views_split_pane\Plugin\views\style;

use Drupal\views\Plugin\views\style\StylePluginBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Style plugin pour affichage Liste + Détail AJAX.
 *
 * @ViewsStyle(
 *   id = "split_pane",
 *   title = @Translation("Split pane (Liste + Détail AJAX)"),
 *   help = @Translation("Affichage en deux colonnes avec chargement AJAX."),
 *   theme = "views_style_split_pane",
 *   display_types = {"normal"}
 * )
 */
class SplitPane extends StylePluginBase {

  protected $usesFields = TRUE;

  /**
   *
   */
  public function defineOptions() {
    $options = parent::defineOptions();
    $options['left_field'] = ['default' => ''];
    $options['left_field_title'] = ['default' => TRUE];
    $options['right_field'] = ['default' => ''];
    $options['right_field_title'] = ['default' => TRUE];
    $options['empty_message'] = ['default' => $this->t('Sélectionnez un élément.')];
    return $options;
  }

  /**
   *
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);

    $fields = $this->displayHandler->getFieldLabels();

    $form['left_field'] = [
      '#type' => 'select',
      '#title' => $this->t('Champ affiché dans la liste (gauche)'),
      '#options' => $fields,
      '#default_value' => $this->options['left_field'],
      '#required' => TRUE,
    ];

    $form['left_field_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Titre du champ gauche'),
      '#default_value' => $this->options['left_field_title'],
    ];

    $form['right_field'] = [
      '#type' => 'select',
      '#title' => $this->t('Champ affiché dans le panneau de détail (droite)'),
      '#options' => $fields,
      '#default_value' => $this->options['right_field'],
      '#required' => TRUE,
    ];

    $form['right_field_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Titre du champ droite'),
      '#default_value' => $this->options['right_field_title'],
    ];

    $form['empty_message'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Message initial'),
      '#default_value' => $this->options['empty_message'],
    ];
  }

  /**
   *
   */
  public function render() {

    parent::render();

    return [
      '#theme' => $this->themeFunctions(),
      '#view' => $this->view,
      '#rows' => $this->rendered_fields,
      '#options' => $this->options,
      '#attached' => [
        'library' => ['views_split_pane/split_pane'],
      ],
    ];
  }

}
