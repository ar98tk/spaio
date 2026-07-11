<?php
$serviceImages = [
    'natural_oil' => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&w=900&h=675&q=85',
    'cream' => 'https://images.unsplash.com/photo-1519823551278-64ac92734fb1?auto=format&fit=crop&w=900&h=675&q=85',
    'oil' => 'https://images.unsplash.com/photo-1515377905703-c4788e51af15?auto=format&fit=crop&w=900&h=675&q=85',
    'soap' => 'https://images.unsplash.com/photo-1600334129128-685c5582fd35?auto=format&fit=crop&w=900&h=675&q=85',
    'underwater' => 'https://images.unsplash.com/photo-1507652313519-d4e9174996dd?auto=format&fit=crop&w=900&h=675&q=85',
    'hot_aroma' => 'https://images.unsplash.com/photo-1519824145371-296894a0daa9?auto=format&fit=crop&w=900&h=675&q=85',
    'turkish_bath' => 'https://images.unsplash.com/photo-1552693673-1bf958298935?auto=format&fit=crop&w=900&h=675&q=85',
    'syrian_bath' => 'https://images.unsplash.com/photo-1556228578-8c89e6adf883?auto=format&fit=crop&w=900&h=675&q=85',
    'moroccan_bath' => 'https://images.unsplash.com/photo-1521590832167-7bcbfaa6381f?auto=format&fit=crop&w=900&h=675&q=85',
    'steam_bath' => 'https://images.unsplash.com/photo-1571902943202-507ec2618e8f?auto=format&fit=crop&w=900&h=675&q=85',
    'herbs' => 'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?auto=format&fit=crop&w=900&h=675&q=85',
    'blue_nila' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=900&h=675&q=85',
    'body_scrub' => 'https://images.unsplash.com/photo-1611073615830-98b804a3f3a4?auto=format&fit=crop&w=900&h=675&q=85',
    'hair_scrub' => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&fit=crop&w=900&h=675&q=85',
    'face_mask' => 'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?auto=format&fit=crop&w=900&h=675&q=85',
    'mani_pedi' => 'https://images.unsplash.com/photo-1604654894610-df63bc536371?auto=format&fit=crop&w=900&h=675&q=85',
    'hair_removal' => 'https://images.unsplash.com/photo-1519415510236-718bdfcd89c8?auto=format&fit=crop&w=900&h=675&q=85',
];

