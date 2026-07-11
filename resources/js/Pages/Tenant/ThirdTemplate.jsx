import { Head, useForm, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { useTenantActionTracking } from '../../lib/tenantActionTracking';
import {
  Activity,
  CalendarCheck,
  CheckCircle2,
  Clock3,
  HeartPulse,
  MapPin,
  Menu,
  MessageCircle,
  Phone,
  Send,
  ShieldCheck,
  Sparkles,
  Star,
  X,
} from 'lucide-react';

const templateBase = '/template-kits/third-template';
const defaultLogo = '/default.png';

const fallbackImages = {
  hero: `${templateBase}/assets/images/bg/bg-image-1.jpg`,
  heroPortrait: `${templateBase}/assets/images/heroslider/heroslider-image-1.jpg`,
  about: `${templateBase}/assets/images/others/about-image-1.jpg`,
  service: `${templateBase}/assets/images/others/service-details.jpg`,
  package: `${templateBase}/assets/images/bg/pricing-bg.jpg`,
  staff: `${templateBase}/assets/images/team/team-member-1.jpg`,
  gallery: `${templateBase}/assets/images/others/mission-image-1.jpg`,
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
    <header className="tt-header">
      <div className="tt-topbar">
        <div className="tt-shell tt-topbar__inner">
          <div>
            {tenant.phone && <a href={`tel:${tenant.phone}`}><Phone size={14} /> {tenant.phone}</a>}
            {tenant.address && <span><MapPin size={14} /> {tenant.address}</span>}
          </div>
          <a href="#services" className="tt-topbar__cta">Book an Appointment</a>
        </div>
      </div>

      <nav className="tt-shell tt-nav" aria-label="Primary navigation">
        <a className="tt-brand" href="#home" onClick={() => setOpen(false)}>
          <img src={logo} alt={`${tenant.business_name} logo`} />
          <span>
            <strong>{tenant.business_name}</strong>
            <small>Clinical wellness spa</small>
          </span>
        </a>

        <button className="tt-menu-button" type="button" aria-label="Open menu" aria-expanded={open} onClick={() => setOpen((value) => !value)}>
          <Menu size={22} />
        </button>

        <div className={`tt-nav-links ${open ? 'is-open' : ''}`}>
          {links.map(([label, href]) => (
            <a key={label} href={href} onClick={() => setOpen(false)}>{label}</a>
          ))}
          <a className="tt-nav-button" href="#services" onClick={() => setOpen(false)}>Book now</a>
        </div>
      </nav>
    </header>
  );
}

function SectionTitle({ eyebrow, title, text, centered = true }) {
  return (
    <div className={`tt-section-title ${centered ? 'tt-section-title--center' : ''}`}>
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
    <div className="tt-card-actions">
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
    <div className="tt-booking" role="dialog" aria-modal="true" aria-labelledby="tt-booking-title">
      <button className="tt-booking__backdrop" type="button" aria-label="Close booking form" onClick={onClose} />
      <form className="tt-booking__panel" onSubmit={submit}>
        <button className="tt-booking__close" type="button" aria-label="Close booking form" onClick={onClose}>
          <X size={18} />
        </button>
        <p className="tt-kicker">Appointment request</p>
        <h2 id="tt-booking-title">Book {booking.name}</h2>
        <strong>{money(booking.price, currency)}</strong>

        <div className="tt-booking__fields">
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
          <div className="tt-booking__row">
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
            <textarea value={form.data.notes} onChange={(event) => form.setData('notes', event.target.value)} placeholder="Preferred therapist, pressure level, or special request" />
            {form.errors.notes && <small>{form.errors.notes}</small>}
          </label>
        </div>

        <button className="tt-button tt-button--primary tt-booking__submit" type="submit" disabled={form.processing}>
          <Send size={16} />
          {form.processing ? 'Sending...' : 'Send booking request'}
        </button>
      </form>
    </div>
  );
}

