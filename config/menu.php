<?php

return [
    [
        "header" => "Dashboard",
        "items" => [
            [
                "label" => "Dashboard",
                "icon" => "ph-house",
                "route" => "dashboard",
                "permission" => null,
            ],
        ],
    ],
    [
        "header" => "Master Data",
        "items" => [
            [
                "label" => "Services",
                "icon" => "ph-list-plus",
                "route" => "services.index",
                "permission" => "VIEW SERVICES",
            ],
            [
                "label" => "Customers",
                "icon" => "ph-users",
                "route" => "customers.index",
                "permission" => "VIEW CUSTOMERS",
            ],
        ],
    ],
    [
        "header" => "Transaksi",
        "items" => [
            [
                "label" => "Order",
                "icon" => "ph-shopping-cart",
                "route" => "orders.index",
                "permission" => "VIEW ORDERS",
            ],
            [
                "label" => "Pembayaran",
                "icon" => "ph-cash",
                "route" => "orders.payment.create",
                "permission" => "VIEW PAYMENTS",
            ],
        ],
    ],
    [
        "header" => "Laporan",
        "items" => [
            [
                "label" => "Harian",
                "icon" => "ph-calendar",
                "route" => "reports.daily",
                "permission" => "VIEW REPORTS",
            ],
            [
                "label" => "Bulanan",
                "icon" => "ph-calendar-dots",
                "route" => "reports.monthly",
                "permission" => "VIEW REPORTS",
            ],
            [
                "label" => "Transaksi",
                "icon" => "ph-list-checks",
                "route" => "reports.transactions",
                "permission" => "VIEW REPORTS",
            ],
        ],
    ],
];