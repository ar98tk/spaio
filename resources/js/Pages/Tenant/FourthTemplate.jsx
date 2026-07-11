import { Head, useForm, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { useTenantActionTracking } from '../../lib/tenantActionTracking';
import {
  CalendarCheck,
  CheckCircle2,
  Gem,
  Heart,
  MapPin,
  Menu,
  MessageCircle,
  Phone,
  Send,
  Sparkles,
  Star,
  X,
} from 'lucide-react';

const templateBase = '/template-kits/fourth-template';
const defaultLogo = '/default.png';

const fallbackImages = {
  hero: `${templateBase}/img/slider/slider_bg.png`,
  heroFigure: `${templateBase}/img/slider/slider_img02.png`,
  about: `${templateBase}/img/features/about_img.png`,
  service: `${templateBase}/img/features/services-img-details2.png`,
  package: `${templateBase}/img/bg/price-bg.png`,
  staff: `${templateBase}/img/team/team01.png`,
  gallery: `${templateBase}/img/gallery/protfolio-img01.png`,
};

const uploadUrl = (path) => {
  if (!path) return null;
  return /^https?:\/\//i.test(path) ? path : `/uploads/${path}`;
};

const money = (value, currency = 'USD') => new Intl.NumberFormat('en', {
  style: 'currency',
  currency,
  maximumFractionDigits: 0,
}).format(Number(value ?? 0));

function whatsappUrl(number, message) {
  const digits = String(number ?? '').replace(/\D/g, '');
  return digits ? `https://wa.me/${digits}?text=${encodeURIComponent(message)}` : null;
}

function Header({ tenant }) {
  const [open, setOpen] = useState(false);
  const logo = uploadUrl(tenant.logo_path) ?? defaultLogo;
  const links = [
    ['About', '#about'],
    ['Services', '#services'],
    ['Offers', '#offers'],
    ['Team', '#team'],
    ['Gallery', '#gallery'],
    ['Contact', '#contact'],
  ];

  return (
    <header className="ft-header">
      <nav className="ft-shell ft-nav" aria-label="Primary navigation">
        <a className="ft-brand" href="#home" onClick={() => setOpen(false)}>
          <img src={logo} alt={`${tenant.business_name} logo`} />
          <span>
            <strong>{tenant.business_name}</strong>
            <small>Spa & beauty salon</small>
          </span>
        </a>

        <button className="ft-menu-button" type="button" aria-label="Open menu" aria-expanded={open} onClick={() => setOpen((value) => !value)}>
          <Menu size={22} />
        </button>

        <div className={`ft-nav-links ${open ? 'is-open' : ''}`}>
          {links.map(([label, href]) => (
            <a key={label} href={href} onClick={() => setOpen(false)}>{label}</a>
          ))}
          <a className="ft-nav-button" href="#services" onClick={() => setOpen(false)}>Book Appointment</a>
        </div>
      </nav>
    </header>
  );
}

function SectionTitle({ eyebrow, title, text, light = false }) {
  return (
    <div className={`ft-section-title ${light ? 'ft-section-title--light' : ''}`}>
      <p>{eyebrow}</p>
      <h2>{title}</h2>
      {text && <span>{text}</span>}
    </div>
  );
}

function CardActions({ tenant, label }) {
  const whatsapp = whatsappUrl(tenant.whatsapp_number, `Hello, I am interested in ${label}.`);

  if (!tenant.show_whatsapp_button && !tenant.show_call_button && !tenant.show_location_button) return null;

  return (
    <div className="ft-card-actions">
      {tenant.show_whatsapp_button && whatsapp && (
        <a href={whatsapp} target="_blank" rel="noreferrer"><MessageCircle size={15} /> WhatsApp</a>
      )}
      {tenant.show_call_button && tenant.phone && (
        <a href={`tel:${tenant.phone}`}><Phone size={15} /> Call</a>
      )}
      {tenant.show_location_button && tenant.google_maps_url && (
        <a href={tenant.google_maps_url} target="_blank" rel="noreferrer"><MapPin size={15} /> Location</a>
      )}
    </div>
  );
}

function BookingModal({ booking, endpoint, currency, onClose, onBooked }) {
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
    if (!booking) return;

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
  }, [booking?.type, booking?.id]);

  if (!booking) return null;

  function submit(event) {
    event.preventDefault();
    form.post(endpoint, {
      preserveScroll: true,
      onSuccess: () => {
        form.reset();
        onBooked('Booking request sent. The spa team will contact you shortly.');
        onClose();
      },
    });
  }

  return (
    <div className="ft-booking" role="dialog" aria-modal="true" aria-labelledby="ft-booking-title">
      <button className="ft-booking__backdrop" type="button" aria-label="Close booking form" onClick={onClose} />
      <form className="ft-booking__panel" onSubmit={submit}>
        <button className="ft-booking__close" type="button" aria-label="Close booking form" onClick={onClose}>
          <X size={18} />
        </button>
        <p className="ft-kicker">Appointment request</p>
        <h2 id="ft-booking-title">Book {booking.name}</h2>
        <strong>{money(booking.price, currency)}</strong>

        <div className="ft-booking__fields">
          <label>
            <span>Your name</span>
            <input value={form.data.customer_name} onChange={(event) => form.setData('customer_name', event.target.value)} required />
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
          <div className="ft-booking__row">
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
            <textarea value={form.data.notes} onChange={(event) => form.setData('notes', event.target.value)} placeholder="Treatment notes, guest count, or preferred therapist" />
            {form.errors.notes && <small>{form.errors.notes}</small>}
          </label>
        </div>

        <button className="ft-button ft-button--pink ft-booking__submit" type="submit" disabled={form.processing}>
          <Send size={16} />
          {form.processing ? 'Sending...' : 'Send booking request'}
        </button>
      </form>
    </div>
  );
}