function Hero({ tenant, services, packages, onBook }) {
  const hero = uploadUrl(tenant.hero_image_path) ?? fallbackImages.hero;
  const heroPortrait = uploadUrl(services[0]?.image_path) ?? fallbackImages.heroPortrait;
  const firstService = services[0];

  return (
    <section className="tt-hero" id="home" style={{ '--tt-hero-bg': `url("${hero}")` }}>
      <div className="tt-shell tt-hero__grid">
        <div className="tt-hero__content">
          <p className="tt-kicker">Registered wellness care</p>
          <h1>{tenant.hero_title || `Focused spa therapy at ${tenant.business_name}`}</h1>
          <p>{tenant.hero_subtitle || 'Book restorative massage, hammam, beauty, and wellness treatments with a calm clinical spa team.'}</p>
          <div className="tt-hero__actions">
            <a className="tt-button tt-button--primary" href="#services"><CalendarCheck size={18} /> Book a treatment</a>
            {firstService && (
              <button className="tt-button tt-button--secondary" type="button" onClick={() => onBook({ type: 'service', id: firstService.id, name: firstService.name, price: firstService.price })}>
                Book {firstService.name}
              </button>
            )}
          </div>
          <div className="tt-hero__stats">
            <span><strong>{services.length}</strong> treatments</span>
            <span><strong>{packages.length}</strong> offers</span>
            <span><strong>{tenant.currency_code ?? 'USD'}</strong> currency</span>
          </div>
        </div>

        <aside className="tt-hero__visual">
          <img src={heroPortrait} alt={`${tenant.business_name} treatment`} />
          <div className="tt-hero-card">
            <span><Activity size={18} /> Today</span>
            <strong>Open by appointment</strong>
            <p>Online requests are sent directly to the spa dashboard.</p>
          </div>
        </aside>
      </div>
    </section>
  );
}

function About({ tenant }) {
  return (
    <section className="tt-section tt-about" id="about">
      <div className="tt-shell tt-about__grid">
        <div className="tt-about__image">
          <img src={uploadUrl(tenant.hero_image_path) ?? fallbackImages.about} alt={`${tenant.business_name} interior`} />
        </div>
        <div className="tt-about__content">
          <SectionTitle centered={false} eyebrow="About the spa" title={`Positive effects of ${tenant.business_name}`} text={tenant.about_content || `${tenant.business_name} combines expert therapists, elegant rooms, and thoughtful spa rituals for customers who want measurable comfort and a polished experience.`} />
          <div className="tt-feature-strip">
            <span><HeartPulse size={20} /> Therapeutic care</span>
            <span><ShieldCheck size={20} /> Professional standards</span>
            <span><Clock3 size={20} /> Easy booking</span>
          </div>
        </div>
      </div>
    </section>
  );
}

function Services({ tenant, services, onBook }) {
  return (
    <section className="tt-section" id="services">
      <div className="tt-shell">
        <SectionTitle eyebrow="Our services" title="Treatments with a clean clinical touch" text="Every service is managed from the tenant dashboard and can be booked from the public website." />
        <div className="tt-service-grid">
          {services.map((service, index) => (
            <article className="tt-service-card" key={service.id}>
              <div className="tt-service-card__icon">{index % 2 ? <Sparkles size={28} /> : <HeartPulse size={28} />}</div>
              <img src={uploadUrl(service.image_path) ?? fallbackImages.service} alt={service.name} />
              <div>
                <h3>{service.name}</h3>
                <p>{service.description}</p>
                <strong>{money(service.price, tenant.currency_code)}</strong>
                <button className="tt-button tt-button--primary" type="button" onClick={() => onBook({ type: 'service', id: service.id, name: service.name, price: service.price })}>
                  <CalendarCheck size={16} /> Book Now
                </button>
                <CardActions tenant={tenant} label={service.name} />
              </div>
            </article>
          ))}
        </div>
        {!services.length && <p className="tt-empty">Services will appear here after they are published.</p>}
      </div>
    </section>
  );
}

function Offers({ tenant, packages, onBook }) {
  return (
    <section className="tt-section tt-section--blue" id="offers">
      <div className="tt-shell">
        <SectionTitle eyebrow="Offers and packages" title="Spa programs for complete recovery" text="Package cards use the same prices, images, and booking actions as every Spaio template." />
        <div className="tt-package-grid">
          {packages.map((pkg) => (
            <article className="tt-package-card" key={pkg.id}>
              <img src={uploadUrl(pkg.image_path) ?? fallbackImages.package} alt={pkg.name} />
              <div>
                <p className="tt-kicker">Wellness package</p>
                <h3>{pkg.name}</h3>
                <ul>
                  {String(pkg.included_description ?? '').split(/\r?\n/).filter(Boolean).map((item) => (
                    <li key={item}><CheckCircle2 size={16} /> {item}</li>
                  ))}
                </ul>
                <div className="tt-price-row">
                  <span>{money(pkg.original_price, tenant.currency_code)}</span>
                  <strong>{money(pkg.discounted_price, tenant.currency_code)}</strong>
                </div>
                <button className="tt-button tt-button--primary" type="button" onClick={() => onBook({ type: 'package', id: pkg.id, name: pkg.name, price: pkg.discounted_price })}>
                  <CalendarCheck size={16} /> Book Now
                </button>
                <CardActions tenant={tenant} label={pkg.name} />
              </div>
            </article>
          ))}
        </div>
        {!packages.length && <p className="tt-empty">Offers will appear here after they are published.</p>}
      </div>
    </section>
  );
}

