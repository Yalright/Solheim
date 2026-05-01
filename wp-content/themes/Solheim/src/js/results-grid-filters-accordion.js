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