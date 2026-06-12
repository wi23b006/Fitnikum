// Steuert die drei Admin-Seiten: Produkte, Kunden, Gutscheine.
// Beim Seitenladen wird je nach vorhandener Tabelle die passende Funktion aufgerufen.

$(document).ready(function() {

    if ($("#products-table").length > 0) {
        loadAdminProducts();
        $("#product-form").on("submit", function(e) {
            e.preventDefault();
            saveProduct();
        });
    }

    if ($("#customers-table").length > 0) {
        loadAdminCustomers();
    }

    if ($("#vouchers-table").length > 0) {
        loadAdminVouchers();
        $("#voucher-form").on("submit", function(e) {
            e.preventDefault();
            createVoucher();
        });
    }
});


// =============== PRODUKTE ===============

var adminProducts = [];

function loadAdminProducts() {
    $.get("../../Backend/logic/admin_products.php?action=list", function(response) {
        if (!response.success) {
            alert(response.error);
            return;
        }
        adminProducts = response.products;

        var tbody = $("#products-table tbody");
        tbody.empty();

        for (var i = 0; i < adminProducts.length; i++) {
            var p = adminProducts[i];
            tbody.append(
                "<tr>" +
                    "<td>" + p.id + "</td>" +
                    "<td>" + p.name + "</td>" +
                    "<td>" + p.category_name + "</td>" +
                    "<td>" + p.price + " €</td>" +
                    "<td>" + p.rating + "</td>" +
                    "<td><img src='../res/img/" + p.image + "' width='60'></td>" +
                    "<td>" +
                        "<button class='btn btn-outline' onclick='editProduct(" + p.id + ")'>Bearbeiten</button> " +
                        "<button class='btn btn-primary' onclick='deleteProduct(" + p.id + ")'>Löschen</button>" +
                    "</td>" +
                "</tr>"
            );
        }
    }, "json");
}

function editProduct(id) {
    var p = null;
    for (var i = 0; i < adminProducts.length; i++) {
        if (adminProducts[i].id == id) p = adminProducts[i];
    }
    if (!p) return;

    $("#product-form input[name=id]").val(p.id);
    $("#product-form input[name=name]").val(p.name);
    $("#product-form textarea[name=description]").val(p.description);
    $("#product-form select[name=category_id]").val(p.category_id);
    $("#product-form input[name=price]").val(p.price);
    $("#product-form input[name=rating]").val(p.rating);
    $("#product-form input[name=image]").val(""); // Datei-Feld zurücksetzen

    $("#product-form-title").text("Produkt bearbeiten (#" + p.id + ")");
    window.scrollTo(0, 0);
}

function newProduct() {
    $("#product-form")[0].reset();
    $("#product-form input[name=id]").val("");
    $("#product-form-title").text("Neues Produkt anlegen");
}

function saveProduct() {
    var form = $("#product-form")[0];
    var formData = new FormData(form);

    // Wenn id vorhanden → update, sonst create
    var hasId = $("#product-form input[name=id]").val() != "";
    var url = "../../Backend/logic/admin_products.php?action=" + (hasId ? "update" : "create");

    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function(response) {
            if (response.success) {
                alert("Gespeichert.");
                newProduct();
                loadAdminProducts();
            } else {
                alert(response.error);
            }
        }
    });
}

function deleteProduct(id) {
    if (!confirm("Produkt wirklich löschen?")) return;
    $.post("../../Backend/logic/admin_products.php?action=delete", { id: id }, function(response) {
        if (response.success) {
            loadAdminProducts();
        } else {
            alert(response.error);
        }
    }, "json");
}


// =============== KUNDEN ===============

