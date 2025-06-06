// ✅ Dostupan globalno
function loadAvailableCars() {
    const token = localStorage.getItem("user_token");

    if ($("#carSelect").length && token) {
        RestClient.get("cars/available", function (cars) {
            let options = '<option selected disabled>Choose your car</option>';
            cars.forEach(car => {
                options += `<option value="${car.id}" data-price="${car.price_per_day}" data-label="${car.brand} ${car.model}">
                    ${car.brand} ${car.model}
                </option>`;
            });
            $('#carSelect').html(options);
        });
    } else {
        $('#carSelect').html('<option selected disabled>You must be logged in</option>');
    }
}

$(document).ready(function () {
    const token = localStorage.getItem("user_token");

    loadAvailableCars(); // ✅ poziv odmah

    $('#carSelect').on('change', function () {
        const selected = $('#carSelect option:selected');
        const price = selected.data('price');
        const model = selected.data('label');
        $('#pricePerDay').val(price + " €");
        $('#modalCarModel').text(model);
        updateReservationTotal();
    });

    $('#startDate, #endDate').on('change', updateReservationTotal);

    function updateReservationTotal() {
        const start = new Date($('#startDate').val());
        const end = new Date($('#endDate').val());
        const price = parseFloat($('#carSelect option:selected').data('price'));
        const days = (end - start) / (1000 * 60 * 60 * 24) + 1;

        if (!isNaN(days) && !isNaN(price) && days > 0) {
            const total = (days * price).toFixed(2);
            $('#totalprice_reservation').val(total + " €");
            $('#modalTotalPrice').text(total + " €");
            $('#modalDates').text($('#startDate').val() + " do " + $('#endDate').val());
        } else {
            $('#totalprice_reservation').val("");
            $('#modalTotalPrice').text("");
            $('#modalDates').text("");
        }
    }

    $('#paymentForm').on('submit', function (e) {
        e.preventDefault();

        const token = localStorage.getItem("user_token");
        const parsed = Utils.parseJwt(token);
        if (!parsed || !parsed.user) {
            toastr.error("You must be logged in!");
            return;
        }

        const startDate = new Date($('#startDate').val());
        const endDate = new Date($('#endDate').val());
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        if (startDate < today || endDate < today) {
            toastr.error("Datumi moraju biti u budućnosti.");
            return;
        }

        if (endDate < startDate) {
            toastr.error("Datum završetka mora biti poslije datuma početka.");
            return;
        }

        const cardNumber = $('#cardNumber').val().replace(/\s+/g, '');
        if (!/^\d{16}$/.test(cardNumber)) {
            toastr.error("Broj kartice mora imati tačno 16 cifara.");
            return;
        }

        const expiry = $('#expiryDate').val();
        if (!/^\d{2}\/\d{2}$/.test(expiry)) {
            toastr.error("Datum isteka mora biti u formatu MM/YY.");
            return;
        }

        const [expMonth, expYear] = expiry.split('/').map(Number);
        const now = new Date();
        const expDate = new Date(2000 + expYear, expMonth);
        if (isNaN(expDate) || expDate <= now) {
            toastr.error("Datum isteka mora biti u budućnosti.");
            return;
        }

        const cvc = $('#cvv').val();
        if (!/^\d{3}$/.test(cvc)) {
            toastr.error("CVC mora imati tačno 3 cifre.");
            return;
        }

        const reservation = {
            car_id: $('#carSelect').val(),
            start_date: $('#startDate').val(),
            end_date: $('#endDate').val(),
            total_price: parseFloat($('#totalprice_reservation').val().replace(/[^\d.]/g, '')),
            card_number: cardNumber,
            expiry_date: expiry,
            cvc: cvc
        };

        RestClient.post("reservations", reservation, function () {
            toastr.success("Rezervacija uspješna!");
            $('#paymentModal').modal('hide');

            setTimeout(() => {
                $('.modal-backdrop').remove();

                $('body').removeClass('modal-open');
                $('body').removeAttr('style');
                $('body').css({
                    overflow: 'auto',
                    position: 'static',
                    top: 'auto',
                    paddingRight: ''
                });

                $('html').removeAttr('style');
                $('html').css({
                    overflow: 'auto',
                    position: 'static'
                });

                $(window).scrollTop(0);
            }, 300);

            $('#paymentForm')[0].reset();
            $('#startDate').val('');
            $('#endDate').val('');
            $('#totalprice_reservation').val('');
            $('#pricePerDay').val('');

            loadAvailableCars(); // ✅ reload dropdown odmah nakon rezervacije
        }, function (err) {
            console.error(err);
            toastr.error("Greška pri rezervaciji.");
        });
    });
});
