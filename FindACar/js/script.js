// Inicijalizacija karusela za testimoniale
function initCarousel() {
    let carouselElement = document.querySelector("#testimonialCarousel");
    if (carouselElement) {
        new bootstrap.Carousel(carouselElement, {
            interval: 5000,
            pause: "hover",
        });
    }
}

// Prebacivanje između mesečnih i godišnjih cena
function initPricingToggle() {
    const toggle = document.getElementById("togglePlan");
    if (!toggle) return;

    const monthlyPrices = document.querySelectorAll(".price-monthly");
    const yearlyPrices = document.querySelectorAll(".price-yearly");

    function updatePricing() {
        if (toggle.checked) {
            toggleVisibility(monthlyPrices, "d-none", true);
            toggleVisibility(yearlyPrices, "d-none", false);
        } else {
            toggleVisibility(monthlyPrices, "d-none", false);
            toggleVisibility(yearlyPrices, "d-none", true);
        }
    }

    toggle.addEventListener("change", updatePricing);
    updatePricing();
}

// Prebacivanje vidljivosti elemenata
function toggleVisibility(elements, className, addClass) {
    elements.forEach(element => element.classList.toggle(className, addClass));
}

// Inicijalizacija FAQ sekcije
function initFAQ() {
    const faqItems = document.querySelectorAll(".faq-item");

    faqItems.forEach(item => {
        const question = item.querySelector(".faq-question");
        const answer = item.querySelector(".faq-answer");
        const icon = question.querySelector(".icon");

        if (answer.classList.contains("show")) {
            answer.style.maxHeight = answer.scrollHeight + "px";
            icon.textContent = "−";
        }

        question.addEventListener("click", () => toggleFAQAnswer(answer, icon));
    });
}

// Toggle FAQ odgovora
function toggleFAQAnswer(answer, icon) {
    if (answer.style.maxHeight) {
        answer.style.maxHeight = null;
        answer.classList.remove("show");
        icon.textContent = "+";
    } else {
        answer.style.maxHeight = answer.scrollHeight + "px";
        answer.classList.add("show");
        icon.textContent = "−";
    }
}

// Otvaranje modala za login/register forme
function openAuthModal(mode) {
    const modalTitle = document.getElementById("authModalLabel");
    const registerFields = document.getElementById("register-extra-fields");
    const authForm = document.getElementById("authForm");

    if (mode === "register") {
        modalTitle.textContent = "Register";
        registerFields.style.display = "block";
        document.getElementById("toggleAuthMode").innerHTML = 
            `<a href="#" onclick="openAuthModal('login'); return false;">Already have an account? Login</a>`;
    } else {
        modalTitle.textContent = "Login";
        registerFields.style.display = "none";
        document.getElementById("toggleAuthMode").innerHTML = 
            `<a href="#" onclick="openAuthModal('register'); return false;">Don't have an account? Register</a>`;
    }

    authForm.reset();
    removeModalBackdrop();

    let authModal = new bootstrap.Modal(document.getElementById("authModal"));
    authModal.show();
}

// Uklanjanje backdrop slojeva
function removeModalBackdrop() {
    document.querySelectorAll(".modal-backdrop").forEach(el => el.remove());
    document.body.classList.remove("modal-open");
}

// Onemogućavanje scrolla prilikom otvaranja modala
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("toggleAuthMode").addEventListener("click", function (event) {
        event.preventDefault();
    });

    document.getElementById("authModal").addEventListener("hidden.bs.modal", function () {
        document.body.style.overflow = "auto";
    });
});

// Validacija datuma
function validateDates(startDate, endDate) {
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    if (!startDate || !endDate) {
        alert("Please select both start and end dates.");
        return false;
    }

    if (startDate < today) {
        alert("The start date cannot be in the past.");
        return false;
    }

    if (endDate <= startDate) {
        alert("The end date must be after the start date.");
        return false;
    }

    if ((endDate - startDate) / (1000 * 60 * 60 * 24) < 1) {
        alert("You must reserve the car for at least one full day.");
        return false;
    }

    return true;
}

