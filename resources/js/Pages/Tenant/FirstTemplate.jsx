import { Head, useForm, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { useTenantActionTracking } from '../../lib/tenantActionTracking';

const templateBase = '/template-kits/first-template';
const defaultLogo = '/default.png';
const fallbackHero = 'https://images.unsplash.com/photo-1600334129128-685c5582fd35?auto=format&fit=crop&w=2400&q=86';
const fallbackService = 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&w=900&h=675&q=85';
const fallbackPackage = 'https://images.unsplash.com/photo-1519823551278-64ac92734fb1?auto=format&fit=crop&w=900&h=675&q=85';
const fallbackGallery = 'https://images.unsplash.com/photo-1515377905703-c4788e51af15?auto=format&fit=crop&w=900&h=675&q=85';

const uploadUrl = (path) => {
  if (!path) return null;
  return /^https?:\/\//i.test(path) ? path : `/uploads/${path}`;
};
const templateAsset = (path) => `${templateBase}/${path}`;
const money = (value, currency = 'USD') => new Intl.NumberFormat('en', {
  style: 'currency',
  currency,
  maximumFractionDigits: 0,
}).format(Number(value ?? 0));

function whatsappUrl(number, message) {
  const digits = String(number ?? '').replace(/\D/g, '');
  return digits ? `https://wa.me/${digits}?text=${encodeURIComponent(message)}` : null;
}

function CardActions({ tenant, label, compact = false }) {
  const whatsapp = whatsappUrl(tenant.whatsapp_number, `Hello, I am interested in ${label}.`);

  return (
    <div className="card-actions">
      {tenant.show_call_button && tenant.phone && (
        <a className={`btn ${compact ? 'btn--compact' : 'btn--mini'}`} href={`tel:${tenant.phone}`}>
          <i className="fa-solid fa-phone" />
          <span>Call</span>
        </a>
      )}
      {tenant.show_whatsapp_button && whatsapp && (
        <a className={`btn btn--whatsapp ${compact ? 'btn--compact' : 'btn--mini'}`} href={whatsapp} target="_blank" rel="noopener noreferrer">
          <i className="fa-brands fa-whatsapp" />
          <span>WhatsApp</span>
        </a>
      )}
      {tenant.show_location_button && tenant.google_maps_url && (
        <a className={`btn ${compact ? 'btn--compact' : 'btn--mini'}`} href={tenant.google_maps_url} target="_blank" rel="noopener noreferrer">
          <i className="fa-solid fa-location-dot" />
          <span>Location</span>
        </a>
      )}
    </div>
  );
}

function Navbar({ tenant }) {
  const [open, setOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);
  const logo = uploadUrl(tenant.logo_path) ?? defaultLogo;
  const whatsapp = whatsappUrl(tenant.whatsapp_number, `Hello, I would like to book at ${tenant.business_name}.`);

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 16);
    onScroll();
    window.addEventListener('scroll', onScroll);
    return () => window.removeEventListener('scroll', onScroll);
  }, []);

  const close = () => setOpen(false);

  return (
    <header className={`site-header ${scrolled ? 'is-scrolled' : ''}`} data-header>
      <nav className="navbar" aria-label="Primary navigation">
        <a className="brand" href="#home" aria-label={`${tenant.business_name} home`} onClick={close}>
          <img src={logo} alt={`${tenant.business_name} logo`} className="brand__logo" />
          <span className="brand__text">
            <span>{tenant.business_name}</span>
            <small>Luxury Spa</small>
          </span>
        </a>

        <button className="nav-toggle" type="button" aria-label="Open menu" aria-expanded={open} onClick={() => setOpen((value) => !value)}>
          <i className="fa-solid fa-bars" />
        </button>

        <div className={`nav-panel ${open ? 'is-open' : ''}`} data-nav-panel>
          <ul className="nav-links">
            <li><a href="#home" onClick={close}>Home</a></li>
            <li><a href="#offers" onClick={close}>Offers</a></li>
            <li><a href="#services" onClick={close}>Services</a></li>
            <li><a href="#about" onClick={close}>About</a></li>
            <li><a href="#gallery" onClick={close}>Gallery</a></li>
            <li><a href="#contact" onClick={close}>Contact Us</a></li>
          </ul>

          <div className="nav-actions" aria-label="Contact actions">
            {whatsapp && <a className="icon-link" href={whatsapp} target="_blank" rel="noopener noreferrer" aria-label="WhatsApp"><i className="fa-brands fa-whatsapp" /></a>}
            {tenant.phone && <a className="icon-link" href={`tel:${tenant.phone}`} aria-label="Phone call"><i className="fa-solid fa-phone" /></a>}
            {tenant.google_maps_url && <a className="icon-link" href={tenant.google_maps_url} target="_blank" rel="noopener noreferrer" aria-label="Google Maps"><i className="fa-solid fa-location-dot" /></a>}
          </div>
        </div>
      </nav>
    </header>
  );
}

