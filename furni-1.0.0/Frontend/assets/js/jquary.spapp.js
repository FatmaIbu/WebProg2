(function ($) {

  $.spapp = function (options) {

    // --- Default Configuration ---
    const config = $.extend({
      defaultView: $("main#spapp > section:first-child").attr("id") || "home",
      templateDir: "./", // adjusted to your frontend/views/ directory
      pageNotFound: false
    }, options);

    // --- Route Registry ---
    const routes = {};

    // Collect all declared routes
    $("main#spapp > section").each(function () {
      const $el = $(this);
      const id = $el.attr("id");

      routes[id] = {
        view: id,
        load: $el.data("load") || null,
        onCreate: function () { },
        onReady: function () { }
      };
    });

    // --- Allow programmatic route extension ---
    this.route = function (opts) {
  if (!opts.view) return console.warn("⚠️ view is required");
  if (!routes[opts.view]) {
    routes[opts.view] = {
      view: opts.view,
      load: opts.load || null,
      onCreate: opts.onCreate || function () {},
      onReady: opts.onReady || function () {}
    };
  } else {
    $.extend(routes[opts.view], opts);
  }
};


    // --- Core Route Handler ---
    const routeChange = function () {
      let id = window.location.hash.slice(1);
      if (!id) id = config.defaultView;

      const route = routes[id];
      const $el = $("#" + id);

      // Handle page not found
      if (!route || $el.length === 0) {
        if (config.pageNotFound && routes[config.pageNotFound]) {
          window.location.hash = "#" + config.pageNotFound;
        } else {
          console.error(`❌ Route "${id}" not defined and no 404 page found.`);
        }
        return;
      }

      // Load content
      if ($el.hasClass("spapp-created")) {
        // Page already loaded once → just trigger onReady
        route.onReady();
      } else {
        $el.addClass("spapp-created");

        if (!route.load) {
          // No external file to load
          route.onCreate();
          route.onReady();
        } else {
          $el.load(config.templateDir + route.load, function (response, status) {
            if (status === "error") {
              console.error(`❌ Failed to load view: ${route.load}`);
              if (config.pageNotFound) window.location.hash = "#" + config.pageNotFound;
              return;
            }
            route.onCreate();
            route.onReady();
          });
        }
      }

      // Update active link styling (optional UX improvement)
      $("nav a.nav-link").removeClass("active");
      $(`nav a[href="#${id}"]`).addClass("active");
    };

    // --- SPA Runner ---
    this.run = function () {
      window.addEventListener("hashchange", routeChange);
      if (!window.location.hash) {
        window.location.hash = "#" + config.defaultView;
      } else {
        routeChange();
      }
    };

    return this;
  };

})(jQuery);
