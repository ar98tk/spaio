import { Head, useForm, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { useTenantActionTracking } from '../../lib/tenantActionTracking';
import {
  CalendarCheck,
  CheckCircle2,
  ChevronRight,
  Clock3,
  Gift,
  Heart,
  Leaf,
  Mail,
  MapPin,
  Menu,
  MessageCircle,
  Phone,
  Search,
  Send,
  ShoppingBag,
  Sparkles,
  Star,
  X,
} from 'lucide-react';

const templateBase = '/template-kits/eleventh-template';
const defaultLogo = '/default.png';

const fallbacks = {
  hero: 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&w=1100&q=88',
  heroAlt: 'https://images.unsplash.com/photo-1519823551278-64ac92734fb1?auto=format&fit=crop&w=1100&q=88',
  about: `${templateBase}/images/contactus-img.jpg`,
  service: 'https://images.unsplash.com/photo-1600334129128-685c5582fd35?auto=format&fit=crop&w=700&q=88',
  package: 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=800&q=88',
  staff: 'https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=700&q=88',
  testimonial: `${templateBase}/images/testimonial-bg01.jpg`,
};

const galleryFallbacks = [
  'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=800&q=88',
  'https://images.unsplash.com/photo-1519823551278-64ac92734fb1?auto=format&fit=crop&w=800&q=88',
  'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&fit=crop&w=800&q=88',
  'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=800&q=88',
  'https://images.unsplash.com/photo-1600334129128-685c5582fd35?auto=format&fit=crop&w=800&q=88',
  'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&w=800&q=88',
];

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
  const socials = [
    ['Facebook', tenant.facebook_url],
    ['Instagram', tenant.instagram_url],
    ['Snapchat', tenant.snapchat_url],
    ['YouTube', tenant.youtube_url],
    ['TikTok', tenant.tiktok_url],
    ['X', tenant.x_url],
  ].filter(([, url]) => url);
  const links = [
    ['Home', '#home'],
    ['Services', '#services'],
    ['Offers', '#offers'],
    ['About', '#about'],
    ['Gallery', '#gallery'],
    ['Contact', '#contact'],
  ];

  return (
    <header className="elvt-header">
      <div className="elvt-topbar">
        <div className="elvt-container elvt-topbar__inner">
          <div className="elvt-topbar__left">
            {tenant.phone && <a href={`tel:${tenant.phone}`}><Phone size={14} /> {tenant.phone}</a>}
            {tenant.address && <span><MapPin size={14} /> {tenant.address}</span>}
          </div>
          <div className="elvt-topbar__right">
            <span><Clock3 size={14} /> Open by appointment</span>
            {socials.map(([label, url]) => (
              <a href={url} target="_blank" rel="noreferrer" key={label} aria-label={label}><Star size={13} /></a>
            ))}
          </div>
        </div>
      </div>

      <div className="elvt-navwrap">
        <div className="elvt-container elvt-nav">
          <a className="elvt-brand" href="#home" onClick={() => setOpen(false)}>
            <img src={logo} alt={`${tenant.business_name} logo`} />
            <span>{tenant.business_name}</span>
          </a>

          <button className="elvt-menu" type="button" aria-label="Toggle navigation" aria-expanded={open} onClick={() => setOpen((value) => !value)}>
            {open ? <X size={22} /> : <Menu size={22} />}
          </button>

          <nav className={`elvt-links ${open ? 'is-open' : ''}`} aria-label="Main navigation">
            {links.map(([label, href]) => (
              <a href={href} key={label} onClick={() => setOpen(false)}>{label}</a>
            ))}
          </nav>

          <div className="elvt-tools">
            <a href="#services" aria-label="Search services"><Search size={19} /></a>
            <a href="#offers" aria-label="View offers"><ShoppingBag size={19} /></a>
          </div>
        </div>
      </div>
    </header>
  );
}

