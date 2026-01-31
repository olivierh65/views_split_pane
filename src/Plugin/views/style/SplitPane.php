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
    $options['empty_message'] = ['default' => $this->t('Sélectionnez un Compte Rendu.')];
    $options['view_mode'] = ['default' => 'full'];
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

    $form['view_mode'] = [
      '#type' => 'select',
      '#title' => $this->t('Mode de vue du Node pour la colonne droite'),
    // Liste des view modes disponibles.
      '#options' => $this->getNodeViewModes(),
      '#default_value' => $this->options['view_mode'] ?? 'full',
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

    $rows = [];

    foreach ($this->view->result as $index => $result_row) {
      $rows[$index] = [
        'fields' => $this->rendered_fields[$index],
      // Add nid for AJAX loading.
        'nid' => $result_row->nid,
      ];
    }

    return [
      '#theme' => $this->themeFunctions(),
      '#view' => $this->view,
      '#rows' => $rows,
      '#options' => $this->options,
      '#attached' => [
        'library' => ['views_split_pane/split_pane'],
      ],
    ];
  }

  /**
   *
   */
  protected function getNodeViewModes(): array {
    /** @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface $entity_display_repo */
    $entity_display_repo = \Drupal::service('entity_display.repository');

    // Retourne un tableau [machine_name => label].
    $view_modes = $entity_display_repo->getViewModes('node');

    $options = [];
    foreach ($view_modes as $machine_name => $info) {
      $options[$machine_name] = $info['label'];
    }

    return $options;
  }

}
