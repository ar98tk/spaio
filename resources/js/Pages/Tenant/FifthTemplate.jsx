import { Head, useForm, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { useTenantActionTracking } from '../../lib/tenantActionTracking';
import {
  CalendarCheck,
  CheckCircle2,
  Clock3,
  Gem,
  Leaf,
  MapPin,
  Menu,
  MessageCircle,
  Phone,
  Send,
  Sparkles,
  Star,
  X,
} from 'lucide-react';

const templateBase = '/template-kits/fifth-template';
const defaultLogo = '/default.png';

const fallbackImages = {
  hero: `${templateBase}/images/1920x1100.jpg`,
  about: `${templateBase}/images/1170x700.jpg`,
  service: `${templateBase}/images/370x250.jpg`,
  package: `${templateBase}/images/770x434.jpg`,
  staff: `${templateBase}/images/391x500.jpg`,
  gallery: `${templateBase}/images/370x370.jpg`,
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
    ['Staff', '#staff'],
    ['Gallery', '#gallery'],
    ['Contact', '#contact'],
  ];

  return (
    <header className="fivt-header">
      <div className="fivt-topbar">
        <div className="fivt-shell fivt-topbar__inner">
          <div>
            {tenant.phone && <a href={`tel:${tenant.phone}`}><Phone size={14} /> {tenant.phone}</a>}
            {tenant.address && <span><MapPin size={14} /> {tenant.address}</span>}
          </div>
          <span><Clock3 size={14} /> Open by appointment</span>
        </div>
      </div>

      <nav className="fivt-shell fivt-nav" aria-label="Primary navigation">
        <button className="fivt-menu-button" type="button" aria-label="Open menu" aria-expanded={open} onClick={() => setOpen((value) => !value)}>
          <Menu size={22} />
        </button>

        <a className="fivt-brand" href="#home" onClick={() => setOpen(false)}>
          <img src={logo} alt={`${tenant.business_name} logo`} />
          <span>{tenant.business_name}</span>
        </a>

        <div className={`fivt-nav-links ${open ? 'is-open' : ''}`}>
          {links.map(([label, href]) => (
            <a key={label} href={href} onClick={() => setOpen(false)}>{label}</a>
          ))}
          <a className="fivt-nav-cta" href="#services" onClick={() => setOpen(false)}>Booking</a>
        </div>
      </nav>
    </header>
  );
}

function SectionTitle({ eyebrow, title, text, light = false }) {
  return (
    <div className={`fivt-section-title ${light ? 'fivt-section-title--light' : ''}`}>
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
    <div className="fivt-card-actions">
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
    <div className="fivt-booking" role="dialog" aria-modal="true" aria-labelledby="fivt-booking-title">
      <button className="fivt-booking__backdrop" type="button" aria-label="Close booking form" onClick={onClose} />
      <form className="fivt-booking__panel" onSubmit={submit}>
        <button className="fivt-booking__close" type="button" aria-label="Close booking form" onClick={onClose}>
          <X size={18} />
        </button>
        <p className="fivt-kicker">Online booking</p>
        <h2 id="fivt-booking-title">Book {booking.name}</h2>
        <strong>{money(booking.price, currency)}</strong>

        <div className="fivt-booking__fields">
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
          <div className="fivt-booking__row">
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
            <textarea value={form.data.notes} onChange={(event) => form.setData('notes', event.target.value)} placeholder="Preferred massage style, therapist, or guest notes" />
            {form.errors.notes && <small>{form.errors.notes}</small>}
          </label>
        </div>

        <button className="fivt-button fivt-button--filled fivt-booking__submit" type="submit" disabled={form.processing}>
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
    <section className="fivt-hero" id="home">
      <img src={hero} alt={`${tenant.business_name} spa atmosphere`} />
      <div className="fivt-hero__overlay" />
      <div className="fivt-shell fivt-hero__content">
        <p className="fivt-kicker">Massage therapy center</p>
        <h1>{tenant.hero_title || `Perfect mind-body harmony at ${tenant.business_name}`}</h1>
        <p>{tenant.hero_subtitle || 'Customized treatments, thoughtful therapists, and online booking for every service and package.'}</p>
        <div className="fivt-hero__actions">
          <a className="fivt-button fivt-button--filled" href="#services"><CalendarCheck size={18} /> Book Online</a>
          {firstService && (
            <button className="fivt-button fivt-button--ghost" type="button" onClick={() => onBook({ type: 'service', id: firstService.id, name: firstService.name, price: firstService.price })}>
              Book Featured Service
            </button>
          )}
        </div>
      </div>
    </section>
  );
}