$services = [
    ['category' => 'massage', 'image' => $serviceImages['natural_oil'], 'title_key' => 'service_natural_oil_title', 'desc_key' => 'service_natural_oil_desc', 'alt_key' => 'service_massage_alt'],
    ['category' => 'massage', 'image' => $serviceImages['cream'], 'title_key' => 'service_cream_title', 'desc_key' => 'service_cream_desc', 'alt_key' => 'service_massage_alt'],
    ['category' => 'massage', 'image' => $serviceImages['oil'], 'title_key' => 'service_oil_title', 'desc_key' => 'service_oil_desc', 'alt_key' => 'service_massage_alt'],
    ['category' => 'massage', 'image' => $serviceImages['soap'], 'title_key' => 'service_soap_title', 'desc_key' => 'service_soap_desc', 'alt_key' => 'service_massage_alt'],
    ['category' => 'massage', 'image' => $serviceImages['underwater'], 'title_key' => 'service_underwater_title', 'desc_key' => 'service_underwater_desc', 'alt_key' => 'service_massage_alt'],
    ['category' => 'massage', 'image' => $serviceImages['hot_aroma'], 'title_key' => 'service_hot_aroma_title', 'desc_key' => 'service_hot_aroma_desc', 'alt_key' => 'service_massage_alt'],
    ['category' => 'bath', 'image' => $serviceImages['turkish_bath'], 'title_key' => 'service_turkish_bath_title', 'desc_key' => 'service_turkish_bath_desc', 'alt_key' => 'service_bath_alt'],
    ['category' => 'bath', 'image' => $serviceImages['syrian_bath'], 'title_key' => 'service_syrian_bath_title', 'desc_key' => 'service_syrian_bath_desc', 'alt_key' => 'service_bath_alt'],
    ['category' => 'bath', 'image' => $serviceImages['moroccan_bath'], 'title_key' => 'service_moroccan_bath_title', 'desc_key' => 'service_moroccan_bath_desc', 'alt_key' => 'service_bath_alt'],
    ['category' => 'bath', 'image' => $serviceImages['steam_bath'], 'title_key' => 'service_steam_bath_title', 'desc_key' => 'service_steam_bath_desc', 'alt_key' => 'service_bath_alt'],
    ['category' => 'care', 'image' => $serviceImages['herbs'], 'title_key' => 'service_herbs_title', 'desc_key' => 'service_herbs_desc', 'alt_key' => 'service_care_alt'],
    ['category' => 'care', 'image' => $serviceImages['blue_nila'], 'title_key' => 'service_blue_nila_title', 'desc_key' => 'service_blue_nila_desc', 'alt_key' => 'service_care_alt'],
    ['category' => 'care', 'image' => $serviceImages['body_scrub'], 'title_key' => 'service_body_scrub_title', 'desc_key' => 'service_body_scrub_desc', 'alt_key' => 'service_care_alt'],
    ['category' => 'care', 'image' => $serviceImages['hair_scrub'], 'title_key' => 'service_hair_scrub_title', 'desc_key' => 'service_hair_scrub_desc', 'alt_key' => 'service_care_alt'],
    ['category' => 'care', 'image' => $serviceImages['face_mask'], 'title_key' => 'service_face_mask_title', 'desc_key' => 'service_face_mask_desc', 'alt_key' => 'service_care_alt'],
    ['category' => 'beauty', 'image' => $serviceImages['mani_pedi'], 'title_key' => 'service_mani_pedi_title', 'desc_key' => 'service_mani_pedi_desc', 'alt_key' => 'service_beauty_alt'],
    ['category' => 'beauty', 'image' => $serviceImages['hair_removal'], 'title_key' => 'service_hair_removal_title', 'desc_key' => 'service_hair_removal_desc', 'alt_key' => 'service_beauty_alt'],
];

