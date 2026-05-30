var produkte = [
    {
        id: 1,
        name: "Whey Protein",
        kategorie: "proteine",
        beschreibung: "Protein Pulver für Muskelaufbau und Regeneration.",
        details: "Whey Protein unterstützt dich nach dem Training beim Muskelaufbau. Es ist einfach zuzubereiten und ideal für Shakes.",
        preis: "24,99 €",
        vorrat: "20 Stück",
        bild: "../res/img/whey-protein.jpg"
    },
    {
        id: 2,
        name: "Proteinriegel",
        kategorie: "proteine",
        beschreibung: "Ein einfacher Snack mit hohem Proteingehalt.",
        details: "Der Proteinriegel ist praktisch für unterwegs und liefert schnell Energie nach dem Training.",
        preis: "2,49 €",
        vorrat: "50 Stück",
        bild: "../res/img/proteinriegel.jpg"
    },
    {
        id: 3,
        name: "Veganes Protein",
        kategorie: "proteine",
        beschreibung: "Pflanzliches Protein als Alternative zu Whey.",
        details: "Veganes Protein eignet sich für alle, die auf tierische Produkte verzichten möchten.",
        preis: "27,99 €",
        vorrat: "15 Stück",
        bild: "../res/img/veganes-protein.jpg"
    },
    {
        id: 4,
        name: "Vitamin D",
        kategorie: "vitamine",
        beschreibung: "Vitaminprodukt zur Unterstützung von Knochen und Immunsystem.",
        details: "Vitamin D unterstützt Knochen, Muskeln und das Immunsystem besonders in der dunklen Jahreszeit.",
        preis: "9,99 €",
        vorrat: "35 Stück",
        bild: "../res/img/vitamin-d.jpg"
    },
    {
        id: 5,
        name: "Vitamin C",
        kategorie: "vitamine",
        beschreibung: "Ein klassisches Vitaminprodukt für den Alltag.",
        details: "Vitamin C unterstützt das Immunsystem und ist ein beliebtes Alltagsprodukt.",
        preis: "7,99 €",
        vorrat: "40 Stück",
        bild: "../res/img/vitamin-c.jpg"
    },
    {
        id: 6,
        name: "Multivitamin",
        kategorie: "vitamine",
        beschreibung: "Mehrere Vitamine in einem einfachen Produkt.",
        details: "Multivitamin kombiniert mehrere wichtige Vitamine in einem Produkt.",
        preis: "14,99 €",
        vorrat: "25 Stück",
        bild: "../res/img/multivitamin.jpg"
    },
    {
        id: 7,
        name: "Shaker",
        kategorie: "zubehoer",
        beschreibung: "Ein einfacher Shaker für Proteinshakes und andere Getränke.",
        details: "Der Shaker ist ideal für Proteinshakes im Gym, in der Arbeit oder unterwegs.",
        preis: "6,99 €",
        vorrat: "60 Stück",
        bild: "../res/img/shaker.jpg"
    },
    {
        id: 8,
        name: "Trainingshandschuhe",
        kategorie: "zubehoer",
        beschreibung: "Handschuhe für besseren Griff beim Training.",
        details: "Trainingshandschuhe helfen dir bei schweren Übungen und verbessern den Griff.",
        preis: "12,99 €",
        vorrat: "18 Stück",
        bild: "../res/img/trainingshandschuhe.jpg"
    },
    {
        id: 9,
        name: "Sporttasche",
        kategorie: "zubehoer",
        beschreibung: "Eine praktische Tasche für Gym, Arbeit oder Freizeit.",
        details: "Die Sporttasche bietet genug Platz für Kleidung, Shaker, Handtuch und Zubehör.",
        preis: "29,99 €",
        vorrat: "12 Stück",
        bild: "../res/img/sporttasche.jpg"
    }
];

