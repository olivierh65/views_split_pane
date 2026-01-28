(function (Drupal) {
  Drupal.behaviors.viewsSplitPane = {
    attach(context) {
      once('viewsSplitPane', '.views-split-pane', context).forEach(container => {
        const links = container.querySelectorAll('.split-pane-link');
        const details = container.querySelectorAll('.views-split-pane__detail');
        const empty = container.querySelector('.views-split-pane__empty');

        links.forEach(link => {
          link.addEventListener('click', e => {
            e.preventDefault();

            const index = link.dataset.rowIndex;

            details.forEach(d => d.hidden = true);
            empty.hidden = true;
            details[index].hidden = false;

            links.forEach(l => l.classList.remove('is-active'));
            link.classList.add('is-active');
          });
        });
      });
    }
  };
})(Drupal);