function Staff({ staff }) {
  return (
    <section className="tt-section" id="team">
      <div className="tt-shell">
        <SectionTitle eyebrow="Expert team" title="Therapists who guide every visit" text="Staff profiles come from the tenant dashboard and stay consistent across templates." />
        <div className="tt-staff-grid">
          {staff.map((member, index) => (
            <article className="tt-staff-card" key={member.id}>
              <img src={uploadUrl(member.photo_path) ?? `${templateBase}/assets/images/team/team-member-${(index % 6) + 1}.jpg`} alt={member.name} />
              <div>
                <h3>{member.name}</h3>
                {member.role && <span>{member.role}</span>}
                {member.bio && <p>{member.bio}</p>}
              </div>
            </article>
          ))}
        </div>
        {!staff.length && <p className="tt-empty">Staff profiles will appear here after they are published.</p>}
      </div>
    </section>
  );
}

function Gallery({ gallery, tenant }) {
  const items = gallery.length ? gallery : [{ id: 'fallback', title: tenant.business_name, image_path: null }];

  return (
    <section className="tt-section tt-section--soft" id="gallery">
      <div className="tt-shell">
        <SectionTitle eyebrow="Gallery" title={`Inside ${tenant.business_name}`} text="Show treatment rooms, wellness details, and the spa atmosphere." />
        <div className="tt-gallery-grid">
          {items.map((image, index) => (
            <figure key={image.id} className={index === 0 || index === 5 ? 'is-wide' : ''}>
              <img src={uploadUrl(image.image_path) ?? fallbackImages.gallery} alt={image.alt_text ?? image.title ?? 'Spa gallery'} />
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
    <section className="tt-section tt-contact-section" id="contact">
      <div className="tt-shell tt-contact">
        <div>
          <p className="tt-kicker">Contact us</p>
          <h2>Plan your next wellness appointment.</h2>
          <p>{tenant.footer_content || `Contact ${tenant.business_name} for appointments, offers, and private treatment requests.`}</p>
        </div>
        <aside>
          <div className="tt-contact-list">
            {tenant.phone && <a href={`tel:${tenant.phone}`}><Phone size={18} /> {tenant.phone}</a>}
            {whatsapp && <a href={whatsapp} target="_blank" rel="noreferrer"><MessageCircle size={18} /> WhatsApp booking</a>}
            {tenant.address && <span><MapPin size={18} /> {tenant.address}</span>}
            {tenant.google_maps_url && <a href={tenant.google_maps_url} target="_blank" rel="noreferrer"><MapPin size={18} /> Open Google Maps</a>}
          </div>
          <div className="tt-socials">
            {socials.map(([label, url]) => <a href={url} target="_blank" rel="noreferrer" key={label}><Star size={16} /> {label}</a>)}
            {!socials.length && <span>Social links will appear here after they are added in settings.</span>}
          </div>
        </aside>
      </div>
    </section>
  );
}

export default function ThirdTemplate({ tenant, services = [], packages = [], staff = [], gallery = [], booking_endpoint, action_endpoint }) {
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
    <div className="third-template-site">
      <Head title={tenant.business_name}>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="" />
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet" />
        <link rel="icon" href={defaultLogo} type="image/png" />
        <link rel="stylesheet" href={`${templateBase}/assets/css/spaio-third.css`} />
      </Head>

      <Header tenant={tenant} />
      <main>
        <Hero tenant={tenant} services={services} packages={packages} onBook={setBooking} />
        <About tenant={tenant} />
        <Services tenant={tenant} services={services} onBook={setBooking} />
        <Offers tenant={tenant} packages={packages} onBook={setBooking} />
        <Staff staff={staff} />
        <Gallery gallery={gallery} tenant={tenant} />
        <Contact tenant={tenant} />
      </main>
      <footer className="tt-footer">
        <div className="tt-shell">
          <strong>{tenant.business_name}</strong>
          <span>{tenant.footer_content || `Copyright ${new Date().getFullYear()} ${tenant.business_name}. All rights reserved.`}</span>
        </div>
      </footer>

      {notice && (
        <div className="tt-alert" role="alert" aria-live="polite">
          <CheckCircle2 size={20} />
          <span>{notice}</span>
          <button type="button" aria-label="Dismiss alert" onClick={() => setNotice('')}><X size={16} /></button>
        </div>
      )}

      <BookingModal booking={booking} endpoint={booking_endpoint} currency={tenant.currency_code} onClose={() => setBooking(null)} onBooked={setNotice} />
    </div>
  );
}
