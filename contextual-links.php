<?php
// contextual-links.php
$current_file = basename($_SERVER['PHP_SELF']);
$page_groups = [
    'primary_services' => [
        'home-inspection-toronto.html',
        'pre-purchase-inspection.html', 
        'condo-inspection.html',
        'new-construction-inspection.html'
    ],
    'secondary_services' => [
        'tarion-warranty-inspection.html',
        'infrared-thermal-imaging.html',
        'mold-testing-toronto.html',
        'wett-inspection-toronto.html'
    ],
    'specialized_services' => [
        'sewer-scope-inspection.html',
        'radon-testing.html',
        'asbestos-testing-toronto.html',
        'lead-paint-testing.html',
        'well-water-testing.html'
    ]
];

$city_pages = [
    'home-inspection-ajax.html', 'home-inspection-brampton.html', 'home-inspection-mississauga.html',
    'home-inspection-markham.html', 'home-inspection-vaughan.html', 'home-inspection-richmond-hill.html',
    'home-inspection-oakville.html', 'home-inspection-burlington.html', 'home-inspection-hamilton.html',
    'home-inspection-kitchener.html', 'home-inspection-waterloo.html', 'home-inspection-cambridge.html'
];

function is_city_page($file) {
    return strpos($file, 'home-inspection-') !== false && $file !== 'home-inspection-toronto.html';
}

function generate_links($current) {
    global $page_groups, $city_pages;
    $links = [];
    
    // Rule 1: All service pages link to primary service
    if (strpos($current, '.html') && $current !== 'index.php' && $current !== 'home-inspection-toronto.html') {
        $links[] = ['url' => '/services/home-inspection-toronto.html', 'text' => 'Home Inspection Toronto'];
    }
    
    // Rule 2: City pages link to related services
    if (is_city_page($current)) {
        $links[] = ['url' => '/services/pre-purchase-inspection.html', 'text' => 'Pre-Purchase Inspection'];
        $links[] = ['url' => '/services/condo-inspection.html', 'text' => 'Condo Inspection'];
        if (rand(0,1)) $links[] = ['url' => '/services/infrared-thermal-imaging.html', 'text' => 'Thermal Imaging'];
    }
    
    // Rule 3: Service pages link to city pages (3 random)
    if (in_array($current, array_merge($page_groups['primary_services'], $page_groups['secondary_services'], $page_groups['specialized_services']))) {
        $random_cities = array_rand($city_pages, min(3, count($city_pages)));
        if (!is_array($random_cities)) $random_cities = [$random_cities];
        foreach ($random_cities as $index) {
            $city = $city_pages[$index];
            $city_name = str_replace(['home-inspection-', '.html'], '', $city);
            $links[] = ['url' => "/services/$city", 'text' => 'Home Inspection ' . ucwords(str_replace('-', ' ', $city_name))];
        }
    }
    
    return array_slice($links, 0, 4);
}

$current_links = generate_links($current_file);

if (!empty($current_links) && $current_file !== 'index.php' && !strpos($current_file, 'privacy') && !strpos($current_file, 'terms')) {
    echo '<div class="contextual-links-section" style="margin: 30px 0; padding: 20px; background: #f8f9fa; border-radius: 5px;">';
    echo '<h3 style="margin-bottom: 15px; font-size: 18px;">Related Services</h3>';
    echo '<div style="display: flex; flex-wrap: wrap; gap: 10px;">';
    foreach ($current_links as $link) {
        echo '<a href="' . htmlspecialchars($link['url']) . '" style="display: inline-block; padding: 8px 16px; background: white; border: 1px solid #ddd; border-radius: 4px; text-decoration: none; color: #333;">';
        echo htmlspecialchars($link['text']) . '</a>';
    }
    echo '</div></div>';
}
?>