$(document).ready(function () {
    $(".reservations").on("submit", function (event) {
        event.preventDefault();

        const carModel = $("#carmodel").val();
        const startDate = new Date($("#startDate").val());
        const endDate = new Date($("#endDate").val());

        if (!validateDates(startDate, endDate)) return;

        // Ažuriraj cenu i modal pre nego što se otvori
        updateTotal();
        openModal();

        // Prikazivanje modala
        $("#reservationSuccessModal").modal("show");

        $(this)[0].reset();
    });
});

// Funkcija za ažuriranje cene po danu
function updatePrice() {
    const carModel = document.getElementById("carmodel").value;
    
    // Cijene po danu za svaki automobil
    const pricePerDay = {
        "BMW": 230,
        "Audi": 180,
        "Mercedes": 250,
        "Volkswagen": 200
    };

    // Ako je model izabran, postavi cenu po danu
    if (carModel) {
        document.getElementById("pricePerDay").value = pricePerDay[carModel] + "$"; // Prikazivanje cene po danu
        updateTotal(); // Ažuriraj ukupnu cenu kad se izabere model
    } else {
        document.getElementById("pricePerDay").value = ""; // Ako nije izabran model, očisti polje
    }
}

// Funkcija za ažuriranje ukupne cijene
function updateTotal() {
    const carModel = document.getElementById("carmodel").value;
    const startDate = document.getElementById("startDate").value;
    const endDate = document.getElementById("endDate").value;

    // Cijene po danu za svaki automobil
    const pricePerDay = {
        "BMW": 230,
        "Audi": 180,
        "Mercedes": 250,
        "Volkswagen": 200
    };

    // Provjera da li su svi podaci uneseni
    if (carModel && startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        const timeDifference = end - start; // Razlika u milisekundama
        const daysDifference = timeDifference / (1000 * 60 * 60 * 24); // Pretvaranje u dane

        // Provjera da li je broj dana validan
        if (daysDifference > 0) {
            const total = pricePerDay[carModel] * daysDifference;
            document.getElementById("totalprice_reservation").value = total + "$";
        } else {
            document.getElementById("totalprice_reservation").value = "Nevažeći datumi";
        }
    } else {
        document.getElementById("totalprice_reservation").value = "";
    }
}

// Funkcija za otvaranje modala sa podacima
function openModal() {
    const carModel = document.getElementById("carmodel").value;
    const startDate = document.getElementById("startDate").value;
    const endDate = document.getElementById("endDate").value;
    const totalPrice = document.getElementById("totalprice_reservation").value;

    // Cijene po danu za svaki automobil
    const carNames = {
        "BMW": "BMW",
        "Audi": "Audi",
        "Mercedes": "Mercedes",
        "Volkswagen": "Volkswagen"
    };

    const start = new Date(startDate);
    const end = new Date(endDate);
    const formattedStartDate = new Intl.DateTimeFormat('en-GB', { year: 'numeric', month: '2-digit', day: '2-digit' }).format(start);
    const formattedEndDate = new Intl.DateTimeFormat('en-GB', { year: 'numeric', month: '2-digit', day: '2-digit' }).format(end);

    // Prikazivanje podataka u modalnom prozoru
    document.getElementById("modalCarModel").textContent = carNames[carModel] || "";
    document.getElementById("modalDates").textContent = `${formattedStartDate} do ${formattedEndDate}`;
    document.getElementById("modalTotalPrice").textContent = totalPrice || "";
}

// Event listener za "Rezerviši" dugme
document.querySelector("button[data-bs-toggle='modal']").addEventListener('click', function () {
    updateTotal(); // Ažuriraj cenu pre nego što otvorimo modal
    openModal(); // Otvori modal sa podacima
});


