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