function SectionHeading({ eyebrow, title, text, light = false }) {
  return (
    <div className={`elvt-heading ${light ? 'elvt-heading--light' : ''}`}>
      <img src={`${templateBase}/images/heading-icon.png`} alt="" />
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
    <div className="elvt-card-actions">
      {tenant.show_whatsapp_button && whatsapp && <a href={whatsapp} target="_blank" rel="noreferrer"><MessageCircle size={14} /> WhatsApp</a>}
      {tenant.show_call_button && tenant.phone && <a href={`tel:${tenant.phone}`}><Phone size={14} /> Call</a>}
      {tenant.show_location_button && tenant.google_maps_url && <a href={tenant.google_maps_url} target="_blank" rel="noreferrer"><MapPin size={14} /> Location</a>}
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
    <div className="elvt-booking" role="dialog" aria-modal="true" aria-labelledby="elvt-booking-title">
      <button className="elvt-booking__overlay" type="button" aria-label="Close booking form" onClick={onClose} />
      <form className="elvt-booking__panel" onSubmit={submit}>
        <button className="elvt-booking__close" type="button" aria-label="Close booking form" onClick={onClose}><X size={18} /></button>
        <p>Request an appointment</p>
        <h2 id="elvt-booking-title">{booking.name}</h2>
        <strong>{money(booking.price, currency)}</strong>

        <div className="elvt-booking__fields">
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
          <div className="elvt-booking__row">
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
            <textarea rows="3" value={form.data.notes} onChange={(event) => form.setData('notes', event.target.value)} />
            {form.errors.notes && <small>{form.errors.notes}</small>}
          </label>
        </div>

        <button className="elvt-btn elvt-btn--primary" type="submit" disabled={form.processing}>
          <Send size={16} /> {form.processing ? 'Sending...' : 'Send Booking Request'}
        </button>
      </form>
    </div>
  );
}

function Hero({ tenant, services, onBook }) {
  const featured = services[0];
  const heroImage = uploadUrl(tenant.hero_image_path) ?? fallbacks.hero;

  return (
    <section className="elvt-hero" id="home">
      <div className="elvt-container elvt-hero__grid">
        <figure className="elvt-hero__portrait">
          <img src={heroImage} alt={`${tenant.business_name} spa treatment`} />
          <figcaption>
            <span>Rest & Relax</span>
            <strong>{tenant.business_name}</strong>
          </figcaption>
        </figure>

        <div className="elvt-hero__copy">
          <p><Sparkles size={16} /> Beauty & Spa Salon</p>
          <h1>{tenant.hero_title || `Refresh and rejuvenate at ${tenant.business_name}`}</h1>
          <span>{tenant.hero_subtitle || 'Enjoy premium spa treatments, relaxing packages, and simple booking from one elegant website.'}</span>
          <div className="elvt-hero__actions">
            {featured && (
              <button className="elvt-btn elvt-btn--primary" type="button" onClick={() => onBook({ type: 'service', id: featured.id, name: featured.name, price: featured.price })}>
                Request an Appointment
              </button>
            )}
            <a className="elvt-btn elvt-btn--ghost" href="#services">More Information</a>
          </div>
        </div>
      </div>
    </section>
  );
}

function Services({ tenant, services, onBook }) {
  return (
    <section className="elvt-section" id="services">
      <div className="elvt-container">
        <SectionHeading eyebrow="Our services" title="Full-body care rituals" text="Circular service treatments inspired by the original Paradise spa theme, powered by tenant-managed content." />
        <div className="elvt-services-ring">
          {services.map((service, index) => (
            <article className="elvt-service-orb" key={service.id}>
              <figure>
                <img src={uploadUrl(service.image_path) ?? galleryFallbacks[index % galleryFallbacks.length] ?? fallbacks.service} alt={service.name} />
                <button type="button" onClick={() => onBook({ type: 'service', id: service.id, name: service.name, price: service.price })}>Book Now</button>
              </figure>
              <div>
                <h3>{service.name}</h3>
                <p>{service.description}</p>
                <strong>{money(service.price, tenant.currency_code)}</strong>
                <CardActions tenant={tenant} label={service.name} />
              </div>
            </article>
          ))}
        </div>
        {!services.length && <p className="elvt-empty">Services will appear here after they are published.</p>}
      </div>
    </section>
  );
}

