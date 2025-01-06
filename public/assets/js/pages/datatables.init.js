$(document).ready(function () {
    $("#datatable").DataTable({
        lengthMenu: [[100, 200, 300, 500], [100, 200, 300, 500]],
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
    $(".multi-datatable").DataTable({
        lengthMenu: [[100, 200, 300, 500], [100, 200, 300, 500]],
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
        lengthMenu: [[100, 200, 300, 500], [100, 200, 300, 500]],
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
        buttons: ["copy", "excel", "pdf", "colvis"],
    });
    a
        .buttons()
        .container()
        .appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),
        $(".dataTables_length select").addClass("form-select form-select-sm"),
        $("#selection-datatable").DataTable({
            lengthMenu: [[100, 200, 300, 500], [100, 200, 300, 500]],
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
            lengthMenu: [[100, 200, 300, 500], [100, 200, 300, 500]],
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
            lengthMenu: [[100, 200, 300, 500], [100, 200, 300, 500]],
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
            lengthMenu: [[100, 200, 300, 500], [100, 200, 300, 500]],
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
            lengthMenu: [[100, 200, 300, 500], [100, 200, 300, 500]],
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
            lengthMenu: [[100, 200, 300, 500], [100, 200, 300, 500]],
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
