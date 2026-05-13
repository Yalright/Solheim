/**
 * Accommodation — highlight the map pin that matches the location row
 * nearest the top of the viewport (scroll spy).
 */
window.addEventListener('DOMContentLoaded', function () {
  var sections = document.querySelectorAll('[data-accommodation-spy]');
  if (!sections.length) {
    return;
  }

  Array.prototype.forEach.call(sections, function (section) {
    var pins = section.querySelectorAll('.accommodation__hero-pin[data-accommodation-loc]');
    var lis = section.querySelectorAll('.accommodation__locations .accommodation__location[data-accommodation-loc]');
    var defaultSrc = section.getAttribute('data-accommodation-pin-default-src');
    var activeSrc = section.getAttribute('data-accommodation-pin-active-src');
    if (!pins.length || !lis.length || !defaultSrc || !activeSrc) {
      return;
    }

    var scheduled = false;

    function scrollOffset() {
      var raw = section.getAttribute('data-accommodation-scroll-offset');
      var n = parseInt(raw, 10);
      if (!isNaN(n) && n >= 0) {
        return n;
      }
      return Math.min(200, Math.max(96, Math.round(window.innerHeight * 0.18)));
    }

    function sync() {
      var offset = scrollOffset();
      var activeLoc = null;
      for (var i = 0; i < lis.length; i++) {
        var r = lis[i].getBoundingClientRect();
        if (r.top <= offset) {
          activeLoc = lis[i].getAttribute('data-accommodation-loc');
        }
      }
      if (activeLoc === null) {
        activeLoc = lis[0].getAttribute('data-accommodation-loc');
      }

      Array.prototype.forEach.call(pins, function (pin) {
        var loc = pin.getAttribute('data-accommodation-loc');
        var on = loc === activeLoc;
        pin.classList.toggle('is-active', on);
        var img = pin.querySelector('.accommodation__hero-pin-img');
        if (img) {
          img.setAttribute('src', on ? activeSrc : defaultSrc);
        }
      });
    }

    function onScrollOrResize() {
      if (scheduled) {
        return;
      }
      scheduled = true;
      window.requestAnimationFrame(function () {
        scheduled = false;
        sync();
      });
    }

    window.addEventListener('scroll', onScrollOrResize, { passive: true });
    window.addEventListener('resize', onScrollOrResize);
    onScrollOrResize();
  });
});
