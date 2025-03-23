$(document).ready(function () {
    let subscriptions = [];
    let cars = [];
    let faqs = [];

    // Globalne varijable za brisanje
    let currentDeleteType = null; // "subscription", "car", ili "faq"
    let currentDeleteId = null; // ID stavke koja se briše

    // Učitaj sve podatke prilikom učitavanja stranice
    fetchSubscriptions();
    fetchCars();
    fetchFaqs();

    // Funkcija za učitavanje pretplata iz subscription.html
    function fetchSubscriptions() {
        $.get("subscription.html")
            .done(function (data) {
                let parsedHtml = $(data);
                parsedHtml.find(".plan").each(function (index) {
                    let name = $(this).find(".card-title").text().trim() || "Unknown";
                    let monthly = $(this).find(".price-monthly").text().trim() || "N/A";
                    let yearly = $(this).find(".price-yearly").text().trim() || "N/A";

                    subscriptions.push({
                        id: index + 1,
                        name: name,
                        monthly: monthly,
                        yearly: yearly
                    });
                });
                loadSubscriptionTable();
            })
            .fail(function () {
                console.error("Error loading subscription.html");
            });
    }

    // Funkcija za učitavanje automobila iz cars.html
    function fetchCars() {
        $.get("cars.html")
            .done(function (data) {
                let parsedHtml = $(data);
                parsedHtml.find(".car-card").each(function (index) {
                    let img = $(this).find("img").attr("src") || "";
                    let name = $(this).find("h4").text().trim() || "Unknown";
                    let details = $(this).find("p").first().text().split(" • ");
                    let price = $(this).find(".price strong").text().trim() || "N/A";

                    cars.push({
                        id: index + 1,
                        image: img,
                        name: name,
                        type: details[0] || "N/A",
                        passengers: details[2] ? parseInt(details[2]) : 0,
                        fuel: details[3] || "Unknown",
                        price: price
                    });
                });
                loadCarTable();
            })
            .fail(function () {
                console.error("Error loading cars.html");
            });
    }

    // Funkcija za učitavanje FAQ pitanja iz faq.html
    function fetchFaqs() {
        $.get("faq.html")
            .done(function (data) {
                let parsedHtml = $(data);
                parsedHtml.find(".faq-item").each(function (index) {
                    let question = $(this).find(".faq-question").text().trim() || "No question";
                    let answer = $(this).find(".faq-answer").text().trim() || "No answer";

                    faqs.push({
                        id: index + 1,
                        question: question,
                        answer: answer
                    });
                });
                loadFaqTable();
            })
            .fail(function () {
                console.error("Error loading faq.html");
            });
    }

    // Funkcija za prikaz pretplata u tabeli
    function loadSubscriptionTable() {
        let subscriptionTable = $("#subscriptionTable");
        subscriptionTable.empty();

        subscriptions.forEach(sub => {
            subscriptionTable.append(`
                <tr>
                    <td>${sub.id}</td>
                    <td>${sub.name}</td>
                    <td>${sub.monthly}</td>
                    <td>${sub.yearly}</td>
                    <td>
                        <button class="btn btn-warning btn-sm edit-subscription" data-id="${sub.id}">Change</button>
                        <button class="btn btn-danger btn-sm delete-subscription" data-id="${sub.id}">Delete</button>
                    </td>
                </tr>
            `);
        });
    }

    // Funkcija za prikaz automobila u tabeli
    function loadCarTable() {
        let carTable = $("#carTable");
        carTable.empty();

        cars.forEach(car => {
            carTable.append(`
                <tr>
                    <td>${car.id}</td>
                    <td><img src="${car.image}" width="50"></td>
                    <td>${car.name}</td>
                    <td>${car.type}</td>
                    <td>${car.passengers}</td>
                    <td>${car.fuel}</td>
                    <td>${car.price}</td>
                    <td>
                        <button class="btn btn-warning btn-sm edit-car" data-id="${car.id}">Change</button>
                        <button class="btn btn-danger btn-sm delete-car" data-id="${car.id}">Delete</button>
                    </td>
                </tr>
            `);
        });
    }

    // Funkcija za prikaz FAQ pitanja u tabeli
    function loadFaqTable() {
        let faqTable = $("#faqTable");
        faqTable.empty();

        faqs.forEach(faq => {
            faqTable.append(`
                <tr>
                    <td>${faq.id}</td>
                    <td>${faq.question}</td>
                    <td>${faq.answer}</td>
                    <td>
                        <button class="btn btn-warning btn-sm edit-faq" data-id="${faq.id}">Change</button>
                        <button class="btn btn-danger btn-sm delete-faq" data-id="${faq.id}">Delete</button>
                    </td>
                </tr>
            `);
        });
    }

    // Event delegacija za pretplate
    $("#subscriptionTable").on("click", ".edit-subscription", function () {
        let id = $(this).data("id");
        editSubscription(id);
    });

    $("#subscriptionTable").on("click", ".delete-subscription", function () {
        let id = $(this).data("id");
        showDeleteConfirmationModal("subscription", id);
    });

    // Event delegacija za automobile
    $("#carTable").on("click", ".edit-car", function () {
        let id = $(this).data("id");
        editCar(id);
    });

    $("#carTable").on("click", ".delete-car", function () {
        let id = $(this).data("id");
        showDeleteConfirmationModal("car", id);
    });

    // Event delegacija za FAQ
    $("#faqTable").on("click", ".edit-faq", function () {
        let id = $(this).data("id");
        editFaq(id);
    });

    $("#faqTable").on("click", ".delete-faq", function () {
        let id = $(this).data("id");
        showDeleteConfirmationModal("faq", id);
    });

    // Funkcija za prikaz modala za potvrdu brisanja
    function showDeleteConfirmationModal(type, id) {
        currentDeleteType = type;
        currentDeleteId = id;
        $("#deleteConfirmationModal").modal("show");
    }

    // Event za potvrdu brisanja
    $("#confirmDeleteButton").on("click", function () {
        if (currentDeleteType === "subscription") {
            subscriptions = subscriptions.filter(sub => sub.id !== currentDeleteId);
            loadSubscriptionTable();
        } else if (currentDeleteType === "car") {
            cars = cars.filter(car => car.id !== currentDeleteId);
            loadCarTable();
        } else if (currentDeleteType === "faq") {
            faqs = faqs.filter(faq => faq.id !== currentDeleteId);
            loadFaqTable();
        }
        $("#deleteConfirmationModal").modal("hide");
    });

    // Funkcija za editiranje pretplate
    function editSubscription(id) {
        let sub = subscriptions.find(sub => sub.id === id);
        if (sub) {
            $("#editSubId").val(sub.id);
            $("#editSubName").val(sub.name);
            $("#editSubPriceM").val(sub.monthly);
            $("#editSubPriceY").val(sub.yearly);
            $("#editSubscriptionModal").modal("show");
        }
    }

    // Funkcija za čuvanje promjena pretplate
    function saveSubscriptionChanges() {
        let id = $("#editSubId").val();
        let name = $("#editSubName").val();
        let monthly = $("#editSubPriceM").val();
        let yearly = $("#editSubPriceY").val();

        let sub = subscriptions.find(sub => sub.id == id);
        if (sub) {
            sub.name = name;
            sub.monthly = monthly;
            sub.yearly = yearly;
            loadSubscriptionTable();
            $("#editSubscriptionModal").modal("hide");
        }
    }

    // Funkcija za editiranje automobila
    function editCar(id) {
        let car = cars.find(car => car.id === id);
        if (car) {
            $("#editCarId").val(car.id);
            $("#editCarName").val(car.name);
            $("#editCarType").val(car.type);
            $("#editCarPassengers").val(car.passengers);
            $("#editCarFuel").val(car.fuel);
            $("#editCarPrice").val(car.price);
            $("#editCarImage").val(car.image);
            $("#editCarModal").modal("show");
        }
    }

    // Funkcija za čuvanje promjena automobila
    function saveCarChanges() {
        let id = $("#editCarId").val();
        let name = $("#editCarName").val();
        let type = $("#editCarType").val();
        let passengers = $("#editCarPassengers").val();
        let fuel = $("#editCarFuel").val();
        let price = $("#editCarPrice").val();
        let image = $("#editCarImage").val();

        let car = cars.find(car => car.id == id);
        if (car) {
            car.name = name;
            car.type = type;
            car.passengers = passengers;
            car.fuel = fuel;
            car.price = price;
            car.image = image;
            loadCarTable();
            $("#editCarModal").modal("hide");
        }
    }

    // Funkcija za editiranje FAQ pitanja
    function editFaq(id) {
        let faq = faqs.find(faq => faq.id === id);
        if (faq) {
            $("#editFaqId").val(faq.id);
            $("#editFaqQuestion").val(faq.question);
            $("#editFaqAnswer").val(faq.answer);
            $("#editFaqModal").modal("show");
        }
    }

    // Funkcija za čuvanje promjena FAQ pitanja
    function saveFaqChanges() {
        let id = $("#editFaqId").val();
        let question = $("#editFaqQuestion").val();
        let answer = $("#editFaqAnswer").val();

        let faq = faqs.find(faq => faq.id == id);
        if (faq) {
            faq.question = question;
            faq.answer = answer;
            loadFaqTable();
            $("#editFaqModal").modal("hide");
        }
    }
});