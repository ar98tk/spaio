import { Head, useForm, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { useTenantActionTracking } from '../../lib/tenantActionTracking';
import {
  CalendarCheck,
  CheckCircle2,
  Clock3,
  Heart,
  Mail,
  MapPin,
  Menu,
  MessageCircle,
  Phone,
  Scissors,
  Search,
  Send,
  ShoppingBag,
  Sparkles,
  Star,
  X,
} from 'lucide-react';

const templateBase = '/template-kits/twelfth-template';
const defaultLogo = '/default.png';

const fallbacks = {
  hero: `${templateBase}/assets/images/sliders/slide-bg/1.jpg`,
  service: `${templateBase}/assets/images/services/1.jpg`,
  package: `${templateBase}/assets/images/background/5.jpg`,
  hours: `${templateBase}/assets/images/background/2.jpg`,
  testimonial: `${templateBase}/assets/images/testimonial/bg-1.jpg`,
  staff: `${templateBase}/assets/images/team/grid/1.jpg`,
};

const serviceFallbacks = [
  `${templateBase}/assets/images/services/1.jpg`,
  `${templateBase}/assets/images/services/2.jpg`,
  `${templateBase}/assets/images/services/3.jpg`,
  `${templateBase}/assets/images/services/4.png`,
  `${templateBase}/assets/images/services/5.png`,
  `${templateBase}/assets/images/services/6.png`,
];

const galleryFallbacks = [
  `${templateBase}/assets/images/gallery/3col/1.jpg`,
  `${templateBase}/assets/images/gallery/3col/2.jpg`,
  `${templateBase}/assets/images/gallery/3col/3.jpg`,
  `${templateBase}/assets/images/gallery/3col/4.jpg`,
  `${templateBase}/assets/images/gallery/3col/5.jpg`,
  `${templateBase}/assets/images/gallery/3col/6.jpg`,
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
    ['Pricing', '#pricing'],
    ['Staff', '#staff'],
    ['Gallery', '#gallery'],
    ['Contact', '#contact'],
  ];

  return (
    <header className="twlt-header">
      <div className="twlt-topbar">
        <div className="twlt-shell twlt-topbar__inner">
          <div>
            <span><Clock3 size={14} /> Open by appointment</span>
            {tenant.phone && <a href={`tel:${tenant.phone}`}><Phone size={14} /> {tenant.phone}</a>}
          </div>
          <div>
            {tenant.address && <span><MapPin size={14} /> {tenant.address}</span>}
            {socials.map(([label, url]) => (
              <a href={url} target="_blank" rel="noreferrer" key={label} aria-label={label}><Star size={13} /></a>
            ))}
          </div>
        </div>
      </div>

      <nav className="twlt-shell twlt-nav" aria-label="Primary navigation">
        <a className="twlt-brand" href="#home" onClick={() => setOpen(false)}>
          <img src={logo} alt={`${tenant.business_name} logo`} />
          <span>{tenant.business_name}</span>
        </a>

        <button className="twlt-menu" type="button" aria-label="Toggle navigation" aria-expanded={open} onClick={() => setOpen((value) => !value)}>
          {open ? <X size={22} /> : <Menu size={22} />}
        </button>

        <div className={`twlt-links ${open ? 'is-open' : ''}`}>
          {links.map(([label, href]) => (
            <a href={href} key={label} onClick={() => setOpen(false)}>{label}</a>
          ))}
        </div>

        <div className="twlt-nav-actions">
          <a href="#services" aria-label="Search services"><Search size={18} /></a>
          <a href="#offers" aria-label="View offers"><ShoppingBag size={18} /></a>
          <a className="twlt-book-link" href="#pricing">Book Online</a>
        </div>
      </nav>
    </header>
  );
}

function Heading({ eyebrow, title, text, light = false }) {
  return (
    <div className={`twlt-heading ${light ? 'twlt-heading--light' : ''}`}>
      <p>{eyebrow}</p>
      <h2>{title}</h2>
      {text && <span>{text}</span>}
      <div />
    </div>
  );
}

function CardActions({ tenant, label }) {
  const whatsapp = whatsappUrl(tenant.whatsapp_number, `Hello, I am interested in ${label}.`);

  if (!tenant.show_whatsapp_button && !tenant.show_call_button && !tenant.show_location_button) return null;

  return (
    <div className="twlt-card-actions">
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
    <div className="twlt-booking" role="dialog" aria-modal="true" aria-labelledby="twlt-booking-title">
      <button className="twlt-booking__shade" type="button" aria-label="Close booking form" onClick={onClose} />
      <form className="twlt-booking__panel" onSubmit={submit}>
        <button className="twlt-booking__close" type="button" aria-label="Close booking form" onClick={onClose}><X size={18} /></button>
        <p>Book online</p>
        <h2 id="twlt-booking-title">{booking.name}</h2>
        <strong>{money(booking.price, currency)}</strong>
        <div className="twlt-booking__fields">
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
          <div className="twlt-booking__row">
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
        <button className="twlt-button twlt-button--primary" type="submit" disabled={form.processing}>
          <Send size={16} /> {form.processing ? 'Sending...' : 'Send Booking Request'}
        </button>
      </form>
    </div>
  );
}

