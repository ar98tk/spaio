<section class="section services" id="services" aria-labelledby="services-title">
    <div class="container">
        <div class="section-heading reveal">
            <p class="eyebrow"><i class="fa-solid fa-spa"></i> <span data-i18n="services_eyebrow">Curated rituals</span></p>
            <h2 id="services-title" data-i18n="services_title">Premium Spa Services</h2>
            <p data-i18n="services_intro">Choose from refined treatments designed for deep relaxation, glowing skin, and the polished calm of Dubai luxury wellness.</p>
        </div>

        <div class="tabs reveal" role="tablist" aria-label="Service categories">
            <button class="tab is-active" type="button" data-filter="all" role="tab" aria-selected="true">
                <i class="fa-solid fa-border-all"></i> <span data-i18n="tab_all">All</span>
            </button>
            <button class="tab" type="button" data-filter="massage" role="tab" aria-selected="false">
                <i class="fa-solid fa-hands"></i> <span data-i18n="tab_massage">Massage</span>
            </button>
            <button class="tab" type="button" data-filter="bath" role="tab" aria-selected="false">
                <i class="fa-solid fa-bath"></i> <span data-i18n="tab_bath">Bath & Hammam</span>
            </button>
            <button class="tab" type="button" data-filter="care" role="tab" aria-selected="false">
                <i class="fa-solid fa-leaf"></i> <span data-i18n="tab_care">Skin & Body Care</span>
            </button>
            <button class="tab" type="button" data-filter="beauty" role="tab" aria-selected="false">
                <i class="fa-regular fa-gem"></i> <span data-i18n="tab_beauty">Beauty</span>
            </button>
        </div>

        <div class="service-grid">
            <?php foreach ($services as $service): ?>
                <article class="service-card reveal" data-service-card data-category="<?php echo htmlspecialchars($service['category']); ?>">
                    <div class="service-card__media">
                        <img src="<?php echo htmlspecialchars($service['image']); ?>" alt="<?php echo ecopy($service['alt_key']); ?>" data-i18n-alt="<?php echo htmlspecialchars($service['alt_key']); ?>" loading="lazy">
                    </div>
                    <div class="service-card__content">
                        <span class="service-card__badge"><i class="fa-solid fa-star"></i> <span data-i18n="service_badge">Signature</span></span>
                        <h3 data-i18n="<?php echo htmlspecialchars($service['title_key']); ?>"><?php echo ecopy($service['title_key']); ?></h3>
                        <p data-i18n="<?php echo htmlspecialchars($service['desc_key']); ?>"><?php echo ecopy($service['desc_key']); ?></p>
                        <div class="card-actions">
                            <a class="btn btn--mini" href="tel:+971501234567"><i class="fa-solid fa-phone"></i> <span data-i18n="btn_call">Call</span></a>
                            <a class="btn btn--mini btn--whatsapp" href="https://wa.me/971501234567" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp"></i> <span data-i18n="btn_whatsapp">WhatsApp</span></a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
