import { Head, useForm, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { useTenantActionTracking } from '../../lib/tenantActionTracking';
import {
  CalendarCheck,
  CheckCircle2,
  Clock3,
  MapPin,
  Menu,
  MessageCircle,
  Phone,
  Send,
  Sparkles,
  Star,
  Users,
  X,
} from 'lucide-react';

const templateBase = '/template-kits/second-template';
const defaultLogo = '/default.png';
const fallbackImages = {
  hero: 'https://images.unsplash.com/photo-1600334129128-685c5582fd35?auto=format&fit=crop&w=2200&q=88',
  service: 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&w=1200&q=88',
  package: 'https://images.unsplash.com/photo-1515377905703-c4788e51af15?auto=format&fit=crop&w=1200&q=88',
  staff: 'https://images.unsplash.com/photo-1580618672591-eb180b1a973f?auto=format&fit=crop&w=900&q=88',
  gallery: 'https://images.unsplash.com/photo-1519823551278-64ac92734fb1?auto=format&fit=crop&w=1200&q=88',
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
    ['Offers', '#offers'],
    ['Services', '#services'],
    ['Team', '#team'],
    ['Gallery', '#gallery'],
    ['Contact', '#contact'],
  ];

  return (
    <header className="st-header">
      <div className="st-topbar">
        <div className="st-shell st-topbar__inner">
          <div className="st-topbar__links">
            {tenant.phone && <a href={`tel:${tenant.phone}`}><Phone size={14} /> {tenant.phone}</a>}
            {tenant.address && <span><MapPin size={14} /> {tenant.address}</span>}
          </div>
          <div className="st-topbar__links st-topbar__links--right">
            <span>{tenant.currency_code ?? 'USD'}</span>
            {tenant.youtube_url && <a href={tenant.youtube_url} target="_blank" rel="noreferrer">YouTube</a>}
            {tenant.instagram_url && <a href={tenant.instagram_url} target="_blank" rel="noreferrer">Instagram</a>}
          </div>
        </div>
      </div>

      <nav className="st-shell st-nav" aria-label="Primary navigation">
        <a className="st-brand" href="#home" onClick={() => setOpen(false)}>
          <img src={logo} alt={`${tenant.business_name} logo`} />
          <span>
            <strong>{tenant.business_name}</strong>
            <small>Wellness spa</small>
          </span>
        </a>

        <button className="st-menu-button" type="button" aria-label="Open menu" aria-expanded={open} onClick={() => setOpen((value) => !value)}>
          <Menu size={22} />
        </button>

        <div className={`st-nav-links ${open ? 'is-open' : ''}`}>
          {links.map(([label, href]) => (
            <a key={label} href={href} onClick={() => setOpen(false)}>{label}</a>
          ))}
          <a className="st-nav-cta" href="#services" onClick={() => setOpen(false)}>Book now</a>
        </div>
      </nav>
    </header>
  );
}

