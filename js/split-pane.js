(function (Drupal, once) {
  Drupal.behaviors.splitPane = {
    attach(context) {
      once('split-pane', '.split-pane-link', context).forEach(link => {
        link.addEventListener('click', e => {
          e.preventDefault();
          Drupal.ajax({
            url: link.dataset.ajaxUrl + '?view_mode=' + link.dataset.viewMode,
            wrapper: 'split-pane-target',
          }).execute();
        });
      });
    }
  };
})(Drupal, once);