function Hero({ tenant, services }) {
  const heroImage = uploadUrl(tenant.hero_image_path) ?? fallbackHero;
  const whatsapp = whatsappUrl(tenant.whatsapp_number, `Hello, I would like to book at ${tenant.business_name}.`);

  return (
    <section className="hero section" id="home" aria-labelledby="hero-title">
      <div
        className="hero__bg"
        aria-hidden="true"
        style={{
          background: `linear-gradient(90deg, rgba(5, 4, 3, 0.92), rgba(5, 4, 3, 0.52) 46%, rgba(5, 4, 3, 0.82)), linear-gradient(180deg, rgba(5, 4, 3, 0.24), rgba(5, 4, 3, 0.9)), url("${heroImage}") center/cover`,
        }}
      />
      <div className="hero__ribbon hero__ribbon--one" aria-hidden="true" />
      <div className="hero__ribbon hero__ribbon--two" aria-hidden="true" />
      <div className="hero__sparkles" aria-hidden="true">
        <i className="fa-solid fa-spa" />
        <i className="fa-regular fa-gem" />
        <i className="fa-solid fa-moon" />
      </div>

      <div className="container hero__content reveal is-visible">
        <p className="eyebrow"><i className="fa-solid fa-crown" /> Private luxury wellness escape</p>
        <h1 id="hero-title">{tenant.hero_title || `A Royal Spa Ritual Crafted for ${tenant.business_name}`}</h1>
        <p className="hero__lead">
          {tenant.hero_subtitle || `Step into ${tenant.business_name}, where refined hospitality, golden serenity, and deeply restorative treatments create a private sanctuary for body and soul.`}
        </p>
        <div className="hero__actions">
          {whatsapp && (
            <a className="btn btn--primary" href={whatsapp} target="_blank" rel="noopener noreferrer">
              <i className="fa-brands fa-whatsapp" />
              <span>Book Now</span>
            </a>
          )}
          <a className="btn btn--ghost" href="#services">
            <i className="fa-solid fa-wand-magic-sparkles" />
            <span>Explore Services</span>
          </a>
        </div>
        <dl className="hero__stats" aria-label="Spa highlights">
          <div>
            <dt>{services.length || '12+'}</dt>
            <dd>Signature rituals</dd>
          </div>
          <div>
            <dt>5-star</dt>
            <dd>Guest experience</dd>
          </div>
          <div>
            <dt>24/7</dt>
            <dd>Booking support</dd>
          </div>
        </dl>
      </div>

      <a href="#services" className="scroll-cue" aria-label="Scroll to services">
        <span />
      </a>
    </section>
  );
}