function About({ tenant }) {
  return (
    <section className="fivt-section fivt-about" id="about">
      <div className="fivt-shell fivt-about__grid">
        <div className="fivt-about__content">
          <SectionTitle eyebrow="About us" title="A calm massage center built around individual needs" text={tenant.about_content || `${tenant.business_name} creates personalized spa and massage experiences with trained therapists, premium rooms, and simple online booking.`} />
          <div className="fivt-about__metrics">
            <span><strong>01</strong> Customized treatments</span>
            <span><strong>02</strong> Therapist-led care</span>
            <span><strong>03</strong> Dashboard-tracked bookings</span>
          </div>
        </div>
        <div className="fivt-about__image">
          <img src={uploadUrl(tenant.hero_image_path) ?? fallbackImages.about} alt={`${tenant.business_name} treatment room`} />
        </div>
      </div>
    </section>
  );
}

function Services({ tenant, services, onBook }) {
  return (
    <section className="fivt-section fivt-services" id="services">
      <div className="fivt-shell">
        <SectionTitle eyebrow="Our services" title="Therapy and relaxation services" text="Services, pricing, photos, and booking buttons come directly from the tenant dashboard." />
        <div className="fivt-service-grid">
          {services.map((service, index) => (
            <article className="fivt-service-card" key={service.id}>
              <span className="fivt-service-card__icon">{index % 3 === 0 ? <Leaf size={28} /> : index % 3 === 1 ? <Sparkles size={28} /> : <Gem size={28} />}</span>
              <img src={uploadUrl(service.image_path) ?? fallbackImages.service} alt={service.name} />
              <div>
                <h3>{service.name}</h3>
                <p>{service.description}</p>
                <strong>{money(service.price, tenant.currency_code)}</strong>
                <button className="fivt-button fivt-button--filled" type="button" onClick={() => onBook({ type: 'service', id: service.id, name: service.name, price: service.price })}>
                  <CalendarCheck size={16} /> Book Now
                </button>
                <CardActions tenant={tenant} label={service.name} />
              </div>
            </article>
          ))}
        </div>
        {!services.length && <p className="fivt-empty">Services will appear here after they are published.</p>}
      </div>
    </section>
  );
}

function Offers({ tenant, packages, onBook }) {
  return (
    <section className="fivt-section fivt-offers" id="offers">
      <div className="fivt-shell fivt-offers__grid">
        <div>
          <SectionTitle light eyebrow="Services & pricing" title="Packages for deeper restoration" text="Promotional offers keep tenant content intact while adopting this template's editorial price-list layout." />
          <div className="fivt-package-list">
            {packages.map((pkg) => (
              <article className="fivt-package-row" key={pkg.id}>
                <img src={uploadUrl(pkg.image_path) ?? fallbackImages.package} alt={pkg.name} />
                <div>
                  <h3>{pkg.name}</h3>
                  <ul>
                    {String(pkg.included_description ?? '').split(/\r?\n/).filter(Boolean).map((item) => (
                      <li key={item}><CheckCircle2 size={15} /> {item}</li>
                    ))}
                  </ul>
                </div>
                <aside>
                  <span>{money(pkg.original_price, tenant.currency_code)}</span>
                  <strong>{money(pkg.discounted_price, tenant.currency_code)}</strong>
                  <button type="button" onClick={() => onBook({ type: 'package', id: pkg.id, name: pkg.name, price: pkg.discounted_price })}>Book Online</button>
                </aside>
              </article>
            ))}
          </div>
          {!packages.length && <p className="fivt-empty fivt-empty--light">Offers will appear here after they are published.</p>}
        </div>
        <div className="fivt-offers__image">
          <img src={fallbackImages.about} alt="Massage pricing" />
        </div>
      </div>
    </section>
  );
}