function About({ tenant }) {
  return (
    <section className="elvt-why" id="about">
      <div className="elvt-container elvt-why__grid">
        <figure>
          <img src={uploadUrl(tenant.hero_image_path) ?? fallbacks.about} alt={`${tenant.business_name} spa space`} />
        </figure>
        <div>
          <SectionHeading eyebrow="Why choose us" title="Special treatments with calm attention" text={tenant.about_content || 'A refined spa experience with thoughtful service, premium presentation, and bookings tracked in Spaio.'} />
          <ul className="elvt-checklist">
            <li><CheckCircle2 size={17} /> Tenant content stays synced across every template.</li>
            <li><CheckCircle2 size={17} /> Services and packages keep online booking enabled.</li>
            <li><CheckCircle2 size={17} /> Contact, WhatsApp, call, maps, and social links are controlled from settings.</li>
          </ul>
          <div className="elvt-stats">
            <span><strong>06</strong> Services</span>
            <span><strong>03</strong> Offers</span>
            <span><strong>24h</strong> Requests</span>
          </div>
        </div>
      </div>
    </section>
  );
}

function Offers({ tenant, packages, onBook }) {
  return (
    <section className="elvt-section elvt-offers" id="offers">
      <div className="elvt-container">
        <SectionHeading eyebrow="Our pricing" title="Gift-worthy packages" text="Offers are shown in pricing rows like the original template, while preserving Spaio package content and booking." />
        <div className="elvt-price-table">
          {packages.map((pkg, index) => (
            <article className={index === 1 ? 'is-highlighted' : ''} key={pkg.id}>
              <img src={uploadUrl(pkg.image_path) ?? fallbacks.package} alt={pkg.name} />
              <div>
                <span>{index === 1 ? 'Recommended' : 'Spa Package'}</span>
                <h3>{pkg.name}</h3>
                <p>{pkg.included_description}</p>
              </div>
              <div className="elvt-price">
                <small>{money(pkg.original_price, tenant.currency_code)}</small>
                <strong>{money(pkg.discounted_price, tenant.currency_code)}</strong>
              </div>
              <button className="elvt-btn elvt-btn--primary" type="button" onClick={() => onBook({ type: 'package', id: pkg.id, name: pkg.name, price: pkg.discounted_price })}>
                <CalendarCheck size={16} /> Book Now
              </button>
              <CardActions tenant={tenant} label={pkg.name} />
            </article>
          ))}
        </div>
        {!packages.length && <p className="elvt-empty">Offers will appear here after they are published.</p>}
      </div>
    </section>
  );
}

function Staff({ staff }) {
  return (
    <section className="elvt-section elvt-staff-section">
      <div className="elvt-container">
        <SectionHeading eyebrow="Therapists" title="Specialists you can trust" text="Staff profiles use tenant dashboard data and keep this template's horizontal specialist layout." />
        <div className="elvt-staff-list">
          {staff.map((member, index) => (
            <article key={member.id}>
              <img src={uploadUrl(member.photo_path) ?? fallbacks.staff} alt={member.name} />
              <div>
                <small>0{(index % 9) + 1}</small>
                <h3>{member.name}</h3>
                {member.role && <span>{member.role}</span>}
                {member.bio && <p>{member.bio}</p>}
              </div>
            </article>
          ))}
        </div>
        {!staff.length && <p className="elvt-empty">Staff profiles will appear here after they are published.</p>}
      </div>
    </section>
  );
}