function loadAdminCustomers() {
    $.get("../../Backend/logic/admin_customers.php?action=list", function(response) {
        if (!response.success) {
            alert(response.error);
            return;
        }

        var tbody = $("#customers-table tbody");
        tbody.empty();

        for (var i = 0; i < response.customers.length; i++) {
            var c = response.customers[i];
            var aktiv     = c.active == "1";
            var statusTxt = aktiv ? "aktiv" : "inaktiv";
            var aktionTxt = aktiv ? "Deaktivieren" : "Aktivieren";
            var neuerWert = aktiv ? 0 : 1;

            tbody.append(
                "<tr>" +
                    "<td>" + c.id + "</td>" +
                    "<td>" + c.firstname + " " + c.lastname + "</td>" +
                    "<td>" + c.username + "</td>" +
                    "<td>" + c.email + "</td>" +
                    "<td>" + statusTxt + "</td>" +
                    "<td>" +
                        "<button class='btn btn-outline' onclick='toggleCustomer(" + c.id + "," + neuerWert + ")'>" + aktionTxt + "</button> " +
                        "<button class='btn btn-primary' onclick='showCustomerOrders(" + c.id + ")'>Bestellungen</button>" +
                    "</td>" +
                "</tr>" +
                "<tr id='orders-row-" + c.id + "' style='display:none;'><td colspan='6'><div id='orders-" + c.id + "'></div></td></tr>"
            );
        }
    }, "json");
}

function toggleCustomer(id, neuerWert) {
    $.post("../../Backend/logic/admin_customers.php?action=toggle", { id: id, active: neuerWert }, function(response) {
        if (response.success) {
            loadAdminCustomers();
        }
    }, "json");
}

function showCustomerOrders(userId) {
    var row = $("#orders-row-" + userId);

    // Wenn schon offen → zuklappen
    if (row.is(":visible")) {
        row.hide();
        return;
    }

    $.get("../../Backend/logic/admin_customers.php?action=orders&user_id=" + userId, function(response) {
        var container = $("#orders-" + userId);
        container.empty();

        if (response.orders.length == 0) {
            container.html("<p>Keine Bestellungen.</p>");
        } else {
            for (var i = 0; i < response.orders.length; i++) {
                var o = response.orders[i];
                var html = "<div class='cart-item'>";
                html += "<p><strong>Bestellung #" + o.id + "</strong> vom " + o.created_at + " – Gesamt: " + o.total_price + " €</p>";
                html += "<ul>";
                for (var j = 0; j < o.items.length; j++) {
                    var it = o.items[j];
                    var isVisible = it.visible == "1";
                    html += "<li" + (isVisible ? "" : " style='text-decoration:line-through; color:#999;'") + ">";
                    html += it.product_name + " – " + it.quantity + " × " + it.price + " € ";
                    if (isVisible) {
                        html += "<button class='btn btn-outline' onclick='hideOrderItem(" + it.id + "," + userId + ")'>Ausblenden</button>";
                    } else {
                        html += "(ausgeblendet)";
                    }
                    html += "</li>";
                }
                html += "</ul></div>";
                container.append(html);
            }
        }
        row.show();
    }, "json");
}

function hideOrderItem(itemId, userId) {
    if (!confirm("Position wirklich ausblenden?")) return;
    $.post("../../Backend/logic/admin_customers.php?action=hide_item", { item_id: itemId }, function(response) {
        if (response.success) {
            // Bestellliste neu laden
            $("#orders-row-" + userId).hide();
            showCustomerOrders(userId);
        }
    }, "json");
}


// =============== GUTSCHEINE ===============

function loadAdminVouchers() {
    $.get("../../Backend/logic/admin_vouchers.php?action=list", function(response) {
        if (!response.success) {
            alert(response.error);
            return;
        }

        var tbody = $("#vouchers-table tbody");
        tbody.empty();

        for (var i = 0; i < response.vouchers.length; i++) {
            var v = response.vouchers[i];
            tbody.append(
                "<tr>" +
                    "<td>" + v.code + "</td>" +
                    "<td>" + v.value + " €</td>" +
                    "<td>" + v.remaining_value + " €</td>" +
                    "<td>" + v.expires_at + "</td>" +
                    "<td>" + v.status + "</td>" +
                "</tr>"
            );
        }
    }, "json");
}

function createVoucher() {
    var value     = $("#voucher-form input[name=value]").val();
    var expiresAt = $("#voucher-form input[name=expires_at]").val();

    $.post("../../Backend/logic/admin_vouchers.php?action=create",
        { value: value, expires_at: expiresAt },
        function(response) {
            if (response.success) {
                alert("Neuer Gutschein-Code: " + response.code);
                $("#voucher-form")[0].reset();
                loadAdminVouchers();
            } else {
                alert(response.error);
            }
        }, "json");
}