function Hero({ tenant, services, onBook }) {
  const hero = uploadUrl(tenant.hero_image_path) ?? fallbackImages.hero;
  const firstService = services[0];

  return (
    <section className="ft-hero" id="home" style={{ '--ft-hero-bg': `url("${hero}")` }}>
      <div className="ft-shell ft-hero__grid">
        <div className="ft-hero__content">
          <p className="ft-kicker">Spa & wellness salon</p>
          <h1>{tenant.hero_title || `Make your skin shine at ${tenant.business_name}`}</h1>
          <p>{tenant.hero_subtitle || 'Reserve beauty, massage, hammam, and spa packages from one elegant online experience.'}</p>
          <div className="ft-hero__actions">
            <a className="ft-button ft-button--pink" href="#services"><CalendarCheck size={18} /> Discover services</a>
            {firstService && (
              <button className="ft-button ft-button--outline" type="button" onClick={() => onBook({ type: 'service', id: firstService.id, name: firstService.name, price: firstService.price })}>
                Book now
              </button>
            )}
          </div>
        </div>
        <div className="ft-hero__visual">
          <img src={fallbackImages.heroFigure} alt={`${tenant.business_name} beauty ritual`} />
          <div className="ft-hero-badge">
            <Gem size={22} />
            <span>Premium rituals</span>
            <strong>{services.length} bookable services</strong>
          </div>
        </div>
      </div>
    </section>
  );
}

function PromoBanners({ packages, onBook }) {
  const promos = [
    ['Offer for glow', 'Best care for skin and body', `${templateBase}/img/features/banner-01.png`],
    ['Offer for wellness', 'Get the best calm with our rituals', `${templateBase}/img/features/banner-02.png`],
    ['Offer for couples', 'Private spa escapes for two', `${templateBase}/img/features/banner-03.png`],
  ];

  return (
    <section className="ft-promos">
      {promos.map(([eyebrow, title, image], index) => {
        const pkg = packages[index % Math.max(packages.length, 1)];

        return (
          <article className="ft-promo-card" key={title}>
            <div>
              <p>{eyebrow}</p>
              <h3>{pkg?.name ?? title}</h3>
              <button type="button" onClick={() => pkg && onBook({ type: 'package', id: pkg.id, name: pkg.name, price: pkg.discounted_price })} disabled={!pkg}>
                Start Now
              </button>
            </div>
            <img src={uploadUrl(pkg?.image_path) ?? image} alt={pkg?.name ?? title} />
          </article>
        );
      })}
    </section>
  );
}

function About({ tenant }) {
  return (
    <section className="ft-section ft-about" id="about">
      <div className="ft-shell ft-about__grid">
        <div className="ft-about__image">
          <img src={uploadUrl(tenant.hero_image_path) ?? fallbackImages.about} alt={`${tenant.business_name} spa care`} />
          <div><strong>15</strong><span>Years of experience</span></div>
        </div>
        <div className="ft-about__content">
          <SectionTitle eyebrow="About us" title="Best way to care for your skin" text={tenant.about_content || `${tenant.business_name} offers polished beauty care, restorative massage, and premium spa hospitality in a private wellness setting.`} />
          <div className="ft-about-list">
            <span><Heart size={18} /> Skin, body, and relaxation care</span>
            <span><Sparkles size={18} /> Premium spa rooms and curated rituals</span>
            <span><CalendarCheck size={18} /> Online bookings connected to the dashboard</span>
          </div>
        </div>
      </div>
    </section>
  );
}