function BookingModal({ booking, bookingEndpoint, currency, onClose, onBooked }) {
  const form = useForm({
    item_type: '',
    item_id: '',
    customer_name: '',
    customer_phone: '',
    customer_email: '',
    preferred_date: '',
    preferred_time: '',
    notes: '',
  });

  useEffect(() => {
    if (booking) {
      form.setData({
        item_type: booking.type,
        item_id: booking.id,
        customer_name: '',
        customer_phone: '',
        customer_email: '',
        preferred_date: '',
        preferred_time: '',
        notes: '',
      });
      form.clearErrors();
    }
  }, [booking?.type, booking?.id]);

  if (!booking) return null;

  function submit(event) {
    event.preventDefault();
    form.post(bookingEndpoint, {
      preserveScroll: true,
      onSuccess: () => {
        form.reset();
        onBooked();
        onClose();
      },
    });
  }

  return (
    <div className="booking-modal" role="dialog" aria-modal="true" aria-labelledby="booking-title">
      <button className="booking-modal__backdrop" type="button" aria-label="Close booking form" onClick={onClose} />
      <form className="booking-modal__panel" onSubmit={submit}>
        <button className="booking-modal__close" type="button" aria-label="Close booking form" onClick={onClose}>
          <i className="fa-solid fa-xmark" />
        </button>
        <p className="eyebrow"><i className="fa-solid fa-calendar-check" /> Booking request</p>
        <h2 id="booking-title">Book {booking.name}</h2>
        <p className="booking-modal__price">{money(booking.price, currency)}</p>

        <div className="booking-form">
          <label>
            <span>Your name</span>
            <input type="text" value={form.data.customer_name} onChange={(event) => form.setData('customer_name', event.target.value)} required />
            {form.errors.customer_name && <small>{form.errors.customer_name}</small>}
          </label>
          <label>
            <span>Phone number</span>
            <input type="tel" value={form.data.customer_phone} onChange={(event) => form.setData('customer_phone', event.target.value)} required />
            {form.errors.customer_phone && <small>{form.errors.customer_phone}</small>}
          </label>
          <label>
            <span>Email address</span>
            <input type="email" value={form.data.customer_email} onChange={(event) => form.setData('customer_email', event.target.value)} />
            {form.errors.customer_email && <small>{form.errors.customer_email}</small>}
          </label>
          <div className="booking-form__row">
            <label>
              <span>Preferred date</span>
              <input type="date" value={form.data.preferred_date} onChange={(event) => form.setData('preferred_date', event.target.value)} />
              {form.errors.preferred_date && <small>{form.errors.preferred_date}</small>}
            </label>
            <label>
              <span>Preferred time</span>
              <input type="time" value={form.data.preferred_time} onChange={(event) => form.setData('preferred_time', event.target.value)} />
              {form.errors.preferred_time && <small>{form.errors.preferred_time}</small>}
            </label>
          </div>
          <label>
            <span>Notes</span>
            <textarea value={form.data.notes} onChange={(event) => form.setData('notes', event.target.value)} placeholder="Any preference, guest count, or special request?" />
            {form.errors.notes && <small>{form.errors.notes}</small>}
          </label>
        </div>

        <button className="btn btn--primary booking-modal__submit" type="submit" disabled={form.processing}>
          <i className="fa-solid fa-paper-plane" />
          <span>{form.processing ? 'Sending...' : 'Send Booking Request'}</span>
        </button>
      </form>
    </div>
  );
}

function Services({ tenant, services, onBook }) {
  return (
    <section className="section services" id="services" aria-labelledby="services-title">
      <div className="container">
        <div className="section-heading reveal is-visible">
          <p className="eyebrow"><i className="fa-solid fa-spa" /> Curated rituals</p>
          <h2 id="services-title">Premium Spa Services</h2>
          <p>Choose from refined treatments designed for deep relaxation, glowing skin, and polished luxury wellness.</p>
        </div>

        <div className="service-grid">
          {services.map((service) => (
            <article className="service-card reveal is-visible" key={service.id}>
              <div className="service-card__media">
                <img src={uploadUrl(service.image_path) ?? fallbackService} alt={service.name} loading="lazy" />
              </div>
              <div className="service-card__content">
                <span className="service-card__badge"><i className="fa-solid fa-star" /> Signature</span>
                <h3>{service.name}</h3>
                <p>{service.description}</p>
                <p className="price">{money(service.price, tenant.currency_code)}</p>
                <button
                  type="button"
                  className="btn btn--primary btn--booking"
                  onClick={() => onBook({ type: 'service', id: service.id, name: service.name, price: service.price })}
                >
                  <i className="fa-solid fa-calendar-check" />
                  <span>Book Now</span>
                </button>
                <CardActions tenant={tenant} label={service.name} />
              </div>
            </article>
          ))}
        </div>

        {!services.length && <p className="section-empty">Services will appear here after they are added from the dashboard.</p>}
      </div>
    </section>
  );
}