// Event listener za "Rezerviši" dugme
document.querySelector("button[data-bs-toggle='modal']").addEventListener('click', function () {
    updateTotal(); // Ažuriraj cenu pre nego što otvorimo modal
    openModal(); // Otvori modal sa podacima
});


// Event listener za "Rezerviši" dugme
document.querySelector("button[data-bs-toggle='modal']").addEventListener('click', function () {
    updateTotal(); // Ažuriraj cenu pre nego što otvorimo modal
    openModal(); // Otvori modal sa podacima
});


// ✅ CARS RENT NOW MODAL
function openModalCars(carName, carPrice) {
    let modalElement = document.getElementById("modal_carsReservation");
    if (!modalElement) {
        console.error("❌ Greška: Modal za rezervaciju automobila nije pronađen!");
        return;
    }

    let modalCarName = document.getElementById("modal_carsCarName");
    let modalCarPrice = document.getElementById("modal_carsCarPrice");
    let startDate = document.getElementById("modal_carsStartDate");
    let endDate = document.getElementById("modal_carsEndDate");
    let totalPrice = document.getElementById("modal_carsTotalPrice");

    modalCarName.textContent = carName;
    modalCarPrice.textContent = carPrice;
    startDate.value = "";
    endDate.value = "";
    totalPrice.textContent = "0.00";
    startDate.setAttribute("data-price", carPrice);

    let modal = new bootstrap.Modal(modalElement);
    modal.show();
}

window.openModalCars = openModalCars;

// ✅ FUNKCIJA ZA IZRAČUNAVANJE UKUPNE CIJENE
function updateTotalPriceCars() {
    let startDate = document.getElementById("modal_carsStartDate");
    let endDate = document.getElementById("modal_carsEndDate");
    let totalPrice = document.getElementById("modal_carsTotalPrice");

    if (!startDate || !endDate || !totalPrice) {
        console.error("❌ Greška: Elementi za datume ili cijenu nisu pronađeni!");
        return;
    }

    let pricePerDay = startDate.getAttribute("data-price");
    if (!pricePerDay) {
        console.error("❌ Greška: Cijena po danu nije postavljena!");
        return;
    }

    let start = new Date(startDate.value);
    let end = new Date(endDate.value);

    if (isNaN(start.getTime()) || isNaN(end.getTime())) {
        totalPrice.textContent = "0.00";
        return;
    }

    let days = (end - start) / (1000 * 60 * 60 * 24); // Konverzija milisekundi u dane

    if (days > 0) {
        let total = days * parseFloat(pricePerDay);
        totalPrice.textContent = total.toFixed(2);
    } else {
        totalPrice.textContent = "0.00";
    }
}



////////////

document.addEventListener("DOMContentLoaded", function () {
    // Klik na dugme za odabir plana
    document.body.addEventListener("click", function (event) {
        if (event.target.classList.contains("choose-plan-btn")) {
            let planName = event.target.getAttribute("data-plan");

            // Podesi naziv plana u modal
            document.getElementById("plan_name").value = planName;

            // Pokaži modal sa Bootstrap
            let modalElement = document.getElementById("subscription_modal");
            let modal = new bootstrap.Modal(modalElement);
            modal.show();
        }
    });

    // Klik na Submit Payment dugme
    document.getElementById("submit_payment").addEventListener("click", function () {
        let cardNumber = document.getElementById("card_number").value;
        let cardExpiry = document.getElementById("card_expiry").value;
        let cardCvc = document.getElementById("card_cvc").value;

        // Provera da li su sva polja popunjena
        if (cardNumber && cardExpiry && cardCvc) {
            console.log("Payment Submitted!");
            console.log("Card Number: " + cardNumber);
            console.log("Expiry Date: " + cardExpiry);
            console.log("CVC: " + cardCvc);

            // Ovde možeš dodati kod za slanje podataka na server
        } else {
            alert("Please fill in all fields.");
        }
    });
});