function produkteAnzeigen(kategorie, suchbegriff) {
    var produktListe = document.getElementById("product-list");

    if (produktListe == null) {
        return;
    }

    suchbegriff = suchbegriff.toLowerCase();

    produktListe.innerHTML = "";

    for (var i = 0; i < produkte.length; i++) {

        if (produkte[i].kategorie == kategorie) {

            var name = produkte[i].name.toLowerCase();

            if (suchbegriff == "" || name.indexOf(suchbegriff) != -1) {

                produktListe.innerHTML +=
                    '<div class="product-card">' +
                        '<div class="product-image"><img src="' + produkte[i].bild + '" alt="' + produkte[i].name + '"></div>' +
                        '<h3>' + produkte[i].name + '</h3>' +
                        '<p>' + produkte[i].beschreibung + '</p>' +
                        '<p><strong>Preis:</strong> ' + produkte[i].preis + '</p>' +
                        '<p><strong>Vorrat:</strong> ' + produkte[i].vorrat + '</p>' +
                        '<button class="btn btn-outline" onclick="produktdetailsOeffnen(' + produkte[i].id + ')">Details</button> ' +
                        '<button class="btn btn-primary" onclick="inWarenkorbLegen(' + produkte[i].id + ')">In den Warenkorb</button>' +
                    '</div>';
            }
        }
    }
}

function produkteSuchen() {
    var suchfeld = document.getElementById("suchfeld");
    var produktListe = document.getElementById("product-list");

    if (suchfeld == null || produktListe == null) {
        return;
    }

    var kategorie = produktListe.getAttribute("data-category");
    produkteAnzeigen(kategorie, suchfeld.value);
}

function produktdetailsOeffnen(produktId) {
    window.location.href = "produktdetails.html?id=" + produktId;
}

function produktdetailsAnzeigen() {
    var detailBox = document.getElementById("produkt-detail");

    if (detailBox == null) {
        return;
    }

    var produktId = getProduktIdAusUrl();
    var produkt = null;

    for (var i = 0; i < produkte.length; i++) {
        if (produkte[i].id == produktId) {
            produkt = produkte[i];
        }
    }

    if (produkt == null) {
        detailBox.innerHTML = "<p>Produkt wurde nicht gefunden.</p>";
        return;
    }

    detailBox.innerHTML =
        '<div class="detail-card">' +
            '<div class="product-image detail-image"><img src="' + produkt.bild + '" alt="' + produkt.name + '"></div>' +
            '<div>' +
                '<p class="section-label">' + produkt.kategorie + '</p>' +
                '<h1>' + produkt.name + '</h1>' +
                '<p>' + produkt.details + '</p>' +
                '<p><strong>Preis:</strong> ' + produkt.preis + '</p>' +
                '<p><strong>Vorrat:</strong> ' + produkt.vorrat + '</p>' +
                '<button class="btn btn-primary" onclick="inWarenkorbLegen(' + produkt.id + ')">In den Warenkorb</button> ' +
                '<a href="' + produkt.kategorie + '.html" class="btn btn-outline">Zurück</a>' +
            '</div>' +
        '</div>';
}

function getProduktIdAusUrl() {
    var url = window.location.href;
    var teile = url.split("id=");

    if (teile.length > 1) {
        return parseInt(teile[1]);
    }

    return 0;
}

function inWarenkorbLegen(produktId) {
    var produkt = null;

    for (var i = 0; i < produkte.length; i++) {
        if (produkte[i].id == produktId) {
            produkt = produkte[i];
        }
    }

    if (produkt != null) {
        var preis = produkt.preis.replace(" €", "");
        preis = preis.replace(",", ".");

        window.location.href = "../../Backend/logic/cart.php?action=add&id=" + produkt.id + "&name=" + produkt.name + "&price=" + preis;
    }
}

window.onload = function () {
    var produktListe = document.getElementById("product-list");

    if (produktListe != null) {
        var kategorie = produktListe.getAttribute("data-category");
        produkteAnzeigen(kategorie, "");
    }

    produktdetailsAnzeigen();
};