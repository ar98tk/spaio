<section class="section therapists" id="about" aria-labelledby="therapists-title">
    <div class="container">
        <div class="section-heading reveal">
            <p class="eyebrow"><i class="fa-solid fa-user-doctor"></i> <span data-i18n="therapists_eyebrow">Expert therapists</span></p>
            <h2 id="therapists-title" data-i18n="therapists_title">A Refined Wellness Team</h2>
            <p data-i18n="therapists_intro">Professional therapists trained to deliver attentive, discreet, and beautifully paced spa experiences.</p>
        </div>

        <div class="carousel reveal" data-carousel>
            <button class="carousel__control carousel__control--prev" type="button" aria-label="Previous therapist" data-carousel-prev>
                <i class="fa-solid fa-chevron-left"></i>
            </button>

            <div class="carousel__viewport">
                <div class="carousel__track" data-carousel-track>
                    <?php foreach ($therapists as $index => $therapist): ?>
                        <article class="therapist-card" data-carousel-slide>
                            <div class="therapist-card__image">
                                <img src="<?php echo htmlspecialchars($therapist['image']); ?>" alt="Laveira Spa therapist <?php echo $index + 1; ?>" loading="lazy">
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>

            <button class="carousel__control carousel__control--next" type="button" aria-label="Next therapist" data-carousel-next>
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>

        <div class="carousel-dots" data-carousel-dots aria-label="Therapist carousel pagination"></div>
    </div>
</section>
