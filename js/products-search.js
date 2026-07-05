(function () {
  function normalizeQuery(value) {
    return (value || '').trim().toLowerCase();
  }

  function updateUrl(query) {
    var url = new URL(window.location.href);
    if (query) {
      url.searchParams.set('search', query);
    } else {
      url.searchParams.delete('search');
    }
    window.history.replaceState({}, '', url.pathname + url.search + url.hash);
  }

  function applySearch(searchBar, grid, noResults) {
    var query = normalizeQuery(searchBar.value);
    var cards = grid.querySelectorAll('.product-card[data-product-id]');
    var visibleCount = 0;

    cards.forEach(function (card) {
      var text = (card.getAttribute('data-search-text') || '').toLowerCase();
      var show = !query || text.indexOf(query) !== -1;
      card.classList.toggle('product-card--search-hidden', !show);
      card.hidden = !show;
      if (show) visibleCount += 1;
    });

    if (noResults) {
      noResults.hidden = visibleCount > 0;
    }
    updateUrl(query);
  }

  function initProductSearch() {
    var searchBar = document.getElementById('search-bar');
    var grid = document.getElementById('product-list');
    var noResults = document.getElementById('no-results');
    var shopForm = document.querySelector('.shop-section .shop-controls');

    if (!searchBar || !grid) return;

    if (shopForm) {
      shopForm.addEventListener('submit', function (e) {
        e.preventDefault();
        applySearch(searchBar, grid, noResults);
      });
    }

    searchBar.addEventListener('input', function () {
      applySearch(searchBar, grid, noResults);
    });

    searchBar.addEventListener('keyup', function () {
      applySearch(searchBar, grid, noResults);
    });

    applySearch(searchBar, grid, noResults);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initProductSearch);
  } else {
    initProductSearch();
  }
})();
