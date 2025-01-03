$(document).ready(function () {
    $("#datatable").DataTable({
        language: {
            paginate: {
                previous: "<i class='mdi mdi-chevron-left'>",
                next: "<i class='mdi mdi-chevron-right'>",
            },
        },
        drawCallback: function () {
            $(".dataTables_paginate > .pagination").addClass(
                "pagination-rounded"
            );
        },
    });
    var a = $("#datatable-buttons").DataTable({
        lengthChange: !1,
        language: {
            paginate: {
                previous: "<i class='mdi mdi-chevron-left'>",
                next: "<i class='mdi mdi-chevron-right'>",
            },
        },
        drawCallback: function () {
            $(".dataTables_paginate > .pagination").addClass(
                "pagination-rounded"
            );
        },
        buttons: [
            {
                extend: "copy",
                exportOptions: {
                    // Excludes the last two columns (Status and Action)
                    columns: ':not(:nth-last-child(-n+2))' 
                }
            },
            {
                extend: "excel",
                exportOptions: {
                    // Excludes the last two columns
                    columns: ':not(:nth-last-child(-n+2))' 
                }
            },
            {
                extend: "pdf",
                exportOptions: {
                    // Excludes the last two columns
                    columns: ':not(:nth-last-child(-n+2))' 
                },
                customize: function (doc) {
                    console.log(doc);
                    
                    doc.content[1].table.widths = [ '*', '*', '*', '*', '*' ]; // Adjust widths as necessary
                    // Optional: Add extra margins, font sizes, etc.
                    doc.pageMargins = [ 40, 40, 40, 40 ]; // Top, Left, Bottom, Right
                    doc.defaultStyle.fontSize = 10; // Default font size in PDF
                    doc.styles.tableHeader.fontSize = 12; // Font size for headers
                }
            },
            {
                extend: "colvis"
            }
        ],
    });
    a
        .buttons()
        .container()
        .appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),
        $(".dataTables_length select").addClass("form-select form-select-sm"),
        $("#selection-datatable").DataTable({
            select: { style: "multi" },
            language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'>",
                    next: "<i class='mdi mdi-chevron-right'>",
                },
            },
            drawCallback: function () {
                $(".dataTables_paginate > .pagination").addClass(
                    "pagination-rounded"
                );
            },
        }),
        $("#key-datatable").DataTable({
            keys: !0,
            language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'>",
                    next: "<i class='mdi mdi-chevron-right'>",
                },
            },
            drawCallback: function () {
                $(".dataTables_paginate > .pagination").addClass(
                    "pagination-rounded"
                );
            },
        }),
        a
            .buttons()
            .container()
            .appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),
        $(".dataTables_length select").addClass("form-select form-select-sm"),
        $("#alternative-page-datatable").DataTable({
            pagingType: "full_numbers",
            drawCallback: function () {
                $(".dataTables_paginate > .pagination").addClass(
                    "pagination-rounded"
                ),
                    $(".dataTables_length select").addClass(
                        "form-select form-select-sm"
                    );
            },
        }),
        $("#scroll-vertical-datatable").DataTable({
            scrollY: "350px",
            scrollCollapse: !0,
            paging: !1,
            language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'>",
                    next: "<i class='mdi mdi-chevron-right'>",
                },
            },
            drawCallback: function () {
                $(".dataTables_paginate > .pagination").addClass(
                    "pagination-rounded"
                );
            },
        }),
        $("#complex-header-datatable").DataTable({
            language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'>",
                    next: "<i class='mdi mdi-chevron-right'>",
                },
            },
            drawCallback: function () {
                $(".dataTables_paginate > .pagination").addClass(
                    "pagination-rounded"
                ),
                    $(".dataTables_length select").addClass(
                        "form-select form-select-sm"
                    );
            },
            columnDefs: [{ visible: !1, targets: -1 }],
        }),
        $("#state-saving-datatable").DataTable({
            stateSave: !0,
            language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'>",
                    next: "<i class='mdi mdi-chevron-right'>",
                },
            },
            drawCallback: function () {
                $(".dataTables_paginate > .pagination").addClass(
                    "pagination-rounded"
                ),
                    $(".dataTables_length select").addClass(
                        "form-select form-select-sm"
                    );
            },
        });
});