function Hero({ tenant, services, onBook }) {
  const featured = services[0];
  const hero = uploadUrl(tenant.hero_image_path) ?? fallbacks.hero;

  return (
    <section className="twlt-hero" id="home">
      <img src={hero} alt={`${tenant.business_name} hero`} />
      <div className="twlt-hero__overlay" />
      <div className="twlt-shell twlt-hero__content">
        <p><Sparkles size={17} /> Style, care, and calm rituals</p>
        <h1>{tenant.hero_title || `We will make you stylish at ${tenant.business_name}`}</h1>
        <span>{tenant.hero_subtitle || 'A polished salon and spa experience with services, offers, staff, gallery, and bookings managed in Spaio.'}</span>
        <div>
          <a className="twlt-button twlt-button--primary" href="#services">Read More</a>
          {featured && (
            <button className="twlt-button twlt-button--white" type="button" onClick={() => onBook({ type: 'service', id: featured.id, name: featured.name, price: featured.price })}>
              Get Started
            </button>
          )}
        </div>
      </div>
    </section>
  );
}

function Services({ tenant, services, onBook }) {
  const featured = services.slice(0, 3);
  const rest = services.slice(3);

  return (
    <section className="twlt-section twlt-services" id="services">
      <div className="twlt-shell">
        <Heading eyebrow="What we do" title="Services with a salon edge" text="The first services are displayed as large Hairy-style service panels, while remaining services continue in a compact pricing menu." />
        <div className="twlt-service-panels">
          {featured.map((service, index) => (
            <article key={service.id}>
              <img src={uploadUrl(service.image_path) ?? serviceFallbacks[index % serviceFallbacks.length]} alt={service.name} />
              <Scissors size={28} />
              <h3>{service.name}</h3>
              <p>{service.description}</p>
              <strong>{money(service.price, tenant.currency_code)}</strong>
              <button type="button" onClick={() => onBook({ type: 'service', id: service.id, name: service.name, price: service.price })}>
                Book Now
              </button>
              <CardActions tenant={tenant} label={service.name} />
            </article>
          ))}
        </div>

        {rest.length > 0 && (
          <div className="twlt-menu-list">
            {rest.map((service) => (
              <article key={service.id}>
                <div>
                  <h3>{service.name}</h3>
                  <p>{service.description}</p>
                </div>
                <span />
                <strong>{money(service.price, tenant.currency_code)}</strong>
                <button type="button" onClick={() => onBook({ type: 'service', id: service.id, name: service.name, price: service.price })}>Book</button>
              </article>
            ))}
          </div>
        )}
        {!services.length && <p className="twlt-empty">Services will appear here after they are published.</p>}
      </div>
    </section>
  );
}

function Hours({ tenant }) {
  return (
    <section className="twlt-hours">
      <img src={fallbacks.hours} alt="" />
      <div className="twlt-hours__overlay" />
      <div className="twlt-shell">
        <Heading light eyebrow="Working hours" title="Book your next visit" text={tenant.about_content || 'Every appointment request is stored in the tenant dashboard, whether it starts from a service or promotional package.'} />
        <div className="twlt-hours__grid">
          <span><Clock3 size={20} /> Mon - Fri <strong>9.00 - 17.00</strong></span>
          <span><CalendarCheck size={20} /> Online booking <strong>Always open</strong></span>
          <span><MapPin size={20} /> Location <strong>{tenant.address || 'Add address in settings'}</strong></span>
        </div>
      </div>
    </section>
  );
}

function Offers({ tenant, packages, onBook }) {
  return (
    <section className="twlt-section twlt-pricing" id="pricing">
      <div className="twlt-shell">
        <Heading eyebrow="Our pricing" title="Spa packages and offers" text="Packages follow the original menu-pricing style with visible original and discounted pricing." />
        <div className="twlt-package-menu">
          {packages.map((pkg) => (
            <article key={pkg.id}>
              <img src={uploadUrl(pkg.image_path) ?? fallbacks.package} alt={pkg.name} />
              <div>
                <h3>{pkg.name}</h3>
                <p>{pkg.included_description}</p>
                <CardActions tenant={tenant} label={pkg.name} />
              </div>
              <span />
              <div className="twlt-package-price">
                <small>{money(pkg.original_price, tenant.currency_code)}</small>
                <strong>{money(pkg.discounted_price, tenant.currency_code)}</strong>
              </div>
              <button type="button" onClick={() => onBook({ type: 'package', id: pkg.id, name: pkg.name, price: pkg.discounted_price })}>
                Book
              </button>
            </article>
          ))}
        </div>
        {!packages.length && <p className="twlt-empty">Offers will appear here after they are published.</p>}
      </div>
    </section>
  );
}

