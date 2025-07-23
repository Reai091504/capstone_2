<?php
// Philippines Address Data API - Updated with Davao del Sur Province, Davao City, and barangays

header('Content-Type: application/json');

$regions = [
    "NCR" => [
        "name" => "National Capital Region",
        "provinces" => [
            "Metro Manila" => [
                "cities" => [
                    "Caloocan City" => ["Barangay 1", "Barangay 2", "Barangay 3", "Barangay 4", "Barangay 5"],
                    "Las Piñas City" => ["Almanza Dos", "Almanza Uno", "Daniel Fajardo", "Elias Aldana", "Ilaya"],
                    "Makati City" => ["Bangkal", "Bel-Air", "Cembo", "Comembo", "Dasmariñas"],
                    "Malabon City" => ["Acacia", "Baritan", "Bayan-Bayanan", "Catmon", "Concepcion"],
                    "Mandaluyong City" => ["Addition Hills", "Bagong Silang", "Barangka Drive", "Barangka Ibaba", "Barangka Ilaya"],
                    "Manila City" => ["Barangay 1", "Barangay 2", "Barangay 3", "Barangay 4", "Barangay 5"],
                    "Marikina City" => ["Barangka", "Calumpang", "Concepcion Dos", "Concepcion Uno", "Fortune"],
                    "Muntinlupa City" => ["Alabang", "Bayanan", "Buli", "Cupang", "Poblacion"],
                    "Navotas City" => ["Bagumbayan North", "Bagumbayan South", "Bangculasi", "Daanghari", "Navotas East"],
                    "Parañaque City" => ["Baclaran", "BF Homes", "Don Bosco", "Don Galo", "La Huerta"],
                    "Pasay City" => ["Barangay 1", "Barangay 2", "Barangay 3", "Barangay 4", "Barangay 5"],
                    "Pasig City" => ["Bagong Ilog", "Bagong Katipunan", "Bambang", "Buting", "Caniogan"],
                    "Pateros" => ["Aguho", "Magtanggol", "Martires del '96", "Poblacion", "San Pedro"],
                    "Quezon City" => ["Alicia", "Amihan", "Apolonio Samson", "Baesa", "Bagong Lipunan ng Crame"],
                    "San Juan City" => ["Addition Hills", "Balong-Bato", "Batis", "Corazon de Jesus", "Ermitaño"],
                    "Taguig City" => ["Bagumbayan", "Bambang", "Calzada", "Central Bicutan", "Central Signal Village"],
                    "Valenzuela City" => ["Arkong Bato", "Bagbaguin", "Bignay", "Bisig", "Canumay East"]
                ]
            ]
        ]
    ],
    "Region XI" => [
        "name" => "Davao Region",
        "provinces" => [
            "Davao del Sur" => [
                "cities" => [
                    "Davao City" => [
                        "Agdao",
                        "Baguio District",
                        "Buhangin",
                        "Buhangin Proper",
                        "Buhangin Upper",
                        "Buhangin Lower",
                        "Buhangin East",
                        "Buhangin West",
                        "Buhangin North",
                        "Buhangin South",
                        "Buhangin Central",
                        "Buhangin Barangay 1",
                        "Buhangin Barangay 2",
                        "Buhangin Barangay 3",
                        "Buhangin Barangay 4",
                        "Buhangin Barangay 5",
                        "Buhangin Barangay 6",
                        "Buhangin Barangay 7",
                        "Buhangin Barangay 8",
                        "Buhangin Barangay 9",
                        "Buhangin Barangay 10"
                    ]
                ]
            ]
        ]
    ]
];

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'regions':
            $result = [];
            foreach ($regions as $code => $region) {
                $result[] = ['code' => $code, 'name' => $region['name']];
            }
            echo json_encode($result);
            break;
            
        case 'provinces':
            $region_code = isset($_GET['region']) ? $_GET['region'] : '';
            if (isset($regions[$region_code])) {
                $result = [];
                foreach ($regions[$region_code]['provinces'] as $province => $data) {
                    $result[] = ['name' => $province];
                }
                echo json_encode($result);
            } else {
                echo json_encode([]);
            }
            break;
            
        case 'cities':
            $region_code = isset($_GET['region']) ? $_GET['region'] : '';
            $province_name = isset($_GET['province']) ? $_GET['province'] : '';
            if (isset($regions[$region_code]['provinces'][$province_name])) {
                $result = [];
                foreach ($regions[$region_code]['provinces'][$province_name]['cities'] as $city => $barangays) {
                    $result[] = ['name' => $city];
                }
                echo json_encode($result);
            } else {
                echo json_encode([]);
            }
            break;
            
        case 'barangays':
            $region_code = isset($_GET['region']) ? $_GET['region'] : '';
            $province_name = isset($_GET['province']) ? $_GET['province'] : '';
            $city_name = isset($_GET['city']) ? $_GET['city'] : '';
            if (isset($regions[$region_code]['provinces'][$province_name]['cities'][$city_name])) {
                $result = [];
                foreach ($regions[$region_code]['provinces'][$province_name]['cities'][$city_name] as $barangay) {
                    $result[] = ['name' => $barangay];
                }
                echo json_encode($result);
            } else {
                echo json_encode([]);
            }
            break;
            
        default:
            echo json_encode(['error' => 'Invalid action']);
    }
} else {
    echo json_encode(['error' => 'No action specified']);
}
?>