function Staff({ staff }) {
  return (
    <section className="fivt-section" id="staff">
      <div className="fivt-shell">
        <SectionTitle eyebrow="Our staff" title="Therapists with a personal approach" text="The same staff profiles are shown across every tenant template." />
        <div className="fivt-staff-grid">
          {staff.map((member) => (
            <article className="fivt-staff-card" key={member.id}>
              <img src={uploadUrl(member.photo_path) ?? fallbackImages.staff} alt={member.name} />
              <div>
                <h3>{member.name}</h3>
                {member.role && <span>{member.role}</span>}
                {member.bio && <p>{member.bio}</p>}
              </div>
            </article>
          ))}
        </div>
        {!staff.length && <p className="fivt-empty">Staff profiles will appear here after they are published.</p>}
      </div>
    </section>
  );
}

function Gallery({ gallery, tenant }) {
  const items = gallery.length ? gallery : [{ id: 'fallback', title: tenant.business_name, image_path: null }];

  return (
    <section className="fivt-section fivt-gallery" id="gallery">
      <div className="fivt-shell">
        <SectionTitle eyebrow="Gallery" title={`Inside ${tenant.business_name}`} text="Gallery photos use tenant uploads and keep the same meaning across all templates." />
        <div className="fivt-gallery-grid">
          {items.map((image, index) => (
            <figure className={index === 1 || index === 4 ? 'is-wide' : ''} key={image.id}>
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
    <section className="fivt-section fivt-contact-section" id="contact">
      <div className="fivt-shell fivt-contact">
        <div>
          <p className="fivt-kicker">Contact us</p>
          <h2>Make an appointment today.</h2>
          <p>{tenant.footer_content || `Contact ${tenant.business_name} to reserve treatments, ask about offers, or plan a private spa visit.`}</p>
        </div>
        <aside>
          <div className="fivt-contact-list">
            {tenant.phone && <a href={`tel:${tenant.phone}`}><Phone size={18} /> {tenant.phone}</a>}
            {whatsapp && <a href={whatsapp} target="_blank" rel="noreferrer"><MessageCircle size={18} /> WhatsApp booking</a>}
            {tenant.address && <span><MapPin size={18} /> {tenant.address}</span>}
            {tenant.google_maps_url && <a href={tenant.google_maps_url} target="_blank" rel="noreferrer"><MapPin size={18} /> Open Google Maps</a>}
          </div>
          <div className="fivt-socials">
            {socials.map(([label, url]) => <a href={url} target="_blank" rel="noreferrer" key={label}><Star size={16} /> {label}</a>)}
            {!socials.length && <span>Social links will appear here after they are added in settings.</span>}
          </div>
        </aside>
      </div>
    </section>
  );
}

export default function FifthTemplate({ tenant, services = [], packages = [], staff = [], gallery = [], booking_endpoint, action_endpoint }) {
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
    <div className="fifth-template-site">
      <Head title={tenant.business_name}>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="" />
        <link href="https://fonts.googleapis.com/css2?family=Lora:wght@500;600;700&family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
        <link rel="icon" href={defaultLogo} type="image/png" />
        <link rel="stylesheet" href={`${templateBase}/css/spaio-fifth.css`} />
      </Head>

      <Header tenant={tenant} />
      <main>
        <Hero tenant={tenant} services={services} onBook={setBooking} />
        <About tenant={tenant} />
        <Services tenant={tenant} services={services} onBook={setBooking} />
        <Offers tenant={tenant} packages={packages} onBook={setBooking} />
        <Staff staff={staff} />
        <Gallery gallery={gallery} tenant={tenant} />
        <Contact tenant={tenant} />
      </main>
      <footer className="fivt-footer">
        <div className="fivt-shell">
          <strong>{tenant.business_name}</strong>
          <span>{tenant.footer_content || `Copyright ${new Date().getFullYear()} ${tenant.business_name}. All rights reserved.`}</span>
        </div>
      </footer>

      {notice && (
        <div className="fivt-alert" role="alert" aria-live="polite">
          <CheckCircle2 size={20} />
          <span>{notice}</span>
          <button type="button" aria-label="Dismiss alert" onClick={() => setNotice('')}><X size={16} /></button>
        </div>
      )}

      <BookingModal booking={booking} endpoint={booking_endpoint} currency={tenant.currency_code} onClose={() => setBooking(null)} onBooked={setNotice} />
    </div>
  );
}
