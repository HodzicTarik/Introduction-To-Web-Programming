    $(document).ready(function () {
    if (typeof $.spapp !== "function") {
        console.error("ERROR: spapp.js nije ispravno učitan!");
        return;
    }

    var app = $.spapp({ defaultView: "home", pageNotFound: "home" });

    // Definišemo rute
    app.route({
  view: "home",
  onReady: function () {
    initCarousel();

    if (sessionStorage.getItem("refresh_home") === "true") {
      loadAvailableCars();
      sessionStorage.removeItem("refresh_home"); // brišemo signal
    }
  }
});
    app.route({ view: "cars", load: "cars.html" });
    app.route({ view: "subscription", load: "subscription.html", onReady: function () { initPricingToggle(); } });
    app.route({ view: "faq", load: "faq.html", onReady: function () { initFAQ(); } });
    app.route({ view: "contact", load: "contact.html", onReady: () => {} });
    app.route({ view: "admin", load: "admin.html", onReady: initAdmin });


    app.run();

    // Kada se promijeni hash, sakrij sve stranice osim aktivne
    $(window).on("hashchange", function () {
        var id = location.hash.replace("#", "") || "home";

        $("main#spapp > section").hide(); // Sakrij sve
        $("#" + id).show(); // Prikazi samo aktivnu

        // Sakrij home sadržaj ako si na drugoj stranici
        if (id !== "home") {
            $("#home").hide();
        } else {
            $("#home").show();
        }
    });

    $(window).trigger("hashchange");
});


document.addEventListener("spap-onpagechange", function (event) {
    console.log("🔄 Page changed to:", event.detail.page);
    if (event.detail.page === "pricing") {
        console.log("✅ Pricing page detected, initializing...");
        initPricingPage();
    }
});

