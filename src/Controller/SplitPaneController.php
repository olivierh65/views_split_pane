<?php

namespace Drupal\views_split_pane\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\node\Entity\Node;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 *
 */
class SplitPaneController extends ControllerBase {

  /**
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * Constructeur avec injection du service RequestStack.
   */
  public function __construct(RequestStack $request_stack) {
    $this->requestStack = $request_stack;
  }

  /**
   * Factory pour l’injection de dépendances.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('request_stack')
    );
  }

  /**
   *
   */
  public function load(Node $node) {

    $request = $this->requestStack->getCurrentRequest();

    $view_mode = $request->query->get('view_mode') ?? 'full';

    $build = \Drupal::entityTypeManager()
      ->getViewBuilder('node')
      ->view($node, $view_mode);

    $response = new AjaxResponse();
    $response->addCommand(new HtmlCommand('#split-pane-target', $build));
    return $response;

  }

}
