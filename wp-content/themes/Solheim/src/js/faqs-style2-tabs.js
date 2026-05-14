window.addEventListener('DOMContentLoaded', function () {
  var blocks = document.querySelectorAll('[data-faqs-style2]');
  if (!blocks.length) {
    return;
  }

  blocks.forEach(function (section) {
    var tabs = section.querySelectorAll('[data-faqs-s2-tab]');
    var panels = section.querySelectorAll('[data-faqs-s2-panel]');
    if (!tabs.length || !panels.length || tabs.length !== panels.length) {
      return;
    }

    function activate(index) {
      var i;
      for (i = 0; i < tabs.length; i++) {
        var on = i === index;
        tabs[i].classList.toggle('is-active', on);
        tabs[i].setAttribute('aria-selected', on ? 'true' : 'false');
        tabs[i].tabIndex = on ? 0 : -1;
        if (on) {
          panels[i].removeAttribute('hidden');
        } else {
          panels[i].setAttribute('hidden', 'hidden');
        }
      }
    }

    tabs.forEach(function (tab, index) {
      tab.addEventListener('click', function () {
        activate(index);
      });
    });

    var tablist = section.querySelector('.faqs__s2-tabs');
    if (tablist) {
      tablist.addEventListener('keydown', function (e) {
        if (e.key !== 'ArrowRight' && e.key !== 'ArrowLeft') {
          return;
        }
        var active = section.querySelector('.faqs__s2-tab.is-active');
        if (!active) {
          return;
        }
        var idx = parseInt(active.getAttribute('data-faqs-s2-index'), 10);
        if (isNaN(idx)) {
          return;
        }
        e.preventDefault();
        var next =
          e.key === 'ArrowRight'
            ? (idx + 1) % tabs.length
            : (idx - 1 + tabs.length) % tabs.length;
        activate(next);
        tabs[next].focus();
      });
    }
  });
});
