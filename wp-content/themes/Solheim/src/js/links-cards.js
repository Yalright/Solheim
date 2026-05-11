window.addEventListener('DOMContentLoaded', function () {
  var roots = document.querySelectorAll('[data-links-cards]');
  if (!roots.length) {
    return;
  }

  roots.forEach(function (root) {
    var tabs = root.querySelectorAll('[data-links-cards-tab]');
    var panels = root.querySelectorAll('[data-links-cards-panel]');
    if (!tabs.length || !panels.length) {
      return;
    }

    function activate(index) {
      var i = parseInt(String(index), 10);
      if (isNaN(i)) {
        return;
      }

      tabs.forEach(function (tab, j) {
        var on = j === i;
        tab.classList.toggle('is-active', on);
        tab.setAttribute('aria-selected', on ? 'true' : 'false');
        tab.setAttribute('tabindex', on ? '0' : '-1');
      });

      panels.forEach(function (panel, j) {
        var on = j === i;
        panel.classList.toggle('is-active', on);
        if (on) {
          panel.removeAttribute('hidden');
        } else {
          panel.setAttribute('hidden', 'hidden');
        }
      });
    }

    tabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        var idx = tab.getAttribute('data-index');
        activate(idx);
      });
    });
  });
});