function Services({ tenant, services, onBook }) {
  return (
    <section className="ft-section ft-services" id="services">
      <div className="ft-shell">
        <SectionTitle eyebrow="Our services" title="What we provide" text="All service cards are powered by the tenant dashboard: images, prices, descriptions, and booking actions." />
        <div className="ft-service-grid">
          {services.map((service, index) => (
            <article className="ft-service-card" key={service.id}>
              <div className="ft-service-card__media">
                <img src={uploadUrl(service.image_path) ?? fallbackImages.service} alt={service.name} />
                <span>{String(index + 1).padStart(2, '0')}</span>
              </div>
              <div>
                <h3>{service.name}</h3>
                <p>{service.description}</p>
                <strong>{money(service.price, tenant.currency_code)}</strong>
                <button className="ft-button ft-button--pink" type="button" onClick={() => onBook({ type: 'service', id: service.id, name: service.name, price: service.price })}>
                  <CalendarCheck size={16} /> Book Now
                </button>
                <CardActions tenant={tenant} label={service.name} />
              </div>
            </article>
          ))}
        </div>
        {!services.length && <p className="ft-empty">Services will appear here after they are published.</p>}
      </div>
    </section>
  );
}

function Offers({ tenant, packages, onBook }) {
  return (
    <section className="ft-section ft-offers" id="offers">
      <div className="ft-shell">
        <SectionTitle light eyebrow="Choose package" title="Our pricing plan" text="Packages keep the same tenant content and booking behavior while adopting this template's pricing-card style." />
        <div className="ft-package-grid">
          {packages.map((pkg, index) => (
            <article className={`ft-package-card ${index === 1 ? 'is-featured' : ''}`} key={pkg.id}>
              <div className="ft-package-head">
                <span>{index === 1 ? 'Popular' : 'Package'}</span>
                <h3>{pkg.name}</h3>
                <div>
                  <small>{money(pkg.original_price, tenant.currency_code)}</small>
                  <strong>{money(pkg.discounted_price, tenant.currency_code)}</strong>
                </div>
              </div>
              <ul>
                {String(pkg.included_description ?? '').split(/\r?\n/).filter(Boolean).map((item) => (
                  <li key={item}><CheckCircle2 size={16} /> {item}</li>
                ))}
              </ul>
              <button className="ft-button ft-button--pink" type="button" onClick={() => onBook({ type: 'package', id: pkg.id, name: pkg.name, price: pkg.discounted_price })}>
                <CalendarCheck size={16} /> Book Now
              </button>
              <CardActions tenant={tenant} label={pkg.name} />
            </article>
          ))}
        </div>
        {!packages.length && <p className="ft-empty ft-empty--light">Offers will appear here after they are published.</p>}
      </div>
    </section>
  );
}

function Staff({ staff }) {
  return (
    <section className="ft-section" id="team">
      <div className="ft-shell">
        <SectionTitle eyebrow="Our team" title="Meet the beauty specialists" text="Staff profiles are shared across templates and controlled by the tenant dashboard." />
        <div className="ft-staff-grid">
          {staff.map((member, index) => (
            <article className="ft-staff-card" key={member.id}>
              <img src={uploadUrl(member.photo_path) ?? `${templateBase}/img/team/team0${(index % 4) + 1}.png`} alt={member.name} />
              <div>
                <h3>{member.name}</h3>
                {member.role && <span>{member.role}</span>}
                {member.bio && <p>{member.bio}</p>}
              </div>
            </article>
          ))}
        </div>
        {!staff.length && <p className="ft-empty">Staff profiles will appear here after they are published.</p>}
      </div>
    </section>
  );
}

function Gallery({ gallery, tenant }) {
  const items = gallery.length ? gallery : [{ id: 'fallback', title: tenant.business_name, image_path: null }];

  return (
    <section className="ft-section ft-gallery" id="gallery">
      <div className="ft-shell">
        <SectionTitle eyebrow="Our gallery" title="See our portfolio" text="Gallery photos use the same tenant uploads and adapt to this template's visual portfolio grid." />
        <div className="ft-gallery-grid">
          {items.map((image, index) => (
            <figure key={image.id} className={index % 5 === 0 ? 'is-tall' : ''}>
              <img src={uploadUrl(image.image_path) ?? `${templateBase}/img/gallery/protfolio-img0${(index % 6) + 1}.png`} alt={image.alt_text ?? image.title ?? 'Spa gallery'} />
              <figcaption>{image.title ?? 'Spa ambience'}</figcaption>
            </figure>
          ))}
        </div>
      </div>
    </section>
  );
}

