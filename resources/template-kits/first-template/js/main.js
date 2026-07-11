(function () {
    "use strict";

    const translations = {
        en: {
            skip_link: "Skip to content",
            brand_name: "Laveira Spa",
            brand_tagline: "Luxury Arabic Spa",
            nav_home: "Home",
            nav_offers: "Offers",
            nav_services: "Services",
            nav_about: "About",
            nav_contact: "Contact Us",
            hero_eyebrow: "Dubai's private luxury wellness escape",
            hero_title: "A Royal Spa Ritual Crafted for Dubai Evenings",
            hero_lead: "Step into Laveira Spa, where Arabic hospitality, golden serenity, and deeply restorative treatments create a private sanctuary for body and soul.",
            cta_book: "Book Now",
            cta_explore: "Explore Services",
            hero_stat_1_value: "12+",
            hero_stat_1_label: "Signature rituals",
            hero_stat_2_value: "5-star",
            hero_stat_2_label: "Dubai experience",
            hero_stat_3_value: "24/7",
            hero_stat_3_label: "Booking support",
            services_eyebrow: "Curated rituals",
            services_title: "Premium Spa Services",
            services_intro: "Choose from refined treatments designed for deep relaxation, glowing skin, and the polished calm of Dubai luxury wellness.",
            tab_all: "All",
            tab_massage: "Massage",
            tab_ritual: "Bath Rituals",
            tab_bath: "Bath & Hammam",
            tab_care: "Skin & Body Care",
            tab_beauty: "Beauty",
            service_badge: "Signature",
            service_1_title: "Royal Relaxation Massage",
            service_1_desc: "A slow, deeply calming massage with aromatic oils and pressure tailored to your body.",
            service_1_alt: "Luxury massage room with candles and soft spa towels",
            service_2_title: "Moroccan Hammam Ritual",
            service_2_desc: "A traditional cleansing ceremony with steam, black soap, exfoliation, and silky hydration.",
            service_2_alt: "Moroccan bath ritual with warm towels and spa products",
            service_3_title: "Rose Gold Glow Facial",
            service_3_desc: "A luminous facial experience for hydrated, polished, camera-ready Dubai skin.",
            service_3_alt: "Luxury facial treatment with rose skincare products",
            service_massage_alt: "Luxury massage suite with warm towels and premium oils",
            service_bath_alt: "Elegant hammam room with steam and traditional spa towels",
            service_care_alt: "Luxury skincare treatment with natural creams and soft towels",
            service_beauty_alt: "Premium manicure and grooming setup in a luxury spa",
            service_natural_oil_title: "Natural Oil Massage",
            service_natural_oil_desc: "A calming full body massage using pure oils for deep comfort and softness.",
            service_cream_title: "Cream Massage",
            service_cream_desc: "A smooth cream massage that hydrates the skin while relaxing tired muscles.",
            service_oil_title: "Oil Massage",
            service_oil_desc: "A classic spa massage with warm oils to release tension and restore balance.",
            service_soap_title: "Soap Massage",
            service_soap_desc: "A cleansing foam massage that refreshes the body and leaves skin silky soft.",
            service_underwater_title: "Underwater Massage",
            service_underwater_desc: "A soothing water-based massage for gentle pressure and total body release.",
            service_hot_aroma_title: "Hot Aromatherapy Oil Massage",
            service_hot_aroma_desc: "A warm aromatic oil massage designed for deep relaxation and elegant calm.",
            service_turkish_bath_title: "Turkish Bath",
            service_turkish_bath_desc: "A traditional Turkish bath with steam, cleansing foam, and soft renewal.",
            service_syrian_bath_title: "Syrian Bath",
            service_syrian_bath_desc: "A heritage bath ritual using warm steam and cleansing care for fresh skin.",
            service_moroccan_bath_title: "Moroccan Bath",
            service_moroccan_bath_desc: "A royal Moroccan hammam with black soap, exfoliation, and silky finish.",
            service_steam_bath_title: "Steam Bath",
            service_steam_bath_desc: "A warm steam therapy session that relaxes the body and clears the skin.",
            service_herbs_title: "Natural Herbs Treatment",
            service_herbs_desc: "A botanical body treatment using natural herbs to refresh and soften skin.",
            service_blue_nila_title: "Moroccan Blue Nila Treatment",
            service_blue_nila_desc: "A brightening Moroccan Blue Nila ritual for smooth, luminous-looking skin.",
            service_body_scrub_title: "Full Body Scrub",
            service_body_scrub_desc: "A polished exfoliation ritual that removes buildup and reveals soft skin.",
            service_hair_scrub_title: "Hair Scrub",
            service_hair_scrub_desc: "A refreshing scalp scrub designed to cleanse, revive, and comfort the hair.",
            service_face_mask_title: "Face Mask",
            service_face_mask_desc: "A soothing facial mask treatment for hydrated, fresh, and glowing skin.",
            service_mani_pedi_title: "Manicure & Pedicure",
            service_mani_pedi_desc: "Detailed hand and foot grooming with a clean, polished luxury spa finish.",
            service_hair_removal_title: "Full Body Hair Removal",
            service_hair_removal_desc: "A discreet full body hair removal service using wax or machine options.",
            btn_call: "Call",
            btn_whatsapp: "WhatsApp",
            offers_eyebrow: "Limited luxury packages",
            offers_title: "Exclusive Dubai Spa Offers",
            offers_intro: "High-value experiences presented with the atmosphere, privacy, and refinement expected from a premium Dubai spa.",
            offer_complimentary_title: "Complimentary Treatments",
            offer_1_eyebrow: "Velvet glow",
            offer_1_title: "Velvet Glow Treatment",
            offer_1_price: "350 AED",
            offer_1_detail_1: "60-minute full body massage using premium oils and natural creams",
            offer_1_detail_2: "Relaxing atmosphere with calming aromatherapy experience",
            offer_2_eyebrow: "Steam bliss",
            offer_2_title: "Steam Bliss Ritual",
            offer_2_price: "400 AED",
            offer_2_detail_1: "Underwater soap massage inside the steam room",
            offer_2_detail_2: "Open access to steam room and sauna",
            offer_3_eyebrow: "Aqua escape",
            offer_3_title: "Aqua Steam Escape",
            offer_3_price: "450 AED",
            offer_3_detail_1: "Luxury underwater soap massage inside the steam room",
            offer_3_detail_2: "Unlimited access to steam room and sauna",
            offer_3_detail_3: "Deep relaxation steam therapy experience",
            offer_4_eyebrow: "Ottoman ritual",
            offer_4_title: "Ottoman Relax Package",
            offer_4_price: "500 AED",
            offer_4_detail_1: "60-minute massage with natural oils and herbal creams",
            offer_4_detail_2: "Moroccan Royal Bath or Turkish Relaxation Bath",
            offer_4_detail_3: "Steam bath experience",
            offer_5_eyebrow: "Royal harmony",
            offer_5_title: "Royal Harmony Experience",
            offer_5_price: "700 AED",
            offer_5_detail_1: "60-minute massage with natural oils and premium creams",
            offer_5_detail_2: "Moroccan Royal Bath or Turkish Relaxation Bath",
            offer_5_detail_3: "Open access to steam room and sauna",
            offer_6_eyebrow: "Elite harmony",
            offer_6_title: "Elite Harmony Ritual",
            offer_6_price: "700 AED",
            offer_6_detail_1: "60-minute luxury massage using natural oils and creams",
            offer_6_detail_2: "Moroccan Royal Bath or Turkish Relaxation Bath",
            offer_6_detail_3: "Underwater soap massage",
            offer_6_detail_4: "Steam bath session",
            offer_7_eyebrow: "Sultan jacuzzi",
            offer_7_title: "Sultan Jacuzzi Ritual",
            offer_7_price: "800 AED",
            offer_7_detail_1: "60-minute massage with luxury oils and herbal creams",
            offer_7_detail_2: "20-minute mineral salt jacuzzi session",
            offer_7_detail_3: "Moroccan Royal Bath or Turkish Relaxation Bath",
            offer_7_detail_4: "Steam room access",
            offer_8_eyebrow: "Imperial palace",
            offer_8_title: "Imperial Palace Ritual",
            offer_8_price: "1000 AED",
            offer_8_detail_1: "60-minute full body massage with luxury oils and premium creams",
            offer_8_detail_2: "Moroccan Royal Bath or Turkish Relaxation Bath",
            offer_8_detail_3: "Underwater soap massage",
            offer_8_detail_4: "Unlimited steam room and sauna access",
            offer_comp_scrub: "Full Body Scrub",
            offer_comp_face_hair_mask: "Face & Hair Mask",
            offer_comp_nail_cleaning: "Nail Cleaning & Grooming",
            offer_comp_sensitive_hair: "Sensitive Area Hair Removal",
            offer_comp_dead_skin: "Dead Skin Exfoliation",
            cta_reserve: "Reserve",
            therapists_eyebrow: "Expert therapists",
            therapists_title: "A Refined Wellness Team",
            therapists_intro: "Professional therapists trained to deliver attentive, discreet, and beautifully paced spa experiences.",
            therapist_1_name: "Sophia",
            therapist_1_role: "Aromatherapy specialist",
            therapist_2_name: "Maya",
            therapist_2_role: "Moroccan bath expert",
            therapist_3_name: "Noura",
            therapist_3_role: "Facial and glow therapist",
            therapist_4_name: "Anika",
            therapist_4_role: "Deep relaxation therapist",
            therapist_badge: "Luxury wellness care",
            footer_statement: "An intimate Dubai sanctuary for golden rituals, soft stillness, and premium Arabic spa hospitality.",
            footer_contact_title: "Contact Information",
            footer_address: "Dubai Marina, Dubai, United Arab Emirates",
            footer_whatsapp: "WhatsApp Booking",
            footer_hours: "Open daily, 10:00 AM - 2:00 AM",
            footer_map_title: "Find Us",
            footer_map_placeholder: "Google Maps iframe placeholder for the Dubai spa location",
            footer_copyright: "Copyright 2026 Laveira Spa. All rights reserved."
        },
        ar: {
            skip_link: "تخطي إلى المحتوى",
            brand_name: "لافيرا سبا",
            brand_tagline: "سبا عربي فاخر",
            nav_home: "الرئيسية",
            nav_offers: "العروض",
            nav_services: "الخدمات",
            nav_about: "من نحن",
            nav_contact: "تواصل معنا",
            hero_eyebrow: "ملاذ فاخر خاص للعافية في دبي",
            hero_title: "طقوس سبا ملكية مصممة لأمسيات دبي",
            hero_lead: "ادخلي عالم لافيرا سبا حيث الضيافة العربية والهدوء الذهبي والعلاجات العميقة تخلق ملاذا خاصا للجسد والروح.",
            cta_book: "احجزي الآن",
            cta_explore: "استكشفي الخدمات",
            hero_stat_1_value: "+12",
            hero_stat_1_label: "طقوس مميزة",
            hero_stat_2_value: "5 نجوم",
            hero_stat_2_label: "تجربة دبي",
            hero_stat_3_value: "24/7",
            hero_stat_3_label: "دعم الحجز",
            services_eyebrow: "طقوس مختارة",
            services_title: "خدمات سبا فاخرة",
            services_intro: "اختاري من علاجات راقية صممت للاسترخاء العميق ونضارة البشرة والهدوء المصقول في عالم العافية بدبي.",
            tab_all: "الكل",
            tab_massage: "مساج",
            tab_ritual: "حمامات",
            tab_bath: "الحمام والحمام التقليدي",
            tab_care: "العناية بالبشرة والجسم",
            tab_beauty: "جمال",
            service_badge: "مميز",
            service_1_title: "مساج الاسترخاء الملكي",
            service_1_desc: "مساج هادئ وعميق بزيوت عطرية وضغط مخصص لاحتياج جسمك.",
            service_1_alt: "غرفة مساج فاخرة مع شموع ومناشف سبا ناعمة",
            service_2_title: "طقوس الحمام المغربي",
            service_2_desc: "تجربة تنظيف تقليدية بالبخار والصابون الأسود والتقشير والترطيب الحريري.",
            service_2_alt: "طقوس حمام مغربي مع مناشف دافئة ومنتجات سبا",
            service_3_title: "فاشيال الوهج الذهبي الوردي",
            service_3_desc: "تجربة فاشيال مشرقة لبشرة مرطبة ومصقولة وجاهزة لإطلالة دبي.",
            service_3_alt: "علاج فاشيال فاخر بمنتجات عناية وردية",
            service_massage_alt: "جناح مساج فاخر مع مناشف دافئة وزيوت مميزة",
            service_bath_alt: "غرفة حمام أنيقة بالبخار ومناشف سبا تقليدية",
            service_care_alt: "علاج عناية فاخر بالبشرة مع كريمات طبيعية ومناشف ناعمة",
            service_beauty_alt: "إعداد فاخر للعناية بالأظافر والتجميل داخل السبا",
            service_natural_oil_title: "مساج بالزيوت الطبيعية",
            service_natural_oil_desc: "مساج كامل مهدئ بزيوت نقية يمنح الجسم راحة عميقة ونعومة.",
            service_cream_title: "مساج بالكريم",
            service_cream_desc: "مساج ناعم بالكريم يرطب البشرة ويخفف إجهاد العضلات بلطف.",
            service_oil_title: "مساج بالزيت",
            service_oil_desc: "مساج سبا كلاسيكي بزيوت دافئة لتخفيف التوتر واستعادة التوازن.",
            service_soap_title: "مساج بالصابون",
            service_soap_desc: "مساج رغوي منظف ينعش الجسم ويترك البشرة ناعمة كالحرير.",
            service_underwater_title: "مساج تحت الماء",
            service_underwater_desc: "مساج مائي هادئ بضغط لطيف يمنح الجسم استرخاء كاملا.",
            service_hot_aroma_title: "مساج زيت عطري ساخن",
            service_hot_aroma_desc: "مساج بزيوت عطرية دافئة للاسترخاء العميق والهدوء الراقي.",
            service_turkish_bath_title: "حمام تركي",
            service_turkish_bath_desc: "حمام تركي تقليدي بالبخار والرغوة المنظفة وتجديد ناعم.",
            service_syrian_bath_title: "حمام سوري",
            service_syrian_bath_desc: "طقس حمام تراثي بالبخار الدافئ والعناية المنظفة لبشرة منتعشة.",
            service_moroccan_bath_title: "حمام مغربي",
            service_moroccan_bath_desc: "حمام مغربي ملكي بالصابون الأسود والتقشير ولمسة حريرية.",
            service_steam_bath_title: "حمام بخار",
            service_steam_bath_desc: "جلسة بخار دافئة تساعد على استرخاء الجسم وتنقية البشرة.",
            service_herbs_title: "علاج بالأعشاب الطبيعية",
            service_herbs_desc: "علاج نباتي للجسم بالأعشاب الطبيعية لإنعاش البشرة وتنعيمها.",
            service_blue_nila_title: "علاج النيلة الزرقاء المغربية",
            service_blue_nila_desc: "طقس نيلة مغربية لتفتيح الإطلالة ومنح البشرة نعومة وإشراقا.",
            service_body_scrub_title: "سنفرة كاملة للجسم",
            service_body_scrub_desc: "تقشير مصقول يزيل التراكمات ويكشف عن بشرة ناعمة ومنتعشة.",
            service_hair_scrub_title: "سنفرة الشعر",
            service_hair_scrub_desc: "سنفرة منعشة لفروة الرأس تنظف الشعر وتعيد له الراحة والحيوية.",
            service_face_mask_title: "ماسك للوجه",
            service_face_mask_desc: "ماسك مهدئ للوجه يمنح البشرة ترطيبا ونضارة وإشراقا لطيفا.",
            service_mani_pedi_title: "مانيكير وباديكير",
            service_mani_pedi_desc: "عناية دقيقة باليدين والقدمين بلمسة سبا نظيفة وفاخرة.",
            service_hair_removal_title: "إزالة شعر الجسم بالكامل",
            service_hair_removal_desc: "خدمة إزالة شعر للجسم بالكامل بالشمع أو الجهاز بخصوصية تامة.",
            btn_call: "اتصال",
            btn_whatsapp: "واتساب",
            offers_eyebrow: "باقات فاخرة محدودة",
            offers_title: "عروض سبا حصرية في دبي",
            offers_intro: "تجارب عالية القيمة بأجواء وخصوصية ورقي يليق بسبا فاخر في دبي.",
            offer_complimentary_title: "علاجات مجانية",
            offer_1_eyebrow: "وهج مخملي",
            offer_1_title: "علاج الوهج المخملي",
            offer_1_price: "350 درهم",
            offer_1_detail_1: "مساج كامل للجسم لمدة 60 دقيقة بزيوت فاخرة وكريمات طبيعية",
            offer_1_detail_2: "أجواء مريحة مع تجربة عطرية هادئة",
            offer_2_eyebrow: "نعيم البخار",
            offer_2_title: "طقس نعيم البخار",
            offer_2_price: "400 درهم",
            offer_2_detail_1: "مساج صابوني تحت الماء داخل غرفة البخار",
            offer_2_detail_2: "دخول مفتوح إلى غرفة البخار والساونا",
            offer_3_eyebrow: "هروب مائي",
            offer_3_title: "هروب البخار المائي",
            offer_3_price: "450 درهم",
            offer_3_detail_1: "مساج صابوني فاخر تحت الماء داخل غرفة البخار",
            offer_3_detail_2: "دخول غير محدود إلى غرفة البخار والساونا",
            offer_3_detail_3: "تجربة علاج بالبخار للاسترخاء العميق",
            offer_4_eyebrow: "طقس عثماني",
            offer_4_title: "باقة الاسترخاء العثمانية",
            offer_4_price: "500 درهم",
            offer_4_detail_1: "مساج 60 دقيقة بزيوت طبيعية وكريمات عشبية",
            offer_4_detail_2: "حمام مغربي ملكي أو حمام تركي للاسترخاء",
            offer_4_detail_3: "تجربة حمام بخار",
            offer_5_eyebrow: "تناغم ملكي",
            offer_5_title: "تجربة التناغم الملكي",
            offer_5_price: "700 درهم",
            offer_5_detail_1: "مساج 60 دقيقة بزيوت طبيعية وكريمات فاخرة",
            offer_5_detail_2: "حمام مغربي ملكي أو حمام تركي للاسترخاء",
            offer_5_detail_3: "دخول مفتوح إلى غرفة البخار والساونا",
            offer_6_eyebrow: "تناغم النخبة",
            offer_6_title: "طقس تناغم النخبة",
            offer_6_price: "700 درهم",
            offer_6_detail_1: "مساج فاخر 60 دقيقة بزيوت وكريمات طبيعية",
            offer_6_detail_2: "حمام مغربي ملكي أو حمام تركي للاسترخاء",
            offer_6_detail_3: "مساج صابوني تحت الماء",
            offer_6_detail_4: "جلسة حمام بخار",
            offer_7_eyebrow: "جاكوزي السلطان",
            offer_7_title: "طقس جاكوزي السلطان",
            offer_7_price: "800 درهم",
            offer_7_detail_1: "مساج 60 دقيقة بزيوت فاخرة وكريمات عشبية",
            offer_7_detail_2: "جلسة جاكوزي بالأملاح المعدنية لمدة 20 دقيقة",
            offer_7_detail_3: "حمام مغربي ملكي أو حمام تركي للاسترخاء",
            offer_7_detail_4: "دخول غرفة البخار",
            offer_8_eyebrow: "قصر إمبراطوري",
            offer_8_title: "طقس القصر الإمبراطوري",
            offer_8_price: "1000 درهم",
            offer_8_detail_1: "مساج كامل للجسم لمدة 60 دقيقة بزيوت فاخرة وكريمات مميزة",
            offer_8_detail_2: "حمام مغربي ملكي أو حمام تركي للاسترخاء",
            offer_8_detail_3: "مساج صابوني تحت الماء",
            offer_8_detail_4: "دخول غير محدود إلى غرفة البخار والساونا",
            offer_comp_scrub: "سنفرة كاملة للجسم",
            offer_comp_face_hair_mask: "ماسك للوجه والشعر",
            offer_comp_nail_cleaning: "تنظيف وتجميل الأظافر",
            offer_comp_sensitive_hair: "إزالة شعر المناطق الحساسة",
            offer_comp_dead_skin: "إزالة الجلد الميت",
            cta_reserve: "احجزي",
            therapists_eyebrow: "معالجات محترفات",
            therapists_title: "فريق عافية راق",
            therapists_intro: "معالجات محترفات لتقديم تجارب سبا دقيقة وهادئة وباهتمام عال.",
            therapist_1_name: "صوفيا",
            therapist_1_role: "اختصاصية العلاج العطري",
            therapist_2_name: "مايا",
            therapist_2_role: "خبيرة الحمام المغربي",
            therapist_3_name: "نورا",
            therapist_3_role: "معالجة فاشيال ونضارة",
            therapist_4_name: "أنيكا",
            therapist_4_role: "معالجة الاسترخاء العميق",
            therapist_badge: "عناية عافية فاخرة",
            footer_statement: "ملاذ حميم في دبي للطقوس الذهبية والهدوء الناعم والضيافة العربية الراقية.",
            footer_contact_title: "معلومات التواصل",
            footer_address: "دبي مارينا، دبي، الإمارات العربية المتحدة",
            footer_whatsapp: "حجز واتساب",
            footer_hours: "مفتوح يوميا، 10:00 صباحا - 2:00 صباحا",
            footer_map_title: "موقعنا",
            footer_map_placeholder: "مكان مخصص لخريطة Google لموقع السبا في دبي",
            footer_copyright: "حقوق النشر 2026 لافيرا سبا. جميع الحقوق محفوظة."
        },
        ru: {
            skip_link: "Перейти к содержимому",
            brand_name: "Laveira Spa",
            brand_tagline: "Luxury Arabic Spa",
            nav_home: "Главная",
            nav_offers: "Акции",
            nav_services: "Услуги",
            nav_about: "О нас",
            nav_contact: "Контакты",
            hero_eyebrow: "Частный люксовый wellness-оазис в Дубае",
            hero_title: "Королевский spa-ритуал для вечеров Дубая",
            hero_lead: "Откройте Laveira Spa: арабское гостеприимство, золотое спокойствие и восстанавливающие процедуры создают личное пространство для тела и души.",
            cta_book: "Забронировать",
            cta_explore: "Смотреть услуги",
            hero_stat_1_value: "12+",
            hero_stat_1_label: "Фирменных ритуалов",
            hero_stat_2_value: "5 звезд",
            hero_stat_2_label: "Опыт Дубая",
            hero_stat_3_value: "24/7",
            hero_stat_3_label: "Поддержка бронирования",
            services_eyebrow: "Избранные ритуалы",
            services_title: "Премиальные spa-услуги",
            services_intro: "Выбирайте из изысканных процедур для глубокого расслабления, сияющей кожи и безупречного wellness-ритма Дубая.",
            tab_all: "Все",
            tab_massage: "Массаж",
            tab_ritual: "Банные ритуалы",
            tab_bath: "Баня и хаммам",
            tab_care: "Кожа и тело",
            tab_beauty: "Красота",
            service_badge: "Фирменная",
            service_1_title: "Королевский расслабляющий массаж",
            service_1_desc: "Медленный успокаивающий массаж с ароматическими маслами и индивидуальным давлением.",
            service_1_alt: "Роскошный массажный кабинет со свечами и мягкими spa-полотенцами",
            service_2_title: "Марокканский хаммам",
            service_2_desc: "Традиционный очищающий ритуал с паром, черным мылом, пилингом и шелковым увлажнением.",
            service_2_alt: "Марокканский банный ритуал с теплыми полотенцами и spa-продуктами",
            service_3_title: "Фейс-уход Rose Gold Glow",
            service_3_desc: "Сияющий уход для увлажненной, гладкой и готовой к камере кожи.",
            service_3_alt: "Роскошная процедура для лица с розовыми средствами ухода",
            service_massage_alt: "Люксовый массажный кабинет с теплыми полотенцами и маслами",
            service_bath_alt: "Элегантный хаммам с паром и традиционными полотенцами",
            service_care_alt: "Люксовый уход за кожей с натуральными кремами и полотенцами",
            service_beauty_alt: "Премиальная зона маникюра и ухода в люксовом spa",
            service_natural_oil_title: "Массаж с натуральным маслом",
            service_natural_oil_desc: "Спокойный массаж всего тела с чистыми маслами для мягкости и комфорта.",
            service_cream_title: "Кремовый массаж",
            service_cream_desc: "Гладкий массаж с кремом увлажняет кожу и расслабляет уставшие мышцы.",
            service_oil_title: "Масляный массаж",
            service_oil_desc: "Классический spa-массаж с теплыми маслами для баланса и снятия напряжения.",
            service_soap_title: "Мыльный массаж",
            service_soap_desc: "Очищающий пенный массаж освежает тело и оставляет кожу шелковой.",
            service_underwater_title: "Подводный массаж",
            service_underwater_desc: "Мягкий водный массаж с деликатным давлением для полного расслабления.",
            service_hot_aroma_title: "Горячий аромамассаж маслом",
            service_hot_aroma_desc: "Теплый ароматический массаж для глубокого расслабления и элегантного покоя.",
            service_turkish_bath_title: "Турецкая баня",
            service_turkish_bath_desc: "Традиционная турецкая баня с паром, очищающей пеной и мягким обновлением.",
            service_syrian_bath_title: "Сирийская баня",
            service_syrian_bath_desc: "Наследственный банный ритуал с теплым паром и очищающим уходом.",
            service_moroccan_bath_title: "Марокканская баня",
            service_moroccan_bath_desc: "Королевский марокканский хаммам с черным мылом и шелковым финишем.",
            service_steam_bath_title: "Паровая баня",
            service_steam_bath_desc: "Теплая паровая терапия расслабляет тело и помогает очистить кожу.",
            service_herbs_title: "Уход с натуральными травами",
            service_herbs_desc: "Ботанический уход для тела с травами, чтобы освежить и смягчить кожу.",
            service_blue_nila_title: "Марокканская синяя нила",
            service_blue_nila_desc: "Осветляющий ритуал Blue Nila для гладкой и сияющей на вид кожи.",
            service_body_scrub_title: "Скраб всего тела",
            service_body_scrub_desc: "Полированный ритуал эксфолиации удаляет накопления и раскрывает мягкую кожу.",
            service_hair_scrub_title: "Скраб для волос",
            service_hair_scrub_desc: "Освежающий скраб кожи головы очищает, оживляет и успокаивает волосы.",
            service_face_mask_title: "Маска для лица",
            service_face_mask_desc: "Успокаивающая маска для увлажненной, свежей и сияющей кожи.",
            service_mani_pedi_title: "Маникюр и педикюр",
            service_mani_pedi_desc: "Детальный уход за руками и ногами с чистым люксовым spa-финишем.",
            service_hair_removal_title: "Удаление волос всего тела",
            service_hair_removal_desc: "Деликатное удаление волос всего тела воском или аппаратом на выбор.",
            btn_call: "Позвонить",
            btn_whatsapp: "WhatsApp",
            offers_eyebrow: "Лимитированные люкс-пакеты",
            offers_title: "Эксклюзивные spa-предложения в Дубае",
            offers_intro: "Ценные впечатления с атмосферой, приватностью и уровнем сервиса премиального spa Дубая.",
            offer_complimentary_title: "Комплиментарные процедуры",
            offer_1_eyebrow: "Бархатное сияние",
            offer_1_title: "Процедура «Бархатное сияние»",
            offer_1_price: "350 AED",
            offer_1_detail_1: "60-минутный массаж всего тела с премиальными маслами и натуральными кремами",
            offer_1_detail_2: "Расслабляющая атмосфера с успокаивающей ароматерапией",
            offer_2_eyebrow: "Паровое блаженство",
            offer_2_title: "Ритуал «Паровое блаженство»",
            offer_2_price: "400 AED",
            offer_2_detail_1: "Подводный мыльный массаж внутри парной",
            offer_2_detail_2: "Открытый доступ в парную и сауну",
            offer_3_eyebrow: "Aqua escape",
            offer_3_title: "Аква-паровое наслаждение",
            offer_3_price: "450 AED",
            offer_3_detail_1: "Роскошный подводный мыльный массаж внутри парной",
            offer_3_detail_2: "Неограниченный доступ в парную и сауну",
            offer_3_detail_3: "Глубоко расслабляющая паровая терапия",
            offer_4_eyebrow: "Османский ритуал",
            offer_4_title: "Османский пакет релакса",
            offer_4_price: "500 AED",
            offer_4_detail_1: "60-минутный массаж с натуральными маслами и травяными кремами",
            offer_4_detail_2: "Королевская марокканская баня или турецкая расслабляющая баня",
            offer_4_detail_3: "Паровая баня",
            offer_5_eyebrow: "Королевская гармония",
            offer_5_title: "Королевская гармония",
            offer_5_price: "700 AED",
            offer_5_detail_1: "60-минутный массаж с натуральными маслами и премиальными кремами",
            offer_5_detail_2: "Королевская марокканская баня или турецкая расслабляющая баня",
            offer_5_detail_3: "Открытый доступ в парную и сауну",
            offer_6_eyebrow: "Элитная гармония",
            offer_6_title: "Элитный ритуал гармонии",
            offer_6_price: "700 AED",
            offer_6_detail_1: "60-минутный люксовый массаж с натуральными маслами и кремами",
            offer_6_detail_2: "Королевская марокканская баня или турецкая расслабляющая баня",
            offer_6_detail_3: "Подводный мыльный массаж",
            offer_6_detail_4: "Сеанс паровой бани",
            offer_7_eyebrow: "Джакузи султана",
            offer_7_title: "Ритуал султанского джакузи",
            offer_7_price: "800 AED",
            offer_7_detail_1: "60-минутный массаж с люксовыми маслами и травяными кремами",
            offer_7_detail_2: "20-минутный сеанс минеральной солевой джакузи",
            offer_7_detail_3: "Королевская марокканская баня или турецкая расслабляющая баня",
            offer_7_detail_4: "Доступ в парную",
            offer_8_eyebrow: "Императорский дворец",
            offer_8_title: "Императорский дворцовый ритуал",
            offer_8_price: "1000 AED",
            offer_8_detail_1: "60-минутный массаж всего тела с люксовыми маслами и премиальными кремами",
            offer_8_detail_2: "Королевская марокканская баня или турецкая расслабляющая баня",
            offer_8_detail_3: "Подводный мыльный массаж",
            offer_8_detail_4: "Неограниченный доступ в парную и сауну",
            offer_comp_scrub: "Скраб всего тела",
            offer_comp_face_hair_mask: "Маска для лица и волос",
            offer_comp_nail_cleaning: "Чистка и уход за ногтями",
            offer_comp_sensitive_hair: "Удаление волос в чувствительной зоне",
            offer_comp_dead_skin: "Отшелушивание омертвевшей кожи",
            cta_reserve: "Резерв",
            therapists_eyebrow: "Опытные терапевты",
            therapists_title: "Изысканная wellness-команда",
            therapists_intro: "Профессиональные терапевты для внимательных, деликатных и красиво выстроенных spa-процедур.",
            therapist_1_name: "София",
            therapist_1_role: "Специалист по ароматерапии",
            therapist_2_name: "Майя",
            therapist_2_role: "Эксперт марокканской бани",
            therapist_3_name: "Нура",
            therapist_3_role: "Терапевт лица и сияния",
            therapist_4_name: "Аника",
            therapist_4_role: "Терапевт глубокого расслабления",
            therapist_badge: "Люксовый wellness-уход",
            footer_statement: "Камерное пространство в Дубае для золотых ритуалов, мягкого спокойствия и премиального арабского гостеприимства.",
            footer_contact_title: "Контактная информация",
            footer_address: "Dubai Marina, Дубай, ОАЭ",
            footer_whatsapp: "Бронирование в WhatsApp",
            footer_hours: "Ежедневно, 10:00 - 02:00",
            footer_map_title: "Как нас найти",
            footer_map_placeholder: "Место для Google Maps iframe с локацией spa в Дубае",
            footer_copyright: "Copyright 2026 Laveira Spa. Все права защищены."
        },
        hi: {
            skip_link: "सामग्री पर जाएं",
            brand_name: "Laveira Spa",
            brand_tagline: "Luxury Arabic Spa",
            nav_home: "होम",
            nav_offers: "ऑफर",
            nav_services: "सेवाएं",
            nav_about: "हमारे बारे में",
            nav_contact: "संपर्क करें",
            hero_eyebrow: "दुबई का निजी लक्जरी वेलनेस एस्केप",
            hero_title: "दुबई की शामों के लिए रॉयल स्पा रिचुअल",
            hero_lead: "Laveira Spa में आपका स्वागत है, जहां अरबी मेहमाननवाजी, सुनहरी शांति और गहरे उपचार शरीर और आत्मा के लिए निजी सुकून बनाते हैं.",
            cta_book: "बुक करें",
            cta_explore: "सेवाएं देखें",
            hero_stat_1_value: "12+",
            hero_stat_1_label: "सिग्नेचर रिचुअल",
            hero_stat_2_value: "5-star",
            hero_stat_2_label: "दुबई अनुभव",
            hero_stat_3_value: "24/7",
            hero_stat_3_label: "बुकिंग सपोर्ट",
            services_eyebrow: "चुने हुए रिचुअल",
            services_title: "प्रीमियम स्पा सेवाएं",
            services_intro: "गहरे आराम, चमकती त्वचा और दुबई लक्जरी वेलनेस की शांत फिनिश के लिए बनाई गई खास सेवाएं चुनें.",
            tab_all: "सभी",
            tab_massage: "मसाज",
            tab_ritual: "बाथ रिचुअल",
            tab_bath: "बाथ और हम्माम",
            tab_care: "स्किन और बॉडी केयर",
            tab_beauty: "ब्यूटी",
            service_badge: "सिग्नेचर",
            service_1_title: "रॉयल रिलैक्सेशन मसाज",
            service_1_desc: "अरोमा ऑयल और शरीर के अनुसार दबाव के साथ धीमी, गहरी और शांत मसाज.",
            service_1_alt: "कैंडल और मुलायम स्पा टॉवल वाला लक्जरी मसाज रूम",
            service_2_title: "मोरक्कन हम्माम रिचुअल",
            service_2_desc: "स्टीम, ब्लैक सोप, एक्सफोलिएशन और सिल्की हाइड्रेशन के साथ पारंपरिक क्लेंज़िंग.",
            service_2_alt: "गर्म टॉवल और स्पा उत्पादों के साथ मोरक्कन बाथ रिचुअल",
            service_3_title: "रोज गोल्ड ग्लो फेशियल",
            service_3_desc: "हाइड्रेटेड, स्मूद और कैमरा-रेडी दुबई त्वचा के लिए चमकदार फेशियल.",
            service_3_alt: "रोज स्किनकेयर उत्पादों के साथ लक्जरी फेशियल ट्रीटमेंट",
            service_massage_alt: "गर्म टॉवल और प्रीमियम ऑयल वाला लक्जरी मसाज सूट",
            service_bath_alt: "स्टीम और पारंपरिक स्पा टॉवल वाला एलीगेंट हम्माम रूम",
            service_care_alt: "नेचुरल क्रीम और मुलायम टॉवल के साथ लक्जरी स्किनकेयर",
            service_beauty_alt: "लक्जरी स्पा में प्रीमियम मैनिक्योर और ग्रूमिंग सेटअप",
            service_natural_oil_title: "प्राकृतिक तेल मसाज",
            service_natural_oil_desc: "शुद्ध तेलों से शांत फुल बॉडी मसाज, गहरी राहत और मुलायम त्वचा के लिए.",
            service_cream_title: "क्रीम मसाज",
            service_cream_desc: "स्मूद क्रीम मसाज त्वचा को हाइड्रेट करती है और थकी मांसपेशियां आराम देती है.",
            service_oil_title: "तेल मसाज",
            service_oil_desc: "गर्म तेलों से क्लासिक स्पा मसाज, तनाव कम कर संतुलन वापस लाती है.",
            service_soap_title: "साबुन मसाज",
            service_soap_desc: "क्लींजिंग फोम मसाज शरीर को तरोताजा कर त्वचा को सिल्की सॉफ्ट छोड़ती है.",
            service_underwater_title: "पानी के अंदर मसाज",
            service_underwater_desc: "हल्के दबाव और पूरे शरीर की रिलीज के लिए सुकूनभरी वॉटर-बेस्ड मसाज.",
            service_hot_aroma_title: "गर्म अरोमाथेरेपी तेल मसाज",
            service_hot_aroma_desc: "गहरी रिलैक्सेशन और एलीगेंट शांति के लिए गर्म अरोमा ऑयल मसाज.",
            service_turkish_bath_title: "तुर्की स्नान",
            service_turkish_bath_desc: "स्टीम, क्लींजिंग फोम और सॉफ्ट रिन्यूअल के साथ पारंपरिक टर्किश बाथ.",
            service_syrian_bath_title: "सीरियाई स्नान",
            service_syrian_bath_desc: "गर्म स्टीम और क्लींजिंग केयर वाला हेरिटेज बाथ रिचुअल, फ्रेश त्वचा के लिए.",
            service_moroccan_bath_title: "मोरक्कन स्नान",
            service_moroccan_bath_desc: "ब्लैक सोप, एक्सफोलिएशन और सिल्की फिनिश वाला रॉयल मोरक्कन हम्माम.",
            service_steam_bath_title: "स्टीम स्नान",
            service_steam_bath_desc: "गर्म स्टीम थेरेपी से शरीर रिलैक्स होता है और त्वचा साफ महसूस करती है.",
            service_herbs_title: "प्राकृतिक जड़ी-बूटी उपचार",
            service_herbs_desc: "नेचुरल हर्ब्स से बॉडी ट्रीटमेंट, त्वचा को रिफ्रेश और सॉफ्ट करने के लिए.",
            service_blue_nila_title: "मोरक्कन ब्लू नीला उपचार",
            service_blue_nila_desc: "स्मूद और luminous-looking skin के लिए ब्राइटनिंग मोरक्कन Blue Nila ritual.",
            service_body_scrub_title: "फुल बॉडी स्क्रब",
            service_body_scrub_desc: "पॉलिश्ड एक्सफोलिएशन रिचुअल buildup हटाकर मुलायम त्वचा दिखाता है.",
            service_hair_scrub_title: "हेयर स्क्रब",
            service_hair_scrub_desc: "स्कैल्प को क्लीन, रिवाइव और कम्फर्ट करने के लिए रिफ्रेशिंग हेयर स्क्रब.",
            service_face_mask_title: "फेस मास्क",
            service_face_mask_desc: "हाइड्रेटेड, फ्रेश और ग्लोइंग त्वचा के लिए सूदिंग फेशियल मास्क ट्रीटमेंट.",
            service_mani_pedi_title: "मैनिक्योर और पेडीक्योर",
            service_mani_pedi_desc: "क्लीन और पॉलिश्ड लक्जरी स्पा फिनिश के साथ हाथ-पैर की detailed grooming.",
            service_hair_removal_title: "पूरे शरीर के बाल हटाना",
            service_hair_removal_desc: "वैक्स या मशीन विकल्पों से discreet फुल बॉडी हेयर रिमूवल सर्विस.",
            btn_call: "कॉल",
            btn_whatsapp: "WhatsApp",
            offers_eyebrow: "सीमित लक्जरी पैकेज",
            offers_title: "दुबई स्पा के खास ऑफर",
            offers_intro: "प्रीमियम दुबई स्पा जैसी गोपनीयता, माहौल और refinement के साथ high-value experiences.",
            offer_complimentary_title: "कॉम्प्लिमेंटरी ट्रीटमेंट्स",
            offer_1_eyebrow: "वेलवेट ग्लो",
            offer_1_title: "वेलवेट ग्लो ट्रीटमेंट",
            offer_1_price: "350 AED",
            offer_1_detail_1: "प्रीमियम ऑयल और नेचुरल क्रीम से 60 मिनट की फुल बॉडी मसाज",
            offer_1_detail_2: "शांत अरोमाथेरेपी अनुभव के साथ रिलैक्सिंग माहौल",
            offer_2_eyebrow: "स्टीम ब्लिस",
            offer_2_title: "स्टीम ब्लिस रिचुअल",
            offer_2_price: "400 AED",
            offer_2_detail_1: "स्टीम रूम के अंदर अंडरवॉटर सोप मसाज",
            offer_2_detail_2: "स्टीम रूम और सौना का ओपन एक्सेस",
            offer_3_eyebrow: "एक्वा एस्केप",
            offer_3_title: "एक्वा स्टीम एस्केप",
            offer_3_price: "450 AED",
            offer_3_detail_1: "स्टीम रूम के अंदर लक्जरी अंडरवॉटर सोप मसाज",
            offer_3_detail_2: "स्टीम रूम और सौना का अनलिमिटेड एक्सेस",
            offer_3_detail_3: "गहरे आराम के लिए स्टीम थेरेपी अनुभव",
            offer_4_eyebrow: "ऑटोमन रिचुअल",
            offer_4_title: "ऑटोमन रिलैक्स पैकेज",
            offer_4_price: "500 AED",
            offer_4_detail_1: "नेचुरल ऑयल और हर्बल क्रीम से 60 मिनट मसाज",
            offer_4_detail_2: "मोरक्कन रॉयल बाथ या टर्किश रिलैक्सेशन बाथ",
            offer_4_detail_3: "स्टीम बाथ अनुभव",
            offer_5_eyebrow: "रॉयल हार्मनी",
            offer_5_title: "रॉयल हार्मनी एक्सपीरियंस",
            offer_5_price: "700 AED",
            offer_5_detail_1: "नेचुरल ऑयल और प्रीमियम क्रीम से 60 मिनट मसाज",
            offer_5_detail_2: "मोरक्कन रॉयल बाथ या टर्किश रिलैक्सेशन बाथ",
            offer_5_detail_3: "स्टीम रूम और सौना का ओपन एक्सेस",
            offer_6_eyebrow: "एलीट हार्मनी",
            offer_6_title: "एलीट हार्मनी रिचुअल",
            offer_6_price: "700 AED",
            offer_6_detail_1: "नेचुरल ऑयल और क्रीम से 60 मिनट लक्जरी मसाज",
            offer_6_detail_2: "मोरक्कन रॉयल बाथ या टर्किश रिलैक्सेशन बाथ",
            offer_6_detail_3: "अंडरवॉटर सोप मसाज",
            offer_6_detail_4: "स्टीम बाथ सेशन",
            offer_7_eyebrow: "सुल्तान जकूज़ी",
            offer_7_title: "सुल्तान जकूज़ी रिचुअल",
            offer_7_price: "800 AED",
            offer_7_detail_1: "लक्जरी ऑयल और हर्बल क्रीम से 60 मिनट मसाज",
            offer_7_detail_2: "20 मिनट मिनरल सॉल्ट जकूज़ी सेशन",
            offer_7_detail_3: "मोरक्कन रॉयल बाथ या टर्किश रिलैक्सेशन बाथ",
            offer_7_detail_4: "स्टीम रूम एक्सेस",
            offer_8_eyebrow: "इंपीरियल पैलेस",
            offer_8_title: "इंपीरियल पैलेस रिचुअल",
            offer_8_price: "1000 AED",
            offer_8_detail_1: "लक्जरी ऑयल और प्रीमियम क्रीम से 60 मिनट फुल बॉडी मसाज",
            offer_8_detail_2: "मोरक्कन रॉयल बाथ या टर्किश रिलैक्सेशन बाथ",
            offer_8_detail_3: "अंडरवॉटर सोप मसाज",
            offer_8_detail_4: "स्टीम रूम और सौना का अनलिमिटेड एक्सेस",
            offer_comp_scrub: "फुल बॉडी स्क्रब",
            offer_comp_face_hair_mask: "फेस और हेयर मास्क",
            offer_comp_nail_cleaning: "नेल क्लीनिंग और ग्रूमिंग",
            offer_comp_sensitive_hair: "सेंसिटिव एरिया हेयर रिमूवल",
            offer_comp_dead_skin: "डेड स्किन एक्सफोलिएशन",
            cta_reserve: "रिजर्व करें",
            therapists_eyebrow: "एक्सपर्ट थेरेपिस्ट",
            therapists_title: "रिफाइंड वेलनेस टीम",
            therapists_intro: "ध्यानपूर्ण, discreet और सुंदर गति वाली स्पा experiences देने के लिए trained professional therapists.",
            therapist_1_name: "Sophia",
            therapist_1_role: "Aromatherapy specialist",
            therapist_2_name: "Maya",
            therapist_2_role: "Moroccan bath expert",
            therapist_3_name: "Noura",
            therapist_3_role: "Facial and glow therapist",
            therapist_4_name: "Anika",
            therapist_4_role: "Deep relaxation therapist",
            therapist_badge: "Luxury wellness care",
            footer_statement: "गोल्डन रिचुअल, सॉफ्ट स्टिलनेस और प्रीमियम Arabic spa hospitality के लिए दुबई का intimate sanctuary.",
            footer_contact_title: "संपर्क जानकारी",
            footer_address: "Dubai Marina, Dubai, United Arab Emirates",
            footer_whatsapp: "WhatsApp बुकिंग",
            footer_hours: "रोज खुला, 10:00 AM - 2:00 AM",
            footer_map_title: "हमें खोजें",
            footer_map_placeholder: "Dubai spa location के लिए Google Maps iframe placeholder",
            footer_copyright: "Copyright 2026 Laveira Spa. All rights reserved."
        },
        zh: {
            skip_link: "跳到内容",
            brand_name: "Laveira Spa",
            brand_tagline: "Luxury Arabic Spa",
            nav_home: "首页",
            nav_offers: "优惠",
            nav_services: "服务",
            nav_about: "关于",
            nav_contact: "联系我们",
            hero_eyebrow: "迪拜私人奢华康养空间",
            hero_title: "为迪拜夜晚打造的皇家水疗仪式",
            hero_lead: "走进 Laveira Spa，在阿拉伯式待客、金色宁静与深层修复护理中，享受身心专属的私密圣境。",
            cta_book: "立即预约",
            cta_explore: "探索服务",
            hero_stat_1_value: "12+",
            hero_stat_1_label: "招牌仪式",
            hero_stat_2_value: "5-star",
            hero_stat_2_label: "迪拜体验",
            hero_stat_3_value: "24/7",
            hero_stat_3_label: "预约支持",
            services_eyebrow: "精选仪式",
            services_title: "高端水疗服务",
            services_intro: "选择为深度放松、焕亮肌肤和迪拜奢华康养体验而设计的精致护理。",
            tab_all: "全部",
            tab_massage: "按摩",
            tab_ritual: "沐浴仪式",
            tab_bath: "沐浴与 Hammam",
            tab_care: "肌肤与身体护理",
            tab_beauty: "美容",
            service_badge: "招牌",
            service_1_title: "皇家舒缓按摩",
            service_1_desc: "使用芳香精油，并根据身体需求调整力度的慢节奏深度放松按摩。",
            service_1_alt: "带蜡烛和柔软毛巾的奢华按摩房",
            service_2_title: "摩洛哥 Hammam 仪式",
            service_2_desc: "结合蒸汽、黑皂、去角质和丝滑保湿的传统净化仪式。",
            service_2_alt: "带暖毛巾和水疗产品的摩洛哥沐浴仪式",
            service_3_title: "玫瑰金焕亮面部护理",
            service_3_desc: "为迪拜镜头感肌肤打造的水润、细腻、透亮面部护理。",
            service_3_alt: "使用玫瑰护肤产品的奢华面部护理",
            service_massage_alt: "配有暖毛巾和高端精油的奢华按摩套间",
            service_bath_alt: "带蒸汽和传统水疗毛巾的优雅 Hammam 房间",
            service_care_alt: "使用天然乳霜和柔软毛巾的奢华护肤护理",
            service_beauty_alt: "奢华水疗空间中的高端美甲与修护设置",
            service_natural_oil_title: "天然精油按摩",
            service_natural_oil_desc: "使用纯净精油进行舒缓全身按摩，带来深层舒适与柔软。",
            service_cream_title: "乳霜按摩",
            service_cream_desc: "顺滑乳霜按摩滋润肌肤，同时放松疲惫肌肉。",
            service_oil_title: "精油按摩",
            service_oil_desc: "经典水疗按摩配合温热精油，释放紧张并恢复平衡。",
            service_soap_title: "泡沫按摩",
            service_soap_desc: "清洁泡沫按摩焕新身体，并让肌肤保持丝滑柔软。",
            service_underwater_title: "水下按摩",
            service_underwater_desc: "舒缓的水疗按摩，以轻柔压力带来全身释放。",
            service_hot_aroma_title: "热芳香精油按摩",
            service_hot_aroma_desc: "温热芳香精油按摩，专为深度放松和优雅平静设计。",
            service_turkish_bath_title: "土耳其浴",
            service_turkish_bath_desc: "传统土耳其浴结合蒸汽、清洁泡沫与柔和焕新。",
            service_syrian_bath_title: "叙利亚浴",
            service_syrian_bath_desc: "传承沐浴仪式，使用温热蒸汽与清洁护理焕新肌肤。",
            service_moroccan_bath_title: "摩洛哥浴",
            service_moroccan_bath_desc: "皇家摩洛哥 Hammam，结合黑皂、去角质和丝滑收尾。",
            service_steam_bath_title: "蒸汽浴",
            service_steam_bath_desc: "温热蒸汽疗法帮助身体放松，同时让肌肤更清爽。",
            service_herbs_title: "天然草本护理",
            service_herbs_desc: "使用天然草本的植物身体护理，帮助肌肤清新柔软。",
            service_blue_nila_title: "摩洛哥蓝尼拉护理",
            service_blue_nila_desc: "焕亮摩洛哥 Blue Nila 仪式，打造柔滑明亮的肤感。",
            service_body_scrub_title: "全身磨砂",
            service_body_scrub_desc: "精致去角质仪式，清除堆积并呈现柔软肌肤。",
            service_hair_scrub_title: "头发磨砂",
            service_hair_scrub_desc: "清爽头皮磨砂，帮助清洁、唤醒并舒缓头发。",
            service_face_mask_title: "面膜护理",
            service_face_mask_desc: "舒缓面膜护理，为肌肤带来水润、清新与自然光泽。",
            service_mani_pedi_title: "手足护理",
            service_mani_pedi_desc: "细致手足修护，呈现干净、精致的奢华水疗质感。",
            service_hair_removal_title: "全身脱毛",
            service_hair_removal_desc: "私密全身脱毛服务，可选择蜜蜡或机器方式。",
            btn_call: "致电",
            btn_whatsapp: "WhatsApp",
            offers_eyebrow: "限时奢华套餐",
            offers_title: "迪拜专属水疗优惠",
            offers_intro: "以高端迪拜水疗应有的氛围、私密性与精致服务呈现高价值体验。",
            offer_complimentary_title: "免费护理项目",
            offer_1_eyebrow: "丝绒焕亮",
            offer_1_title: "丝绒焕亮护理",
            offer_1_price: "350 AED",
            offer_1_detail_1: "使用高端精油和天然乳霜进行 60 分钟全身按摩",
            offer_1_detail_2: "配合舒缓芳香疗法的放松氛围",
            offer_2_eyebrow: "蒸汽 bliss",
            offer_2_title: "蒸汽舒享仪式",
            offer_2_price: "400 AED",
            offer_2_detail_1: "在蒸汽房内进行水下泡沫按摩",
            offer_2_detail_2: "开放使用蒸汽房和桑拿",
            offer_3_eyebrow: "水汽逃逸",
            offer_3_title: "水润蒸汽之旅",
            offer_3_price: "450 AED",
            offer_3_detail_1: "在蒸汽房内进行奢华水下泡沫按摩",
            offer_3_detail_2: "无限使用蒸汽房和桑拿",
            offer_3_detail_3: "深度放松蒸汽疗愈体验",
            offer_4_eyebrow: "奥斯曼仪式",
            offer_4_title: "奥斯曼放松套餐",
            offer_4_price: "500 AED",
            offer_4_detail_1: "使用天然精油和草本乳霜进行 60 分钟按摩",
            offer_4_detail_2: "摩洛哥皇家浴或土耳其放松浴",
            offer_4_detail_3: "蒸汽浴体验",
            offer_5_eyebrow: "皇家和谐",
            offer_5_title: "皇家和谐体验",
            offer_5_price: "700 AED",
            offer_5_detail_1: "使用天然精油和高端乳霜进行 60 分钟按摩",
            offer_5_detail_2: "摩洛哥皇家浴或土耳其放松浴",
            offer_5_detail_3: "开放使用蒸汽房和桑拿",
            offer_6_eyebrow: "精英和谐",
            offer_6_title: "精英和谐仪式",
            offer_6_price: "700 AED",
            offer_6_detail_1: "使用天然精油和乳霜进行 60 分钟奢华按摩",
            offer_6_detail_2: "摩洛哥皇家浴或土耳其放松浴",
            offer_6_detail_3: "水下泡沫按摩",
            offer_6_detail_4: "蒸汽浴时段",
            offer_7_eyebrow: "苏丹按摩浴",
            offer_7_title: "苏丹按摩浴仪式",
            offer_7_price: "800 AED",
            offer_7_detail_1: "使用奢华精油和草本乳霜进行 60 分钟按摩",
            offer_7_detail_2: "20 分钟矿物盐按摩浴缸体验",
            offer_7_detail_3: "摩洛哥皇家浴或土耳其放松浴",
            offer_7_detail_4: "蒸汽房使用",
            offer_8_eyebrow: "帝国宫殿",
            offer_8_title: "帝国宫殿仪式",
            offer_8_price: "1000 AED",
            offer_8_detail_1: "使用奢华精油和高端乳霜进行 60 分钟全身按摩",
            offer_8_detail_2: "摩洛哥皇家浴或土耳其放松浴",
            offer_8_detail_3: "水下泡沫按摩",
            offer_8_detail_4: "无限使用蒸汽房和桑拿",
            offer_comp_scrub: "全身磨砂",
            offer_comp_face_hair_mask: "面部与头发护理膜",
            offer_comp_nail_cleaning: "指甲清洁与修护",
            offer_comp_sensitive_hair: "敏感部位脱毛",
            offer_comp_dead_skin: "死皮去角质",
            cta_reserve: "预约",
            therapists_eyebrow: "专业理疗师",
            therapists_title: "精致康养团队",
            therapists_intro: "经过专业训练的理疗师，提供细致、私密且节奏优雅的水疗体验。",
            therapist_1_name: "Sophia",
            therapist_1_role: "芳香疗法专家",
            therapist_2_name: "Maya",
            therapist_2_role: "摩洛哥浴专家",
            therapist_3_name: "Noura",
            therapist_3_role: "面部焕亮护理师",
            therapist_4_name: "Anika",
            therapist_4_role: "深度放松理疗师",
            therapist_badge: "奢华康养护理",
            footer_statement: "迪拜私密圣境，呈现金色仪式、柔和宁静与高端阿拉伯水疗待客之道。",
            footer_contact_title: "联系方式",
            footer_address: "阿联酋迪拜 Dubai Marina",
            footer_whatsapp: "WhatsApp 预约",
            footer_hours: "每日营业，上午 10:00 - 凌晨 2:00",
            footer_map_title: "找到我们",
            footer_map_placeholder: "迪拜水疗位置的 Google Maps iframe 占位",
            footer_copyright: "Copyright 2026 Laveira Spa. All rights reserved."
        }
    };

    const languageMeta = {
        en: { label: "English", flag: "https://flagcdn.com/w20/gb.png", dir: "ltr" },
        ar: { label: "العربية", flag: "https://flagcdn.com/w20/ae.png", dir: "rtl" },
        ru: { label: "Русский", flag: "https://flagcdn.com/w20/ru.png", dir: "ltr" },
        hi: { label: "हिन्दी", flag: "https://flagcdn.com/w20/in.png", dir: "ltr" },
        zh: { label: "中文", flag: "https://flagcdn.com/w20/cn.png", dir: "ltr" }
    };

    const header = document.querySelector("[data-header]");
    const navToggle = document.querySelector("[data-nav-toggle]");
    const navPanel = document.querySelector("[data-nav-panel]");
    const languageSwitcher = document.querySelector(".language-switcher");
    const languageButton = document.querySelector("[data-language-button]");
    const languageLabel = document.querySelector("[data-language-label]");
    const languageFlag = document.querySelector("[data-language-flag]");
    const languageMenu = document.querySelector("[data-language-menu]");
    const heroBg = document.querySelector(".hero__bg");

    function setHeaderState() {
        header.classList.toggle("is-scrolled", window.scrollY > 18);
    }

    function closeNavigation() {
        navPanel.classList.remove("is-open");
        navToggle.setAttribute("aria-expanded", "false");
        navToggle.innerHTML = '<i class="fa-solid fa-bars"></i>';
    }

    function applyLanguage(lang) {
        const dictionary = translations[lang] || translations.en;
        const meta = languageMeta[lang] || languageMeta.en;

        document.documentElement.lang = lang;
        document.documentElement.dir = meta.dir;
        document.body.dir = meta.dir;

        document.querySelectorAll("[data-i18n]").forEach((element) => {
            const key = element.dataset.i18n;
            if (dictionary[key]) {
                element.textContent = dictionary[key];
            }
        });

        document.querySelectorAll("[data-i18n-alt]").forEach((element) => {
            const key = element.dataset.i18nAlt;
            if (dictionary[key]) {
                element.setAttribute("alt", dictionary[key]);
            }
        });

        languageLabel.textContent = meta.label;
        languageFlag.src = meta.flag;
        localStorage.setItem("laveira-language", lang);
    }

    function initNavigation() {
        setHeaderState();
        window.addEventListener("scroll", setHeaderState, { passive: true });

        navToggle.addEventListener("click", () => {
            const isOpen = navPanel.classList.toggle("is-open");
            navToggle.setAttribute("aria-expanded", String(isOpen));
            navToggle.innerHTML = isOpen ? '<i class="fa-solid fa-xmark"></i>' : '<i class="fa-solid fa-bars"></i>';
        });

        navPanel.querySelectorAll("a[href^='#']").forEach((link) => {
            link.addEventListener("click", closeNavigation);
        });

        document.addEventListener("click", (event) => {
            if (!navPanel.contains(event.target) && !navToggle.contains(event.target)) {
                closeNavigation();
            }
        });
    }

    function initLanguageSwitcher() {
        const savedLanguage = localStorage.getItem("laveira-language") || "en";
        applyLanguage(savedLanguage);

        languageButton.addEventListener("click", () => {
            const isOpen = languageSwitcher.classList.toggle("is-open");
            languageButton.setAttribute("aria-expanded", String(isOpen));
        });

        languageMenu.querySelectorAll("[data-lang]").forEach((button) => {
            button.addEventListener("click", () => {
                applyLanguage(button.dataset.lang);
                languageSwitcher.classList.remove("is-open");
                languageButton.setAttribute("aria-expanded", "false");
            });
        });

        document.addEventListener("click", (event) => {
            if (!languageSwitcher.contains(event.target)) {
                languageSwitcher.classList.remove("is-open");
                languageButton.setAttribute("aria-expanded", "false");
            }
        });
    }

    function initServiceTabs() {
        const tabs = document.querySelectorAll("[data-filter]");
        const cards = document.querySelectorAll("[data-service-card]");

        tabs.forEach((tab) => {
            tab.addEventListener("click", () => {
                const filter = tab.dataset.filter;

                tabs.forEach((item) => {
                    const isActive = item === tab;
                    item.classList.toggle("is-active", isActive);
                    item.setAttribute("aria-selected", String(isActive));
                });

                cards.forEach((card) => {
                    card.classList.toggle("is-hidden", filter !== "all" && card.dataset.category !== filter);
                });
            });
        });
    }

    function initReveal() {
        const elements = document.querySelectorAll(".reveal");

        if (!("IntersectionObserver" in window)) {
            elements.forEach((element) => element.classList.add("is-visible"));
            return;
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("is-visible");
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.14 });

        elements.forEach((element) => observer.observe(element));
    }

    function initCarousel() {
        const carousel = document.querySelector("[data-carousel]");
        if (!carousel) return;

        const track = carousel.querySelector("[data-carousel-track]");
        const slides = Array.from(carousel.querySelectorAll("[data-carousel-slide]"));
        const prev = carousel.querySelector("[data-carousel-prev]");
        const next = carousel.querySelector("[data-carousel-next]");
        const dotsWrap = document.querySelector("[data-carousel-dots]");
        let index = 0;
        let visibleCount = getVisibleCount();
        let autoplay;

        function getVisibleCount() {
            if (window.matchMedia("(max-width: 720px)").matches) return 1;
            if (window.matchMedia("(max-width: 1100px)").matches) return 2;
            return 3;
        }

        function maxIndex() {
            return Math.max(slides.length - visibleCount, 0);
        }

        function buildDots() {
            dotsWrap.innerHTML = "";
            for (let i = 0; i <= maxIndex(); i += 1) {
                const dot = document.createElement("button");
                dot.type = "button";
                dot.setAttribute("aria-label", `Go to therapist slide ${i + 1}`);
                dot.addEventListener("click", () => {
                    index = i;
                    updateCarousel();
                    restartAutoplay();
                });
                dotsWrap.appendChild(dot);
            }
        }

        function updateCarousel() {
            visibleCount = getVisibleCount();
            index = Math.min(index, maxIndex());
            const slideWidth = slides[0].getBoundingClientRect().width;
            const gap = Number.parseFloat(window.getComputedStyle(track).gap) || 0;
            track.style.transform = `translateX(${-index * (slideWidth + gap)}px)`;

            Array.from(dotsWrap.children).forEach((dot, dotIndex) => {
                dot.classList.toggle("is-active", dotIndex === index);
            });
        }

        function go(direction) {
            index += direction;
            if (index < 0) index = maxIndex();
            if (index > maxIndex()) index = 0;
            updateCarousel();
            restartAutoplay();
        }

        function restartAutoplay() {
            clearInterval(autoplay);
            autoplay = window.setInterval(() => go(1), 5200);
        }

        prev.addEventListener("click", () => go(-1));
        next.addEventListener("click", () => go(1));
        window.addEventListener("resize", () => {
            buildDots();
            updateCarousel();
        });

        buildDots();
        updateCarousel();
        restartAutoplay();
    }

    function initParallax() {
        if (!heroBg || window.matchMedia("(prefers-reduced-motion: reduce)").matches) return;

        window.addEventListener("scroll", () => {
            const offset = Math.min(window.scrollY * 0.08, 60);
            heroBg.style.transform = `scale(1.03) translateY(${offset}px)`;
        }, { passive: true });
    }

    document.addEventListener("DOMContentLoaded", () => {
        initNavigation();
        initLanguageSwitcher();
        initServiceTabs();
        initReveal();
        initCarousel();
        initParallax();
    });
})();
