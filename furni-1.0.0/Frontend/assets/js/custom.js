(function() {
	'use strict';

	var tinyslider = function() {
		var el = document.querySelectorAll('.testimonial-slider');

		if (el.length > 0) {
			var slider = tns({
				container: '.testimonial-slider',
				items: 1,
				axis: "horizontal",
				controlsContainer: "#testimonial-nav",
				swipeAngle: false,
				speed: 700,
				nav: true,
				controls: true,
				autoplay: true,
				autoplayHoverPause: true,
				autoplayTimeout: 3500,
				autoplayButtonOutput: false
			});
		}
	};
	tinyslider();

	


	var sitePlusMinus = function() {

		var value,
    		quantity = document.getElementsByClassName('quantity-container');

		function createBindings(quantityContainer) {
	      var quantityAmount = quantityContainer.getElementsByClassName('quantity-amount')[0];
	      var increase = quantityContainer.getElementsByClassName('increase')[0];
	      var decrease = quantityContainer.getElementsByClassName('decrease')[0];
	      increase.addEventListener('click', function (e) { increaseValue(e, quantityAmount); });
	      decrease.addEventListener('click', function (e) { decreaseValue(e, quantityAmount); });
	    }

	    function init() {
	        for (var i = 0; i < quantity.length; i++ ) {
						createBindings(quantity[i]);
	        }
	    };

	    function increaseValue(event, quantityAmount) {
	        value = parseInt(quantityAmount.value, 10);

	        console.log(quantityAmount, quantityAmount.value);

	        value = isNaN(value) ? 0 : value;
	        value++;
	        quantityAmount.value = value;
	    }

	    function decreaseValue(event, quantityAmount) {
	        value = parseInt(quantityAmount.value, 10);

	        value = isNaN(value) ? 0 : value;
	        if (value > 0) value--;

	        quantityAmount.value = value;
	    }
	    
	    init();
		
	};
	sitePlusMinus();
	document.addEventListener("DOMContentLoaded", function () {
		const filterButtons = document.querySelectorAll(".product-filters ul li");
		const products = document.querySelectorAll(".product-item");
	
		filterButtons.forEach(button => {
			button.addEventListener("click", function () {
				// Remove active class from all buttons
				filterButtons.forEach(btn => btn.classList.remove("active"));
				this.classList.add("active");
	
				const filterValue = this.getAttribute("data-filter");
	
				products.forEach(product => {
					if (filterValue === "*" || product.parentElement.classList.contains(filterValue.substring(1))) {
						product.parentElement.style.display = "block";
					} else {
						product.parentElement.style.display = "none";
					}
				});
			});
		});
	});
	document.addEventListener("DOMContentLoaded", function () {
		let cartCount = 0;
		const cartCountElement = document.getElementById("cart-count");
		const addToCartButtons = document.querySelectorAll(".add-to-cart");
	
		// Create the notification element
		const notification = document.createElement("div");
		notification.classList.add("cart-notification");
		notification.textContent = "Item added to cart!";
		document.body.appendChild(notification);
	
		addToCartButtons.forEach(button => {
			button.addEventListener("click", function () {
				// Update Cart Count
				cartCount++;
				cartCountElement.textContent = cartCount;
				cartCountElement.style.opacity = "1"; // Show badge
	
				// Show Notification
				notification.classList.add("show");
	
				// Hide Notification After 2 Seconds
				setTimeout(() => {
					notification.classList.remove("show");
				}, 2000);
			});
		});
	});
	document.addEventListener("DOMContentLoaded", function () {
		const navLinks = document.querySelectorAll(".custom-navbar-nav .nav-link");
	
		navLinks.forEach(link => {
			link.addEventListener("click", function () {
				// Remove "active" class from all items
				navLinks.forEach(nav => nav.parentElement.classList.remove("active"));
	
				// Add "active" class to the clicked item
				this.parentElement.classList.add("active");
			});
		});
	});
	document.addEventListener("DOMContentLoaded", function () {
		const navLinks = document.querySelectorAll(".custom-navbar-nav .nav-link");
		const sections = document.querySelectorAll(".section");
	
		function showSection(hash) {
			sections.forEach(section => {
				section.classList.toggle("active", section.id === hash);
			});
		}
	
		navLinks.forEach(link => {
			link.addEventListener("click", function (e) {
				e.preventDefault();
				const targetId = this.getAttribute("href").substring(1);
				
				// Update active navbar item
				navLinks.forEach(nav => nav.parentElement.classList.remove("active"));
				this.parentElement.classList.add("active");
	
				// Show the target section
				showSection(targetId);
			});
		});
	
		// Show the initial section based on URL hash (if available)
		const initialHash = window.location.hash.substring(1) || "home";
		showSection(initialHash);
	});
		
	$(document).ready(function () {
		$("main#spapp > section").height($(document).height() - 60);
	
		var app = $.spapp({ pageNotFound: 'error_404' }); // Initialize SPAPP
	
		// Define routes
		 // Define routes for ALL views
		app.route({
   		view: "home",
    	onReady: function () {
     	 // No need to load content; it's already in index.html
		  $("main#spapp > section").hide();
      	$("#home").show(); // Ensure home is visible
    	}
  		});
	
		  app.route({
			view: "shop",
			load: "shop.html",
			onReady: function() {
				$.get("views/shop.html")
					.done(function(data) {
						$("#shop").html($(data).find("section#shop").html());
					})
					.fail(function() {
						console.error("Failed to load shop view");
					});
			}
			});
		
		  app.route({
			view: "about",
			load: "about.html",
			onReady: function () {
			  $.get("about.html", function (data) {
				var content = $("<div>").html(data).find("section#about").html();
				$("#about").html(content);
			  });
			}
		  });
		
		  app.route({
			view: "cart",
			load: "cart.html",
			onReady: function () {
			  $.get("views/cart.html", function (data) {
				var content = $("<div>").html(data).find("section#cart").html();
				$("#cart").html(content);
			  });
			}
		  });
		
		  // Add routes for other views (blog, contact, etc.) similarly
		
		  // Run the SPA
		  app.run();
		});
})()