$offers = [
    [
        'eyebrow_key' => 'offer_1_eyebrow',
        'title_key' => 'offer_1_title',
        'price_key' => 'offer_1_price',
        'details' => ['offer_1_detail_1', 'offer_1_detail_2'],
        'complimentary' => ['offer_comp_scrub', 'offer_comp_face_hair_mask', 'offer_comp_nail_cleaning', 'offer_comp_sensitive_hair'],
        'featured' => true,
    ],
    [
        'eyebrow_key' => 'offer_2_eyebrow',
        'title_key' => 'offer_2_title',
        'price_key' => 'offer_2_price',
        'details' => ['offer_2_detail_1', 'offer_2_detail_2'],
        'complimentary' => ['offer_comp_scrub', 'offer_comp_face_hair_mask', 'offer_comp_dead_skin', 'offer_comp_nail_cleaning', 'offer_comp_sensitive_hair'],
        'featured' => false,
    ],
    [
        'eyebrow_key' => 'offer_3_eyebrow',
        'title_key' => 'offer_3_title',
        'price_key' => 'offer_3_price',
        'details' => ['offer_3_detail_1', 'offer_3_detail_2', 'offer_3_detail_3'],
        'complimentary' => ['offer_comp_scrub', 'offer_comp_face_hair_mask', 'offer_comp_dead_skin', 'offer_comp_nail_cleaning', 'offer_comp_sensitive_hair'],
        'featured' => false,
    ],
    [
        'eyebrow_key' => 'offer_4_eyebrow',
        'title_key' => 'offer_4_title',
        'price_key' => 'offer_4_price',
        'details' => ['offer_4_detail_1', 'offer_4_detail_2', 'offer_4_detail_3'],
        'complimentary' => ['offer_comp_scrub', 'offer_comp_face_hair_mask', 'offer_comp_dead_skin', 'offer_comp_nail_cleaning', 'offer_comp_sensitive_hair'],
        'featured' => false,
    ],
    [
        'eyebrow_key' => 'offer_5_eyebrow',
        'title_key' => 'offer_5_title',
        'price_key' => 'offer_5_price',
        'details' => ['offer_5_detail_1', 'offer_5_detail_2', 'offer_5_detail_3'],
        'complimentary' => ['offer_comp_scrub', 'offer_comp_face_hair_mask', 'offer_comp_dead_skin', 'offer_comp_nail_cleaning', 'offer_comp_sensitive_hair'],
        'featured' => true,
    ],
    [
        'eyebrow_key' => 'offer_6_eyebrow',
        'title_key' => 'offer_6_title',
        'price_key' => 'offer_6_price',
        'details' => ['offer_6_detail_1', 'offer_6_detail_2', 'offer_6_detail_3', 'offer_6_detail_4'],
        'complimentary' => ['offer_comp_scrub', 'offer_comp_face_hair_mask', 'offer_comp_dead_skin', 'offer_comp_nail_cleaning', 'offer_comp_sensitive_hair'],
        'featured' => false,
    ],
    [
        'eyebrow_key' => 'offer_7_eyebrow',
        'title_key' => 'offer_7_title',
        'price_key' => 'offer_7_price',
        'details' => ['offer_7_detail_1', 'offer_7_detail_2', 'offer_7_detail_3', 'offer_7_detail_4'],
        'complimentary' => ['offer_comp_scrub', 'offer_comp_face_hair_mask', 'offer_comp_dead_skin', 'offer_comp_nail_cleaning', 'offer_comp_sensitive_hair'],
        'featured' => false,
    ],
    [
        'eyebrow_key' => 'offer_8_eyebrow',
        'title_key' => 'offer_8_title',
        'price_key' => 'offer_8_price',
        'details' => ['offer_8_detail_1', 'offer_8_detail_2', 'offer_8_detail_3', 'offer_8_detail_4'],
        'complimentary' => ['offer_comp_scrub', 'offer_comp_face_hair_mask', 'offer_comp_dead_skin', 'offer_comp_nail_cleaning', 'offer_comp_sensitive_hair'],
        'featured' => false,
    ],
];

$therapists = [
    ['image' => 'assets/staff/staff-1.png', 'name_key' => 'therapist_1_name', 'role_key' => 'therapist_1_role'],
    ['image' => 'assets/staff/staff-2.png', 'name_key' => 'therapist_2_name', 'role_key' => 'therapist_2_role'],
    ['image' => 'assets/staff/staff-3.png', 'name_key' => 'therapist_3_name', 'role_key' => 'therapist_3_role'],
    ['image' => 'assets/staff/staff-4.png', 'name_key' => 'therapist_4_name', 'role_key' => 'therapist_4_role'],
];