function SectionTitle({ eyebrow, title, text, light = false }) {
  return (
    <div className={`st-section-title ${light ? 'st-section-title--light' : ''}`}>
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
    <div className="st-card-actions">
      {tenant.show_whatsapp_button && whatsapp && (
        <a href={whatsapp} target="_blank" rel="noreferrer"><MessageCircle size={16} /> WhatsApp</a>
      )}
      {tenant.show_call_button && tenant.phone && (
        <a href={`tel:${tenant.phone}`}><Phone size={16} /> Call</a>
      )}
      {tenant.show_location_button && tenant.google_maps_url && (
        <a href={tenant.google_maps_url} target="_blank" rel="noreferrer"><MapPin size={16} /> Location</a>
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
    <div className="st-booking" role="dialog" aria-modal="true" aria-labelledby="st-booking-title">
      <button className="st-booking__backdrop" type="button" aria-label="Close booking form" onClick={onClose} />
      <form className="st-booking__panel" onSubmit={submit}>
        <button className="st-booking__close" type="button" aria-label="Close booking form" onClick={onClose}>
          <X size={18} />
        </button>
        <p className="st-kicker">Reservation request</p>
        <h2 id="st-booking-title">Book {booking.name}</h2>
        <strong>{money(booking.price, currency)}</strong>

        <div className="st-booking__fields">
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
          <div className="st-booking__row">
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
            <textarea value={form.data.notes} onChange={(event) => form.setData('notes', event.target.value)} placeholder="Guest count, preferred therapist, or special request" />
            {form.errors.notes && <small>{form.errors.notes}</small>}
          </label>
        </div>

        <button className="st-button st-button--dark st-booking__submit" type="submit" disabled={form.processing}>
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
    <section className="st-hero" id="home">
      <div className="st-hero__image">
        <img src={hero} alt={`${tenant.business_name} ambience`} />
      </div>
      <div className="st-shell st-hero__content">
        <div>
          <p className="st-kicker">Luxury wellness studio</p>
          <h1>{tenant.hero_title || `A refined spa ritual at ${tenant.business_name}`}</h1>
          <p>{tenant.hero_subtitle || 'Reserve premium massage, hammam, beauty, and wellness experiences in a calm private space.'}</p>
          <div className="st-hero__actions">
            <a className="st-button st-button--dark" href="#services"><CalendarCheck size={18} /> Explore bookings</a>
            {firstService && (
              <button className="st-button st-button--light" type="button" onClick={() => onBook({ type: 'service', id: firstService.id, name: firstService.name, price: firstService.price })}>
                Book featured service
              </button>
            )}
          </div>
        </div>
        <aside className="st-hero-card">
          <span>Today at {tenant.business_name}</span>
          <strong>{services.length || 0}</strong>
          <p>Signature treatments ready to book</p>
          <div>
            <Clock3 size={18} />
            <span>Open daily by appointment</span>
          </div>
        </aside>
      </div>
    </section>
  );
}

function Offers({ tenant, packages, onBook }) {
  return (
    <section className="st-section st-section--cream" id="offers">
      <div className="st-shell">
        <SectionTitle eyebrow="Exclusive offers" title="Packages for complete spa escapes" text="Every offer keeps the same content and booking behavior across templates." />
        <div className="st-offers">
          {packages.map((pkg) => (
            <article className="st-offer-card" key={pkg.id}>
              <img src={uploadUrl(pkg.image_path) ?? fallbackImages.package} alt={pkg.name} />
              <div>
                <p className="st-kicker">Featured package</p>
                <h3>{pkg.name}</h3>
                <ul>
                  {String(pkg.included_description).split(/\r?\n/).filter(Boolean).map((item) => (
                    <li key={item}><CheckCircle2 size={16} /> {item}</li>
                  ))}
                </ul>
                <div className="st-price-row">
                  <span>{money(pkg.original_price, tenant.currency_code)}</span>
                  <strong>{money(pkg.discounted_price, tenant.currency_code)}</strong>
                </div>
                <button className="st-button st-button--dark" type="button" onClick={() => onBook({ type: 'package', id: pkg.id, name: pkg.name, price: pkg.discounted_price })}>
                  <CalendarCheck size={16} /> Book Now
                </button>
                <CardActions tenant={tenant} label={pkg.name} />
              </div>
            </article>
          ))}
        </div>
        {!packages.length && <p className="st-empty">Offers will appear here after they are published.</p>}
      </div>
    </section>
  );
}

function Services({ tenant, services, onBook }) {
  return (
    <section className="st-section" id="services">
      <div className="st-shell">
        <SectionTitle eyebrow="Treatment menu" title="Services designed for relaxation and glow" text="Prices, descriptions, images, and booking actions come directly from the tenant dashboard." />
        <div className="st-service-grid">
          {services.map((service) => (
            <article className="st-service-card" key={service.id}>
              <img src={uploadUrl(service.image_path) ?? fallbackImages.service} alt={service.name} />
              <div>
                <span><Sparkles size={15} /> Signature</span>
                <h3>{service.name}</h3>
                <p>{service.description}</p>
                <strong>{money(service.price, tenant.currency_code)}</strong>
                <button className="st-button st-button--dark" type="button" onClick={() => onBook({ type: 'service', id: service.id, name: service.name, price: service.price })}>
                  <CalendarCheck size={16} /> Book Now
                </button>
                <CardActions tenant={tenant} label={service.name} />
              </div>
            </article>
          ))}
        </div>
        {!services.length && <p className="st-empty">Services will appear here after they are published.</p>}
      </div>
    </section>
  );
}

function Staff({ staff }) {
  return (
    <section className="st-section st-section--green" id="team">
      <div className="st-shell">
        <SectionTitle light eyebrow="Therapists" title="A calm, experienced wellness team" text="Staff cards reuse the same tenant team data across all templates." />
        <div className="st-staff-grid">
          {staff.map((member) => (
            <article className="st-staff-card" key={member.id}>
              <img src={uploadUrl(member.photo_path) ?? fallbackImages.staff} alt={member.name} />
              <div>
                <h3>{member.name}</h3>
                {member.role && <span>{member.role}</span>}
                {member.bio && <p>{member.bio}</p>}
              </div>
            </article>
          ))}
        </div>
        {!staff.length && <p className="st-empty st-empty--light">Staff profiles will appear here after they are published.</p>}
      </div>
    </section>
  );
}

function Gallery({ gallery, tenant }) {
  const items = gallery.length ? gallery : [{ id: 'fallback', title: tenant.business_name, image_path: null }];

  return (
    <section className="st-section" id="gallery">
      <div className="st-shell">
        <SectionTitle eyebrow="Gallery" title={`Inside ${tenant.business_name}`} text="Treatment rooms, spa atmosphere, and gallery photos managed from the tenant dashboard." />
        <div className="st-gallery-grid">
          {items.map((image, index) => (
            <figure className={index === 0 ? 'is-large' : ''} key={image.id}>
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
    ['Facebook', tenant.facebook_url, Star],
    ['Instagram', tenant.instagram_url, Star],
    ['Snapchat', tenant.snapchat_url, Star],
    ['YouTube', tenant.youtube_url, Star],
    ['TikTok', tenant.tiktok_url, Star],
    ['X', tenant.x_url, Star],
  ].filter(([, url]) => url);

  return (
    <section className="st-section st-section--cream" id="contact">
      <div className="st-shell st-contact">
        <div>
          <p className="st-kicker">Contact and location</p>
          <h2>Ready to plan your spa visit?</h2>
          <p>{tenant.about_content || `${tenant.business_name} brings together premium treatments, thoughtful service, and a refined wellness atmosphere.`}</p>
          <div className="st-contact-list">
            {tenant.phone && <a href={`tel:${tenant.phone}`}><Phone size={18} /> {tenant.phone}</a>}
            {whatsapp && <a href={whatsapp} target="_blank" rel="noreferrer"><MessageCircle size={18} /> WhatsApp booking</a>}
            {tenant.address && <span><MapPin size={18} /> {tenant.address}</span>}
            {tenant.google_maps_url && <a href={tenant.google_maps_url} target="_blank" rel="noreferrer"><MapPin size={18} /> Open Google Maps</a>}
          </div>
        </div>
        <aside>
          <h3>Follow the spa</h3>
          <p>Social buttons use the same tenant settings as every other template.</p>
          <div className="st-socials">
            {socials.map(([label, url, Icon]) => (
              <a href={url} target="_blank" rel="noreferrer" key={label}><Icon size={18} /> {label}</a>
            ))}
            {!socials.length && <span>Social links will appear here after they are added in settings.</span>}
          </div>
        </aside>
      </div>
    </section>
  );
}

export default function SecondTemplate({ tenant, services = [], packages = [], staff = [], gallery = [], booking_endpoint, action_endpoint }) {
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
    <div className="second-template-site">
      <Head title={tenant.business_name}>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="" />
        <link href="https://fonts.googleapis.com/css2?family=Bilbo+Swash+Caps&family=Philosopher:wght@400;700&family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
        <link rel="icon" href={defaultLogo} type="image/png" />
        <link rel="stylesheet" href={`${templateBase}/css/spaio-second.css`} />
      </Head>

      <Header tenant={tenant} />
      <main>
        <Hero tenant={tenant} services={services} onBook={setBooking} />
        <Offers tenant={tenant} packages={packages} onBook={setBooking} />
        <Services tenant={tenant} services={services} onBook={setBooking} />
        <Staff staff={staff} />
        <Gallery gallery={gallery} tenant={tenant} />
        <Contact tenant={tenant} />
      </main>
      <footer className="st-footer">
        <div className="st-shell">
          <strong>{tenant.business_name}</strong>
          <span>{tenant.footer_content || `Copyright ${new Date().getFullYear()} ${tenant.business_name}. All rights reserved.`}</span>
        </div>
      </footer>

      {notice && (
        <div className="st-alert" role="alert" aria-live="polite">
          <CheckCircle2 size={20} />
          <span>{notice}</span>
          <button type="button" aria-label="Dismiss alert" onClick={() => setNotice('')}><X size={16} /></button>
        </div>
      )}

      <BookingModal booking={booking} endpoint={booking_endpoint} currency={tenant.currency_code} onClose={() => setBooking(null)} onBooked={setNotice} />
    </div>
  );
}