function Gallery({ gallery, tenant }) {
  const items = gallery.length ? gallery : [{ id: 'fallback', title: tenant.business_name, image_path: null }];

  return (
    <section className="elvt-section elvt-gallery" id="gallery">
      <div className="elvt-container">
        <SectionHeading eyebrow="Gallery" title="A calm look inside" text="Masonry-style gallery presentation, populated by the tenant gallery manager." />
        <div className="elvt-masonry">
          {items.map((image, index) => (
            <figure key={image.id} className={index % 3 === 0 ? 'is-tall' : ''}>
              <img src={uploadUrl(image.image_path) ?? galleryFallbacks[index % galleryFallbacks.length]} alt={image.alt_text ?? image.title ?? 'Spa gallery'} />
              <figcaption>{image.title ?? 'Spa ambience'} <ChevronRight size={15} /></figcaption>
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
    <section className="elvt-contact" id="contact">
      <div className="elvt-contact__bg" style={{ backgroundImage: `url("${fallbacks.testimonial}")` }} />
      <div className="elvt-container elvt-contact__grid">
        <div>
          <SectionHeading light eyebrow="Contact" title="Request your next appointment" text={tenant.footer_content || `Reach ${tenant.business_name} for service details, packages, and private bookings.`} />
          <div className="elvt-contact-actions">
            {tenant.phone && <a href={`tel:${tenant.phone}`}><Phone size={18} /> {tenant.phone}</a>}
            {whatsapp && <a href={whatsapp} target="_blank" rel="noreferrer"><MessageCircle size={18} /> WhatsApp</a>}
            {tenant.address && <span><MapPin size={18} /> {tenant.address}</span>}
            {tenant.google_maps_url && <a href={tenant.google_maps_url} target="_blank" rel="noreferrer"><MapPin size={18} /> Open location</a>}
          </div>
        </div>
        <aside>
          <Gift size={32} />
          <h3>Gift Voucher</h3>
          <p>Use packages and services from the dashboard to promote premium spa experiences.</p>
          <div>
            {socials.map(([label, url]) => <a href={url} target="_blank" rel="noreferrer" key={label}>{label}</a>)}
            {!socials.length && <span>Social links appear here from tenant settings.</span>}
          </div>
        </aside>
      </div>
    </section>
  );
}

export default function EleventhTemplate({ tenant, services = [], packages = [], staff = [], gallery = [], booking_endpoint, action_endpoint }) {
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
    <div className="eleventh-template-site">
      <Head title={tenant.business_name}>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="" />
        <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@400;700&family=Nunito+Sans:wght@400;600;700;800&display=swap" rel="stylesheet" />
        <link rel="icon" href={defaultLogo} type="image/png" />
        <link rel="stylesheet" href={`${templateBase}/assets/css/spaio-eleventh.css`} />
      </Head>

      <Header tenant={tenant} />
      <main>
        <Hero tenant={tenant} services={services} onBook={setBooking} />
        <Services tenant={tenant} services={services} onBook={setBooking} />
        <About tenant={tenant} />
        <Offers tenant={tenant} packages={packages} onBook={setBooking} />
        <Staff staff={staff} />
        <Gallery gallery={gallery} tenant={tenant} />
        <Contact tenant={tenant} />
      </main>

      <footer className="elvt-footer">
        <div className="elvt-container">
          <strong>{tenant.business_name}</strong>
          <span>{tenant.footer_content || `Copyright ${new Date().getFullYear()} ${tenant.business_name}. All rights reserved.`}</span>
        </div>
      </footer>

      {notice && (
        <div className="elvt-alert" role="alert" aria-live="polite">
          <CheckCircle2 size={20} />
          <span>{notice}</span>
          <button type="button" aria-label="Dismiss alert" onClick={() => setNotice('')}><X size={16} /></button>
        </div>
      )}

      <BookingModal booking={booking} endpoint={booking_endpoint} currency={tenant.currency_code} onClose={() => setBooking(null)} onBooked={setNotice} />
    </div>
  );
}