$defaultCopy = [
    'service_massage_alt' => 'Luxury massage suite with warm towels and premium oils',
    'service_bath_alt' => 'Elegant hammam room with steam and traditional spa towels',
    'service_care_alt' => 'Luxury skincare treatment with natural creams and soft towels',
    'service_beauty_alt' => 'Premium manicure and grooming setup in a luxury spa',
    'service_natural_oil_title' => 'Natural Oil Massage',
    'service_natural_oil_desc' => 'A calming full body massage using pure oils for deep comfort and softness.',
    'service_cream_title' => 'Cream Massage',
    'service_cream_desc' => 'A smooth cream massage that hydrates the skin while relaxing tired muscles.',
    'service_oil_title' => 'Oil Massage',
    'service_oil_desc' => 'A classic spa massage with warm oils to release tension and restore balance.',
    'service_soap_title' => 'Soap Massage',
    'service_soap_desc' => 'A cleansing foam massage that refreshes the body and leaves skin silky soft.',
    'service_underwater_title' => 'Underwater Massage',
    'service_underwater_desc' => 'A soothing water-based massage for gentle pressure and total body release.',
    'service_hot_aroma_title' => 'Hot Aromatherapy Oil Massage',
    'service_hot_aroma_desc' => 'A warm aromatic oil massage designed for deep relaxation and elegant calm.',
    'service_turkish_bath_title' => 'Turkish Bath',
    'service_turkish_bath_desc' => 'A traditional Turkish bath with steam, cleansing foam, and soft renewal.',
    'service_syrian_bath_title' => 'Syrian Bath',
    'service_syrian_bath_desc' => 'A heritage bath ritual using warm steam and cleansing care for fresh skin.',
    'service_moroccan_bath_title' => 'Moroccan Bath',
    'service_moroccan_bath_desc' => 'A royal Moroccan hammam with black soap, exfoliation, and silky finish.',
    'service_steam_bath_title' => 'Steam Bath',
    'service_steam_bath_desc' => 'A warm steam therapy session that relaxes the body and clears the skin.',
    'service_herbs_title' => 'Natural Herbs Treatment',
    'service_herbs_desc' => 'A botanical body treatment using natural herbs to refresh and soften skin.',
    'service_blue_nila_title' => 'Moroccan Blue Nila Treatment',
    'service_blue_nila_desc' => 'A brightening Moroccan Blue Nila ritual for smooth, luminous-looking skin.',
    'service_body_scrub_title' => 'Full Body Scrub',
    'service_body_scrub_desc' => 'A polished exfoliation ritual that removes buildup and reveals soft skin.',
    'service_hair_scrub_title' => 'Hair Scrub',
    'service_hair_scrub_desc' => 'A refreshing scalp scrub designed to cleanse, revive, and comfort the hair.',
    'service_face_mask_title' => 'Face Mask',
    'service_face_mask_desc' => 'A soothing facial mask treatment for hydrated, fresh, and glowing skin.',
    'service_mani_pedi_title' => 'Manicure & Pedicure',
    'service_mani_pedi_desc' => 'Detailed hand and foot grooming with a clean, polished luxury spa finish.',
    'service_hair_removal_title' => 'Full Body Hair Removal',
    'service_hair_removal_desc' => 'A discreet full body hair removal service using wax or machine options.',
    'offer_complimentary_title' => 'Complimentary Treatments',
    'offer_1_eyebrow' => 'Velvet glow',
    'offer_1_title' => 'Velvet Glow Treatment',
    'offer_1_price' => '350 AED',
    'offer_1_detail_1' => '60-minute full body massage using premium oils and natural creams',
    'offer_1_detail_2' => 'Relaxing atmosphere with calming aromatherapy experience',
    'offer_2_eyebrow' => 'Steam bliss',
    'offer_2_title' => 'Steam Bliss Ritual',
    'offer_2_price' => '400 AED',
    'offer_2_detail_1' => 'Underwater soap massage inside the steam room',
    'offer_2_detail_2' => 'Open access to steam room and sauna',
    'offer_3_eyebrow' => 'Aqua escape',
    'offer_3_title' => 'Aqua Steam Escape',
    'offer_3_price' => '450 AED',
    'offer_3_detail_1' => 'Luxury underwater soap massage inside the steam room',
    'offer_3_detail_2' => 'Unlimited access to steam room and sauna',
    'offer_3_detail_3' => 'Deep relaxation steam therapy experience',
    'offer_4_eyebrow' => 'Ottoman ritual',
    'offer_4_title' => 'Ottoman Relax Package',
    'offer_4_price' => '500 AED',
    'offer_4_detail_1' => '60-minute massage with natural oils and herbal creams',
    'offer_4_detail_2' => 'Moroccan Royal Bath or Turkish Relaxation Bath',
    'offer_4_detail_3' => 'Steam bath experience',
    'offer_5_eyebrow' => 'Royal harmony',
    'offer_5_title' => 'Royal Harmony Experience',
    'offer_5_price' => '700 AED',
    'offer_5_detail_1' => '60-minute massage with natural oils and premium creams',
    'offer_5_detail_2' => 'Moroccan Royal Bath or Turkish Relaxation Bath',
    'offer_5_detail_3' => 'Open access to steam room and sauna',
    'offer_6_eyebrow' => 'Elite harmony',
    'offer_6_title' => 'Elite Harmony Ritual',
    'offer_6_price' => '700 AED',
    'offer_6_detail_1' => '60-minute luxury massage using natural oils and creams',
    'offer_6_detail_2' => 'Moroccan Royal Bath or Turkish Relaxation Bath',
    'offer_6_detail_3' => 'Underwater soap massage',
    'offer_6_detail_4' => 'Steam bath session',
    'offer_7_eyebrow' => 'Sultan jacuzzi',
    'offer_7_title' => 'Sultan Jacuzzi Ritual',
    'offer_7_price' => '800 AED',
    'offer_7_detail_1' => '60-minute massage with luxury oils and herbal creams',
    'offer_7_detail_2' => '20-minute mineral salt jacuzzi session',
    'offer_7_detail_3' => 'Moroccan Royal Bath or Turkish Relaxation Bath',
    'offer_7_detail_4' => 'Steam room access',
    'offer_8_eyebrow' => 'Imperial palace',
    'offer_8_title' => 'Imperial Palace Ritual',
    'offer_8_price' => '1000 AED',
    'offer_8_detail_1' => '60-minute full body massage with luxury oils and premium creams',
    'offer_8_detail_2' => 'Moroccan Royal Bath or Turkish Relaxation Bath',
    'offer_8_detail_3' => 'Underwater soap massage',
    'offer_8_detail_4' => 'Unlimited steam room and sauna access',
    'offer_comp_scrub' => 'Full Body Scrub',
    'offer_comp_face_hair_mask' => 'Face & Hair Mask',
    'offer_comp_nail_cleaning' => 'Nail Cleaning & Grooming',
    'offer_comp_sensitive_hair' => 'Sensitive Area Hair Removal',
    'offer_comp_dead_skin' => 'Dead Skin Exfoliation',
    'therapist_1_name' => 'Sophia',
    'therapist_1_role' => 'Aromatherapy specialist',
    'therapist_2_name' => 'Maya',
    'therapist_2_role' => 'Moroccan bath expert',
    'therapist_3_name' => 'Noura',
    'therapist_3_role' => 'Facial and glow therapist',
    'therapist_4_name' => 'Anika',
    'therapist_4_role' => 'Deep relaxation therapist',
];