function Contact({ tenant }) {
  const whatsapp = whatsappUrl(tenant.whatsapp_number, `Hello, I would like to book at ${tenant.business_name}.`);
  const socials = [
    ['Facebook', tenant.facebook_url],
    ['Instagram', tenant.instagram_url],
    ['Snapchat', tenant.snapchat_url],
    ['YouTube', tenant.youtube_url],
    ['TikTok', tenant.tiktok_url],
    ['X', tenant.x_url],
  ].filter(([, url]) => url);

  return (
    <section className="ft-section ft-contact-section" id="contact">
      <div className="ft-shell ft-contact">
        <div>
          <p className="ft-kicker">Contact us</p>
          <h2>Ready for your next appointment?</h2>
          <p>{tenant.footer_content || `Contact ${tenant.business_name} for bookings, offers, and private spa requests.`}</p>
        </div>
        <aside>
          <div className="ft-contact-list">
            {tenant.phone && <a href={`tel:${tenant.phone}`}><Phone size={18} /> {tenant.phone}</a>}
            {whatsapp && <a href={whatsapp} target="_blank" rel="noreferrer"><MessageCircle size={18} /> WhatsApp booking</a>}
            {tenant.address && <span><MapPin size={18} /> {tenant.address}</span>}
            {tenant.google_maps_url && <a href={tenant.google_maps_url} target="_blank" rel="noreferrer"><MapPin size={18} /> Open Google Maps</a>}
          </div>
          <div className="ft-socials">
            {socials.map(([label, url]) => <a href={url} target="_blank" rel="noreferrer" key={label}><Star size={16} /> {label}</a>)}
            {!socials.length && <span>Social links will appear here after they are added in settings.</span>}
          </div>
        </aside>
      </div>
    </section>
  );
}

export default function FourthTemplate({ tenant, services = [], packages = [], staff = [], gallery = [], booking_endpoint, action_endpoint }) {
  useTenantActionTracking(action_endpoint);
  const { flash = {} } = usePage().props;
  const [booking, setBooking] = useState(null);
  const [notice, setNotice] = useState(flash.success ?? '');

  useEffect(() => {
    if (flash.success) setNotice(flash.success);
  }, [flash.success]);

  useEffect(() => {
    if (!notice) return undefined;
    const timeout = window.setTimeout(() => setNotice(''), 5200);
    return () => window.clearTimeout(timeout);
  }, [notice]);

  return (
    <div className="fourth-template-site">
      <Head title={tenant.business_name}>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="" />
        <link href="https://fonts.googleapis.com/css2?family=PT+Serif:wght@700&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
        <link rel="icon" href={defaultLogo} type="image/png" />
        <link rel="stylesheet" href={`${templateBase}/css/spaio-fourth.css`} />
      </Head>

      <Header tenant={tenant} />
      <main>
        <Hero tenant={tenant} services={services} onBook={setBooking} />
        <PromoBanners packages={packages} onBook={setBooking} />
        <About tenant={tenant} />
        <Services tenant={tenant} services={services} onBook={setBooking} />
        <Offers tenant={tenant} packages={packages} onBook={setBooking} />
        <Staff staff={staff} />
        <Gallery gallery={gallery} tenant={tenant} />
        <Contact tenant={tenant} />
      </main>
      <footer className="ft-footer">
        <div className="ft-shell">
          <strong>{tenant.business_name}</strong>
          <span>{tenant.footer_content || `Copyright ${new Date().getFullYear()} ${tenant.business_name}. All rights reserved.`}</span>
        </div>
      </footer>

      {notice && (
        <div className="ft-alert" role="alert" aria-live="polite">
          <CheckCircle2 size={20} />
          <span>{notice}</span>
          <button type="button" aria-label="Dismiss alert" onClick={() => setNotice('')}><X size={16} /></button>
        </div>
      )}

      <BookingModal booking={booking} endpoint={booking_endpoint} currency={tenant.currency_code} onClose={() => setBooking(null)} onBooked={setNotice} />
    </div>
  );
}
