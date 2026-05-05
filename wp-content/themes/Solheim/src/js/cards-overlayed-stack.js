(function () {
  function applyOrder(stack) {
    var cards = stack.querySelectorAll(".cards-overlayed__card");
    cards.forEach(function (card, index) {
      card.style.setProperty("--card-index", String(index));
      card.classList.toggle("is-active", index === 0);
    });
  }

  function initStack(stack) {
    if (!stack) {
      return;
    }
    applyOrder(stack);

    stack.addEventListener("click", function (e) {
      var clickedCard = e.target.closest(".cards-overlayed__card");
      if (!clickedCard || !stack.contains(clickedCard)) {
        return;
      }
      var cards = Array.prototype.slice.call(
        stack.querySelectorAll(".cards-overlayed__card")
      );
      if (!cards.length || cards[0] === clickedCard) {
        return;
      }
      stack.insertBefore(clickedCard, cards[0]);
      applyOrder(stack);
    });
  }

  window.addEventListener("DOMContentLoaded", function () {
    document
      .querySelectorAll(".cards-overlayed__stack")
      .forEach(function (stackEl) {
        initStack(stackEl);
      });
  });
})();
