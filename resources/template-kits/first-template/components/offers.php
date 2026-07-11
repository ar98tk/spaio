<section class="section offers" id="offers" aria-labelledby="offers-title">
    <div class="container">
        <div class="section-heading reveal">
            <p class="eyebrow"><i class="fa-solid fa-gift"></i> <span data-i18n="offers_eyebrow">Limited luxury packages</span></p>
            <h2 id="offers-title" data-i18n="offers_title">Exclusive Dubai Spa Offers</h2>
            <p data-i18n="offers_intro">High-value experiences presented with the atmosphere, privacy, and refinement expected from a premium Dubai spa.</p>
        </div>

        <div class="offers-grid">
            <?php foreach ($offers as $offer): ?>
                <article class="offer-card reveal <?php echo $offer['featured'] ? 'offer-card--featured' : ''; ?>">
                    <div class="offer-card__shine" aria-hidden="true"></div>
                    <p class="offer-card__eyebrow" data-i18n="<?php echo htmlspecialchars($offer['eyebrow_key']); ?>"><?php echo ecopy($offer['eyebrow_key']); ?></p>
                    <h3 data-i18n="<?php echo htmlspecialchars($offer['title_key']); ?>"><?php echo ecopy($offer['title_key']); ?></h3>
                    <ul class="offer-card__details">
                        <?php foreach ($offer['details'] as $item): ?>
                            <li><i class="fa-solid fa-check"></i> <span data-i18n="<?php echo htmlspecialchars($item); ?>"><?php echo ecopy($item); ?></span></li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="offer-card__complimentary">
                        <h4><i class="fa-solid fa-gem"></i> <span data-i18n="offer_complimentary_title">Complimentary Treatments</span></h4>
                        <ul>
                        <?php foreach ($offer['complimentary'] as $item): ?>
                            <li><i class="fa-solid fa-sparkles"></i> <span data-i18n="<?php echo htmlspecialchars($item); ?>"><?php echo ecopy($item); ?></span></li>
                        <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="offer-card__bottom">
                        <p class="price" data-i18n="<?php echo htmlspecialchars($offer['price_key']); ?>"><?php echo ecopy($offer['price_key']); ?></p>
                        <a class="btn btn--primary btn--compact" href="https://wa.me/971501234567" target="_blank" rel="noopener">
                            <i class="fa-solid fa-calendar-check"></i>
                            <span data-i18n="cta_reserve">Reserve</span>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