function Staff({ staff }) {
  return (
    <section className="twlt-section twlt-team" id="staff">
      <div className="twlt-shell">
        <Heading eyebrow="Our staff" title="Trusted specialists" text="Staff profiles use the tenant dashboard, presented in a clean salon-team grid." />
        <div className="twlt-team-grid">
          {staff.map((member, index) => (
            <article key={member.id}>
              <img src={uploadUrl(member.photo_path) ?? `${templateBase}/assets/images/team/grid/${(index % 6) + 1}.jpg`} alt={member.name} />
              <div>
                <h3>{member.name}</h3>
                {member.role && <span>{member.role}</span>}
                {member.bio && <p>{member.bio}</p>}
              </div>
            </article>
          ))}
        </div>
        {!staff.length && <p className="twlt-empty">Staff profiles will appear here after they are published.</p>}
      </div>
    </section>
  );
}

function Gallery({ gallery, tenant }) {
  const items = gallery.length ? gallery : [{ id: 'fallback', title: tenant.business_name, image_path: null }];

  return (
    <section className="twlt-section twlt-gallery" id="gallery">
      <div className="twlt-shell">
        <Heading eyebrow="Gallery" title={`Inside ${tenant.business_name}`} text="A sharp multi-column gallery using tenant uploads." />
        <div className="twlt-gallery-grid">
          {items.map((image, index) => (
            <figure key={image.id}>
              <img src={uploadUrl(image.image_path) ?? galleryFallbacks[index % galleryFallbacks.length]} alt={image.alt_text ?? image.title ?? 'Spa gallery'} />
              <figcaption>{image.title ?? 'Gallery'} <Heart size={15} /></figcaption>
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
    <section className="twlt-testimonial-contact" id="contact">
      <img src={fallbacks.testimonial} alt="" />
      <div className="twlt-testimonial-contact__overlay" />
      <div className="twlt-shell twlt-testimonial-contact__grid">
        <div>
          <Heading light eyebrow="Clients say" title="Care that guests remember" text={tenant.footer_content || `Contact ${tenant.business_name} for appointment requests, service questions, directions, and package details.`} />
          <blockquote>
            <Star size={20} />
            Bookings, WhatsApp clicks, phone calls, and location actions all stay connected to the tenant experience.
          </blockquote>
        </div>
        <aside>
          {tenant.phone && <a href={`tel:${tenant.phone}`}><Phone size={18} /> {tenant.phone}</a>}
          {whatsapp && <a href={whatsapp} target="_blank" rel="noreferrer"><MessageCircle size={18} /> WhatsApp booking</a>}
          {tenant.address && <span><MapPin size={18} /> {tenant.address}</span>}
          {tenant.google_maps_url && <a href={tenant.google_maps_url} target="_blank" rel="noreferrer"><MapPin size={18} /> Open location</a>}
          <div>
            {socials.map(([label, url]) => <a href={url} target="_blank" rel="noreferrer" key={label}>{label}</a>)}
            {!socials.length && <span>Social links appear here from tenant settings.</span>}
          </div>
        </aside>
      </div>
    </section>
  );
}

export default function TwelfthTemplate({ tenant, services = [], packages = [], staff = [], gallery = [], booking_endpoint, action_endpoint }) {
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
    <div className="twelfth-template-site">
      <Head title={tenant.business_name}>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="" />
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700;800&family=Playfair+Display:wght@400;700;900&display=swap" rel="stylesheet" />
        <link rel="icon" href={defaultLogo} type="image/png" />
        <link rel="stylesheet" href={`${templateBase}/assets/css/spaio-twelfth.css`} />
      </Head>

      <Header tenant={tenant} />
      <main>
        <Hero tenant={tenant} services={services} onBook={setBooking} />
        <Services tenant={tenant} services={services} onBook={setBooking} />
        <Hours tenant={tenant} />
        <Offers tenant={tenant} packages={packages} onBook={setBooking} />
        <Staff staff={staff} />
        <Gallery gallery={gallery} tenant={tenant} />
        <Contact tenant={tenant} />
      </main>

      <footer className="twlt-footer">
        <div className="twlt-shell twlt-footer__grid">
          <div>
            <strong>{tenant.business_name}</strong>
            <p>{tenant.footer_content || `Copyright ${new Date().getFullYear()} ${tenant.business_name}. All rights reserved.`}</p>
          </div>
          <div>
            {tenant.phone && <a href={`tel:${tenant.phone}`}><Phone size={16} /> {tenant.phone}</a>}
            {tenant.address && <span><MapPin size={16} /> {tenant.address}</span>}
            {tenant.google_maps_url && <a href={tenant.google_maps_url} target="_blank" rel="noreferrer"><MapPin size={16} /> Google Maps</a>}
          </div>
          <div>
            <span><Mail size={16} /> Add email in dashboard settings</span>
            <a href="#pricing"><CalendarCheck size={16} /> Book Online</a>
          </div>
        </div>
      </footer>

      {notice && (
        <div className="twlt-alert" role="alert" aria-live="polite">
          <CheckCircle2 size={20} />
          <span>{notice}</span>
          <button type="button" aria-label="Dismiss alert" onClick={() => setNotice('')}><X size={16} /></button>
        </div>
      )}

      <BookingModal booking={booking} endpoint={booking_endpoint} currency={tenant.currency_code} onClose={() => setBooking(null)} onBooked={setNotice} />
    </div>
  );
}
