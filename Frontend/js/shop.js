// Diese Datei steuert die Produktseiten, den Warenkorb und die Bestellung.
// Sie wird von proteine.php, vitamine.php, zubehoer.php und warenkorb.php geladen.

// Beim Laden der Seite: Cart-Badge in der Navbar aktualisieren
// und ggf. die Produkte für die aktuelle Kategorie laden.
$(document).ready(function() {

    updateCartBadge();

    // Wenn eine Produktliste auf der Seite ist (proteine/vitamine/zubehoer)
    var produktListe = $("#product-list");
    if (produktListe.length > 0) {
        var kategorie = produktListe.data("category");
        produkteLaden(kategorie, "");

        // Suchfeld: bei jedem Tastendruck neue Suche (Continuous Search)
        $("#suchfeld").on("input", function() {
            produkteLaden(kategorie, $(this).val());
        });
    }

    // Wenn die Warenkorb-Seite gerade offen ist
    if ($("#cart-container").length > 0) {
        warenkorbLaden();
    }

    // Drag-and-Drop-Ziel: Warenkorb-Symbol in der Navbar
    var dropZiel = $("#cart-link");
    if (dropZiel.length > 0) {
        dropZiel.on("dragover", function(e) {
            e.preventDefault();
            $(this).addClass("drop-hover");
        });
        dropZiel.on("dragleave", function() {
            $(this).removeClass("drop-hover");
        });
        dropZiel.on("drop", function(e) {
            e.preventDefault();
            $(this).removeClass("drop-hover");
            var produktId = e.originalEvent.dataTransfer.getData("text/plain");
            inWarenkorbLegen(produktId);
        });
    }
});


// Produkte einer Kategorie laden – mit optionalem Suchbegriff
function produkteLaden(kategorie, suchbegriff) {

    var url = "../../Backend/logic/search_products.php?category=" + kategorie + "&q=" + encodeURIComponent(suchbegriff);

    $.get(url, function(response) {
        var liste = $("#product-list");
        liste.empty();

        if (!response.success || response.products.length == 0) {
            liste.html("<p>Keine Produkte gefunden.</p>");
            return;
        }

        for (var i = 0; i < response.products.length; i++) {
            var p = response.products[i];

            // Bewertung als Sterne darstellen (z.B. 4.6 → "★★★★★ 4.6")
            var sterne = sterneAnzeigen(p.rating);

            var karte =
                '<div class="product-card" draggable="true" data-id="' + p.id + '">' +
                    '<div class="product-image"><img src="../res/img/' + p.image + '" alt="' + p.name + '"></div>' +
                    '<h3>' + p.name + '</h3>' +
                    '<p>' + p.description + '</p>' +
                    '<p><strong>Preis:</strong> ' + p.price + ' €</p>' +
                    '<p><strong>Bewertung:</strong> ' + sterne + '</p>' +
                    '<button class="btn btn-primary" onclick="inWarenkorbLegen(' + p.id + ')">In den Warenkorb</button>' +
                '</div>';

            liste.append(karte);
        }

        // Drag-Start für jede Karte aktivieren
        $(".product-card").on("dragstart", function(e) {
            e.originalEvent.dataTransfer.setData("text/plain", $(this).data("id"));
        });

    }, "json");
}


// Bewertung 4.6 → "★★★★★ (4.6)"
function sterneAnzeigen(rating) {
    var voll = Math.round(rating);
    var s = "";
    for (var i = 0; i < 5; i++) {
        s += (i < voll) ? "★" : "☆";
    }
    return s + " (" + rating + ")";
}


// Produkt in den Warenkorb legen (per AJAX, ohne Page-Reload)
function inWarenkorbLegen(produktId) {
    $.post("../../Backend/logic/cart.php?action=add", { id: produktId }, function(response) {
        if (response.success) {
            $("#cart-badge").text(response.count);
        } else {
            alert(response.error);
        }
    }, "json");
}


// Cart-Badge in der Navbar aktualisieren (Anzahl Produkte)
function updateCartBadge() {
    $.get("../../Backend/logic/cart.php?action=count", function(response) {
        if (response.success) {
            $("#cart-badge").text(response.count);
        }
    }, "json");
}


// =========================================================
// Warenkorb-Seite
// =========================================================

function warenkorbLaden() {
    $.get("../../Backend/logic/cart.php?action=list", function(response) {
        var container = $("#cart-container");
        container.empty();

        if (!response.success || response.items.length == 0) {
            container.html("<p>Dein Warenkorb ist leer.</p>");
            $("#checkout-area").hide();
            return;
        }

        $("#checkout-area").show();

        for (var i = 0; i < response.items.length; i++) {
            var item = response.items[i];
            var zwischensumme = (item.price * item.quantity).toFixed(2);

            var zeile =
                '<div class="cart-item">' +
                    '<h3>' + item.name + '</h3>' +
                    '<p>Einzelpreis: ' + item.price + ' €</p>' +
                    '<p>Zwischensumme: ' + zwischensumme + ' €</p>' +
                    '<button class="btn btn-outline" onclick="cartMinus(' + item.id + ')">-</button> ' +
                    '<span style="font-weight:bold; margin:0 10px;">' + item.quantity + '</span>' +
                    '<button class="btn btn-outline" onclick="cartPlus(' + item.id + ')">+</button> ' +
                    '<button class="btn btn-primary" onclick="cartRemove(' + item.id + ')" style="margin-left:20px;">Entfernen</button>' +
                '</div>';

            container.append(zeile);
        }

        $("#cart-total").text(response.total.toFixed(2) + " €");

    }, "json");
}

function cartPlus(id) {
    $.post("../../Backend/logic/cart.php?action=plus", { id: id }, function() {
        warenkorbLaden();
        updateCartBadge();
    }, "json");
}

function cartMinus(id) {
    $.post("../../Backend/logic/cart.php?action=minus", { id: id }, function() {
        warenkorbLaden();
        updateCartBadge();
    }, "json");
}

function cartRemove(id) {
    $.post("../../Backend/logic/cart.php?action=remove", { id: id }, function() {
        warenkorbLaden();
        updateCartBadge();
    }, "json");
}


// =========================================================
// Bestellung aufgeben
// =========================================================

function bestellen() {
    var paymentMethod = $("#payment-method").val();
    var voucherCode   = $("#voucher-code").val();

    $("#order-error").text("");

    $.post("../../Backend/logic/order.php", {
        payment_method: paymentMethod,
        voucher_code:   voucherCode
    }, function(response) {
        if (response.success) {
            window.location.href = "bestellbestaetigung.php";
        } else {
            if (response.error == "Bitte einloggen.") {
                // User ist nicht eingeloggt → zum Login schicken
                window.location.href = "login.php";
            } else {
                $("#order-error").text(response.error);
            }
        }
    }, "json");
}