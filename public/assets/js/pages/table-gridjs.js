document.getElementById("table-gridjs") &&
    new gridjs.Grid({
        columns: [
            {
                name: "ID",
                width: "80px",
                formatter: function (e) {
                    return gridjs.html(
                        '<span class="fw-semibold">' + e + "</span>"
                    );
                },
            },
            { name: "Produk", width: "150px" },
            {
                name: "Total Pesan",
                width: "150px",
            },
            { name: "Harga", width: "130px" },
            { name: "Total Harga", width: "150px" },
            { name: "Tanggal Pemesanan", width: "250px" },
            { name: "Status Pengiriman", width: "250px" },
            { name: "Status Pengiriman", width: "250px" },
        ],
        pagination: { limit: 5 },
        sort: !0,
        search: !0,
        data: [
            [
                "01",
                "Jonathan",
                "jonathan@example.com",
                "Senior Implementation Architect",
                "Hauck Inc",
                "Holy See",
            ],
        ],
    }).render(document.getElementById("table-gridjs"));