function Offers({ tenant, packages, onBook }) {
  return (
    <section className="section offers" id="offers" aria-labelledby="offers-title">
      <div className="container">
        <div className="section-heading reveal is-visible">
          <p className="eyebrow"><i className="fa-solid fa-gift" /> Limited luxury packages</p>
          <h2 id="offers-title">Exclusive Spa Offers</h2>
          <p>High-value experiences presented with the atmosphere, privacy, and refinement expected from a premium spa.</p>
        </div>

        <div className="offers-grid">
          {packages.map((pkg, index) => (
            <article className={`offer-card reveal is-visible ${index % 4 === 0 ? 'offer-card--featured' : ''}`} key={pkg.id}>
              <div className="offer-card__shine" aria-hidden="true" />
              <div className="offer-card__image">
                <img src={uploadUrl(pkg.image_path) ?? fallbackPackage} alt={pkg.name} loading="lazy" />
              </div>
              <p className="offer-card__eyebrow">Spa package</p>
              <h3>{pkg.name}</h3>
              <ul className="offer-card__details">
                {String(pkg.included_description).split(/\r?\n/).filter(Boolean).map((line) => (
                  <li key={line}><i className="fa-solid fa-check" /> <span>{line}</span></li>
                ))}
              </ul>
              <div className="offer-card__bottom">
                <div>
                  <p className="price">{money(pkg.discounted_price, tenant.currency_code)}</p>
                  <p className="offer-card__eyebrow" style={{ marginTop: 4, textDecoration: 'line-through', opacity: 0.72 }}>{money(pkg.original_price, tenant.currency_code)}</p>
                </div>
                <div className="offer-card__actions">
                  <button
                    type="button"
                    className="btn btn--primary btn--compact"
                    onClick={() => onBook({ type: 'package', id: pkg.id, name: pkg.name, price: pkg.discounted_price })}
                  >
                    <i className="fa-solid fa-calendar-check" />
                    <span>Book Now</span>
                  </button>
                  <CardActions tenant={tenant} label={pkg.name} compact />
                </div>
              </div>
            </article>
          ))}
        </div>

        {!packages.length && <p className="section-empty">Offers will appear here after they are added from the dashboard.</p>}
      </div>
    </section>
  );
}

function Therapists({ staff }) {
  const team = staff;

  return (
    <section className="section therapists" id="about" aria-labelledby="therapists-title">
      <div className="container">
        <div className="section-heading reveal is-visible">
          <p className="eyebrow"><i className="fa-solid fa-user-doctor" /> Expert therapists</p>
          <h2 id="therapists-title">A Refined Wellness Team</h2>
          <p>Professional therapists trained to deliver attentive, discreet, and beautifully paced spa experiences.</p>
        </div>

        {team.length ? (
          <div className="staff-grid reveal is-visible">
            {team.map((member) => (
              <article className="staff-card" key={member.id}>
                <div className="staff-card__image">
                  <img src={uploadUrl(member.photo_path) ?? defaultLogo} alt={member.name} loading="lazy" />
                </div>
                <div className="staff-card__content">
                  <span className="service-card__badge"><i className="fa-solid fa-gem" /> Therapist</span>
                  <h3>{member.name}</h3>
                  {member.role && <p className="staff-card__role">{member.role}</p>}
                  {member.bio && <p>{member.bio}</p>}
                </div>
              </article>
            ))}
          </div>
        ) : (
          <p className="section-empty">Staff profiles will appear here after they are added from the dashboard.</p>
        )}
      </div>
    </section>
  );
}

function Gallery({ gallery }) {
  return (
    <section className="section services" id="gallery" aria-labelledby="gallery-title">
      <div className="container">
        <div className="section-heading reveal is-visible">
          <p className="eyebrow"><i className="fa-regular fa-images" /> Spa ambience</p>
          <h2 id="gallery-title">Inside the Spa</h2>
          <p>Showcase treatment rooms, reception details, and the atmosphere customers can expect before they arrive.</p>
        </div>
        <div className="service-grid">
          {(gallery.length ? gallery : [{ id: 'fallback-gallery', title: 'Spa ambience', image_path: null }]).map((image) => (
            <article className="service-card reveal is-visible" key={image.id}>
              <div className="service-card__media">
                <img src={uploadUrl(image.image_path) ?? fallbackGallery} alt={image.alt_text ?? image.title ?? 'Spa gallery'} loading="lazy" />
              </div>
              <div className="service-card__content">
                <span className="service-card__badge"><i className="fa-solid fa-star" /> Gallery</span>
                <h3>{image.title ?? 'Spa ambience'}</h3>
                <p>{image.alt_text ?? 'Gallery images will appear here after the spa owner uploads them.'}</p>
              </div>
            </article>
          ))}
        </div>
      </div>
    </section>
  );
}