function ecopy(string $key, string $fallback = ''): string
{
    global $defaultCopy;
    return htmlspecialchars($defaultCopy[$key] ?? $fallback, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Laveira Spa is a premium Dubai luxury spa offering massage, Moroccan bath rituals, facials, jacuzzi experiences, and exclusive wellness offers.">
    <meta name="theme-color" content="#070503">
    <meta property="og:title" content="Laveira Spa | Luxury Arabic Spa in Dubai">
    <meta property="og:description" content="A cinematic Dubai wellness experience with luxury rituals, premium therapists, and exclusive spa offers.">
    <meta property="og:image" content="assets/logo.jpeg">
    <title>Laveira Spa | Luxury Arabic Spa in Dubai</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="assets/favicon.png" type="image/png">
    <link rel="apple-touch-icon" href="assets/favicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <a class="skip-link" href="#main" data-i18n="skip_link">Skip to content</a>

    <?php include 'components/navbar.php'; ?>

    <main id="main">
        <?php include 'components/hero.php'; ?>
        <?php include 'components/services.php'; ?>
        <?php include 'components/offers.php'; ?>
        <?php include 'components/therapists.php'; ?>
    </main>

    <?php include 'components/footer.php'; ?>

    <script src="js/main.js" defer></script>
</body>
</html>
