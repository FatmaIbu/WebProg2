$(function() {
  var app = $.spapp({
    defaultView: "home",
    templateDir: "views/",  // point to your views folder
    pageNotFound: "404.html"  // optional
  });

  // Define routes
  app.route({ view: "home", load: "index.html" });
  app.route({ view: "shop", load: "shop.html" });
  app.route({ view: "about", load: "about.html" });
  app.route({ view: "blog", load: "blog.html" });
  app.route({ view: "contact", load: "contact.html" });
  app.route({ view: "signin", load: "signin.html" });
  app.route({ view: "cart", load: "cart.html" });

  app.run();
  // Listen to route changes
  $(document).on("spapp:routeChange", function(event, view) {
    // Check if current view is 'shop'
    if (view === "shop") {
      // Initialize Isotope for the product grid
      var $grid = $('.product-grid').isotope({
        itemSelector: '.col-12',
        layoutMode: 'fitRows'
      });

      // Filter items on button click
      $('.product-filters').on('click', 'li', function() {
        var filterValue = $(this).attr('data-filter');
        $grid.isotope({ filter: filterValue });

        // Update active class
        $('.product-filters li').removeClass('active');
        $(this).addClass('active');
      });

      // Handle product clicks (SPA navigation)
      $('.product-item').on('click', function(e) {
        e.preventDefault();
        var productUrl = $(this).attr('href');
        window.location.hash = productUrl;
      });
    }
  });
});