function Footer({ tenant }) {
  const logo = uploadUrl(tenant.logo_path) ?? defaultLogo;
  const whatsapp = whatsappUrl(tenant.whatsapp_number, `Hello, I would like to book at ${tenant.business_name}.`);
  const socialLinks = [
    ['Facebook', 'facebook-f', tenant.facebook_url],
    ['Instagram', 'instagram', tenant.instagram_url],
    ['Snapchat', 'snapchat', tenant.snapchat_url],
    ['YouTube', 'youtube', tenant.youtube_url],
    ['TikTok', 'tiktok', tenant.tiktok_url],
    ['X Twitter', 'x-twitter', tenant.x_url],
    ['WhatsApp', 'whatsapp', whatsapp],
  ].filter(([, , url]) => url);

  return (
    <footer className="footer" id="contact">
      <div className="container footer__grid">
        <div className="footer__brand">
          <img src={logo} alt={`${tenant.business_name} logo`} />
          <h2>{tenant.business_name}</h2>
          <p>{tenant.footer_content || tenant.about_content || 'An intimate sanctuary for golden rituals, soft stillness, and premium spa hospitality.'}</p>
          <div className="socials" aria-label="Social media links">
            {socialLinks.map(([label, icon, url]) => (
              <a key={label} href={url} target="_blank" rel="noopener noreferrer" aria-label={label}>
                <i className={`fa-brands fa-${icon}`} />
              </a>
            ))}
            {tenant.google_maps_url && <a href={tenant.google_maps_url} target="_blank" rel="noopener noreferrer" aria-label="Location"><i className="fa-solid fa-location-dot" /></a>}
          </div>
        </div>

        <div className="footer__contact">
          <h3>Contact Information</h3>
          <ul>
            {tenant.address && <li><i className="fa-solid fa-location-dot" /> <span>{tenant.address}</span></li>}
            {tenant.phone && <li><i className="fa-solid fa-phone" /> <a href={`tel:${tenant.phone}`}>{tenant.phone}</a></li>}
            {whatsapp && <li><i className="fa-brands fa-whatsapp" /> <a href={whatsapp} target="_blank" rel="noopener noreferrer">WhatsApp Booking</a></li>}
            <li><i className="fa-regular fa-clock" /> <span>Open daily</span></li>
          </ul>
        </div>

        <div className="footer__map">
          <h3>Find Us</h3>
          <div className="map-placeholder">
            {tenant.google_maps_url ? (
              <a className="btn btn--primary" href={tenant.google_maps_url} target="_blank" rel="noopener noreferrer">
                <i className="fa-solid fa-map-location-dot" />
                <span>Open Google Maps</span>
              </a>
            ) : (
              <p>Google Maps link will appear here after it is added in settings.</p>
            )}
          </div>
        </div>
      </div>

      <div className="footer__bottom">
        <p>Copyright {new Date().getFullYear()} {tenant.business_name}. All rights reserved.</p>
      </div>
    </footer>
  );
}

export default function FirstTemplate({ tenant, services = [], packages = [], staff = [], gallery = [], booking_endpoint, action_endpoint }) {
  useTenantActionTracking(action_endpoint);
  const { flash = {} } = usePage().props;
  const [booking, setBooking] = useState(null);
  const [bookingNotice, setBookingNotice] = useState(flash.success ?? '');

  useEffect(() => {
    if (!bookingNotice) return undefined;

    const timeout = window.setTimeout(() => setBookingNotice(false), 5200);

    return () => window.clearTimeout(timeout);
  }, [bookingNotice]);

  useEffect(() => {
    if (flash.success) {
      setBookingNotice(flash.success);
    }
  }, [flash.success]);

  return (
    <div className="first-template-site">
      <Head title={tenant.business_name}>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="" />
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
        <link rel="icon" href={defaultLogo} type="image/png" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
        <link rel="stylesheet" href={templateAsset('css/main.css')} />
      </Head>

      <a className="skip-link" href="#main">Skip to content</a>
      <Navbar tenant={tenant} />
      <main id="main">
        <Hero tenant={tenant} services={services} />
        <Offers tenant={tenant} packages={packages} onBook={setBooking} />
        <Services tenant={tenant} services={services} onBook={setBooking} />
        <Therapists staff={staff} />
        <Gallery gallery={gallery} />
      </main>
      <Footer tenant={tenant} />
      {bookingNotice && (
        <div className="booking-alert" role="alert" aria-live="polite">
          <span className="booking-alert__icon"><i className="fa-solid fa-circle-check" /></span>
          <span>{bookingNotice}</span>
          <button type="button" aria-label="Close success alert" onClick={() => setBookingNotice('')}>
            <i className="fa-solid fa-xmark" />
          </button>
        </div>
      )}
      <BookingModal
        booking={booking}
        bookingEndpoint={booking_endpoint}
        currency={tenant.currency_code}
        onClose={() => setBooking(null)}
        onBooked={() => setBookingNotice('Booking request sent. The spa team will contact you shortly.')}
      />
    </div>
  );
}
