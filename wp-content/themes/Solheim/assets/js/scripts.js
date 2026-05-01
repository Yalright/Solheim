window.addEventListener('DOMContentLoaded', function () {
    var toggle = document.querySelector('.site-header__menu-toggle');
    var offcanvas = document.getElementById('site-header-offcanvas');

    if (!toggle || !offcanvas) {
        return;
    }

    toggle.addEventListener('click', function () {
        var isOpen = toggle.getAttribute('aria-expanded') === 'true';
        toggle.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
        offcanvas.hidden = isOpen;
        document.body.classList.toggle('site-header-overlay-active', !isOpen);
    });
});

// Accordion logic removed.
// Wait until DOM is fully loaded before running accordion logic
window.addEventListener('DOMContentLoaded', function () {
  // Select all elements matching the selector and store in a reusable variable.
  const resultsGridFilterLabels = document.querySelectorAll(
    '.results-grid-content__filter-item .search-filter-label'
  );

  resultsGridFilterLabels.forEach(label => {
    label.addEventListener('click', function () {
      // Find the closest ancestor with the class .results-grid-content__filter-item
      const filterItem = label.closest('.results-grid-content__filter-item');
      if (filterItem) {
        if (filterItem.classList.contains('active')) {
          filterItem.classList.remove('active');
        } else {
          filterItem.classList.add('active');
        }
      }
    });
  });
});

document.addEventListener('DOMContentLoaded', function () {
    var tabBlocks = document.querySelectorAll('.js-single-venue-tabs');

    tabBlocks.forEach(function (tabBlock) {
        var buttons = tabBlock.querySelectorAll('.single-venue__tab');
        var panels = tabBlock.querySelectorAll('.single-venue__tab-panel');

        function activateTab(target) {
            buttons.forEach(function (button) {
                var isActive = button.getAttribute('data-tab-target') === target;
                button.classList.toggle('is-active', isActive);
                button.setAttribute('aria-selected', isActive ? 'true' : 'false');
            });

            panels.forEach(function (panel) {
                var isActive = panel.getAttribute('data-tab-panel') === target;
                panel.classList.toggle('is-active', isActive);
                if (isActive) {
                    panel.removeAttribute('hidden');
                } else {
                    panel.setAttribute('hidden', 'hidden');
                }
            });
        }

        buttons.forEach(function (button) {
            button.addEventListener('click', function () {
                var target = button.getAttribute('data-tab-target');
                if (target) {
                    activateTab(target);
                }
            });
        });

        var defaultButton = tabBlock.querySelector('.single-venue__tab.is-active') || buttons[0];
        if (defaultButton) {
            var defaultTarget = defaultButton.getAttribute('data-tab-target');
            if (defaultTarget) {
                activateTab(defaultTarget);
            }
        }
    });
});
