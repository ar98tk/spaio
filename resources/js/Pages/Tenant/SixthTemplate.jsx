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

const templateBase = '/template-kits/sixth-template';
const defaultLogo = '/default.png';

const fallbackImages = {
  hero: `${templateBase}/assets/images/bg/introBg1_1.jpg`,
  heroFigure: `${templateBase}/assets/images/hero/heroThumb3_1.png`,
  about: `${templateBase}/assets/images/about/aboutThumb1_1.png`,
  service: `${templateBase}/assets/images/services/servicesThumb2_1.png`,
  package: `${templateBase}/assets/images/pricing/pricingThumb1_1.jpg`,
  staff: `${templateBase}/assets/images/team/teamThumb1_1.jpg`,
  gallery: `${templateBase}/assets/images/gallery/galleryThumb1_1.jpg`,
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
    <header className="sixt-header">
      <nav className="sixt-shell sixt-nav" aria-label="Primary navigation">
        <a className="sixt-brand" href="#home" onClick={() => setOpen(false)}>
          <img src={logo} alt={`${tenant.business_name} logo`} />
          <span>
            <strong>{tenant.business_name}</strong>
            <small>Reluxe spa studio</small>
          </span>
        </a>

        <button className="sixt-menu-button" type="button" aria-label="Open menu" aria-expanded={open} onClick={() => setOpen((value) => !value)}>
          <Menu size={22} />
        </button>

        <div className={`sixt-nav-links ${open ? 'is-open' : ''}`}>
          {links.map(([label, href]) => (
            <a key={label} href={href} onClick={() => setOpen(false)}>{label}</a>
          ))}
          <a className="sixt-nav-cta" href="#services" onClick={() => setOpen(false)}>Get in touch</a>
        </div>
      </nav>
    </header>
  );
}

function SectionTitle({ eyebrow, title, text, light = false, align = 'center' }) {
  return (
    <div className={`sixt-section-title sixt-section-title--${align} ${light ? 'sixt-section-title--light' : ''}`}>
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
    <div className="sixt-card-actions">
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
    <div className="sixt-booking" role="dialog" aria-modal="true" aria-labelledby="sixt-booking-title">
      <button className="sixt-booking__backdrop" type="button" aria-label="Close booking form" onClick={onClose} />
      <form className="sixt-booking__panel" onSubmit={submit}>
        <button className="sixt-booking__close" type="button" aria-label="Close booking form" onClick={onClose}><X size={18} /></button>
        <p className="sixt-kicker">Appointment request</p>
        <h2 id="sixt-booking-title">Book {booking.name}</h2>
        <strong>{money(booking.price, currency)}</strong>

        <div className="sixt-booking__fields">
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
          <div className="sixt-booking__row">
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
            <textarea value={form.data.notes} onChange={(event) => form.setData('notes', event.target.value)} placeholder="Treatment notes, preferred therapist, or guest count" />
            {form.errors.notes && <small>{form.errors.notes}</small>}
          </label>
        </div>

        <button className="sixt-button sixt-button--primary sixt-booking__submit" type="submit" disabled={form.processing}>
          <Send size={16} /> {form.processing ? 'Sending...' : 'Send booking request'}
        </button>
      </form>
    </div>
  );
}

function Hero({ tenant, services, onBook }) {
  const hero = uploadUrl(tenant.hero_image_path) ?? fallbackImages.hero;
  const firstService = services[0];

  return (
    <section className="sixt-hero" id="home" style={{ '--sixt-hero-bg': `url("${hero}")` }}>
      <div className="sixt-shell sixt-hero__grid">
        <div className="sixt-hero__content">
          <p className="sixt-kicker">Beauty care and wellness</p>
          <h1>{tenant.hero_title || `Timeless elegance at ${tenant.business_name}`}</h1>
          <p>{tenant.hero_subtitle || 'Modern spa rituals, premium therapists, and online booking for every service and package.'}</p>
          <div className="sixt-hero__actions">
            <a className="sixt-button sixt-button--primary" href="#services"><CalendarCheck size={18} /> Explore services</a>
            {firstService && (
              <button className="sixt-button sixt-button--light" type="button" onClick={() => onBook({ type: 'service', id: firstService.id, name: firstService.name, price: firstService.price })}>
                Book featured
              </button>
            )}
          </div>
        </div>
        <div className="sixt-hero__visual">
          <img src={fallbackImages.heroFigure} alt={`${tenant.business_name} spa model`} />
          <aside>
            <Sparkles size={22} />
            <strong>{services.length} Services</strong>
            <span>Bookable from this website</span>
          </aside>
        </div>
      </div>
    </section>
  );
}

function About({ tenant }) {
  return (
    <section className="sixt-section sixt-about" id="about">
      <div className="sixt-shell sixt-about__grid">
        <div className="sixt-about__image">
          <img src={uploadUrl(tenant.hero_image_path) ?? fallbackImages.about} alt={`${tenant.business_name} interior`} />
        </div>
        <div>
          <SectionTitle align="left" eyebrow="About us" title="A refined beauty ritual with modern care" text={tenant.about_content || `${tenant.business_name} blends elegant spa hospitality, skilled therapists, premium treatment rooms, and a simple online booking experience.`} />
          <div className="sixt-about__list">
            <span><Leaf size={18} /> Natural rituals and premium oils</span>
            <span><Gem size={18} /> Modern beauty care and spa packages</span>
            <span><Clock3 size={18} /> Booking requests tracked in the dashboard</span>
          </div>
        </div>
      </div>
    </section>
  );
}

function Services({ tenant, services, onBook }) {
  const icons = ['servicesIcon1_1.svg', 'servicesIcon1_2.svg', 'servicesIcon1_3.svg', 'servicesIcon2_1.svg', 'servicesIcon2_2.svg', 'servicesIcon2_3.svg'];

  return (
    <section className="sixt-section sixt-services" id="services">
      <div className="sixt-shell">
        <SectionTitle eyebrow="Our services" title="What we provide" text="Every service uses the tenant dashboard content: image, description, price, and booking action." />
        <div className="sixt-service-grid">
          {services.map((service, index) => (
            <article className="sixt-service-card" key={service.id}>
              <div className="sixt-service-card__icon">
                <img src={`${templateBase}/assets/images/icon/${icons[index % icons.length]}`} alt="" />
              </div>
              <img src={uploadUrl(service.image_path) ?? fallbackImages.service} alt={service.name} />
              <div>
                <h3>{service.name}</h3>
                <p>{service.description}</p>
                <strong>{money(service.price, tenant.currency_code)}</strong>
                <button className="sixt-button sixt-button--primary" type="button" onClick={() => onBook({ type: 'service', id: service.id, name: service.name, price: service.price })}>
                  <CalendarCheck size={16} /> Book Now
                </button>
                <CardActions tenant={tenant} label={service.name} />
              </div>
            </article>
          ))}
        </div>
        {!services.length && <p className="sixt-empty">Services will appear here after they are published.</p>}
      </div>
    </section>
  );
}

function Offers({ tenant, packages, onBook }) {
  return (
    <section className="sixt-section sixt-offers" id="offers">
      <div className="sixt-shell">
        <SectionTitle eyebrow="Pricing plans" title="Curated offers for complete renewal" text="Package content stays unchanged across templates while the visual treatment adapts." />
        <div className="sixt-package-grid">
          {packages.map((pkg, index) => (
            <article className={`sixt-package-card ${index === 1 ? 'is-featured' : ''}`} key={pkg.id}>
              <img src={uploadUrl(pkg.image_path) ?? fallbackImages.package} alt={pkg.name} />
              <div>
                <span>{index === 1 ? 'Featured' : 'Package'}</span>
                <h3>{pkg.name}</h3>
                <ul>
                  {String(pkg.included_description ?? '').split(/\r?\n/).filter(Boolean).map((item) => (
                    <li key={item}><CheckCircle2 size={15} /> {item}</li>
                  ))}
                </ul>
                <div className="sixt-price-row">
                  <small>{money(pkg.original_price, tenant.currency_code)}</small>
                  <strong>{money(pkg.discounted_price, tenant.currency_code)}</strong>
                </div>
                <button className="sixt-button sixt-button--primary" type="button" onClick={() => onBook({ type: 'package', id: pkg.id, name: pkg.name, price: pkg.discounted_price })}>
                  <CalendarCheck size={16} /> Book Now
                </button>
                <CardActions tenant={tenant} label={pkg.name} />
              </div>
            </article>
          ))}
        </div>
        {!packages.length && <p className="sixt-empty">Offers will appear here after they are published.</p>}
      </div>
    </section>
  );
}

function Staff({ staff }) {
  return (
    <section className="sixt-section" id="team">
      <div className="sixt-shell">
        <SectionTitle eyebrow="Our team" title="Timeless elegance with a modern twist" text="Staff profiles are tenant-managed and shared by every website template." />
        <div className="sixt-staff-grid">
          {staff.map((member, index) => (
            <article className="sixt-staff-card" key={member.id}>
              <img src={uploadUrl(member.photo_path) ?? `${templateBase}/assets/images/team/teamThumb1_${(index % 3) + 1}.jpg`} alt={member.name} />
              <div>
                <h3>{member.name}</h3>
                {member.role && <span>{member.role}</span>}
                {member.bio && <p>{member.bio}</p>}
              </div>
            </article>
          ))}
        </div>
        {!staff.length && <p className="sixt-empty">Staff profiles will appear here after they are published.</p>}
      </div>
    </section>
  );
}

function Gallery({ gallery, tenant }) {
  const items = gallery.length ? gallery : [{ id: 'fallback', title: tenant.business_name, image_path: null }];

  return (
    <section className="sixt-section sixt-gallery" id="gallery">
      <div className="sixt-shell">
        <SectionTitle light eyebrow="Our gallery" title={`Inside ${tenant.business_name}`} text="Gallery photos use tenant uploads and adapt to this template's project-style grid." />
        <div className="sixt-gallery-grid">
          {items.map((image, index) => (
            <figure key={image.id} className={index === 0 || index === 3 ? 'is-large' : ''}>
              <img src={uploadUrl(image.image_path) ?? `${templateBase}/assets/images/gallery/galleryThumb1_${(index % 4) + 1}.jpg`} alt={image.alt_text ?? image.title ?? 'Spa gallery'} />
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
    <section className="sixt-section sixt-contact-section" id="contact">
      <div className="sixt-shell sixt-contact">
        <div>
          <p className="sixt-kicker">Contact us</p>
          <h2>Ready for a refined spa appointment?</h2>
          <p>{tenant.footer_content || `Contact ${tenant.business_name} for treatments, offers, and private wellness reservations.`}</p>
        </div>
        <aside>
          <div className="sixt-contact-list">
            {tenant.phone && <a href={`tel:${tenant.phone}`}><Phone size={18} /> {tenant.phone}</a>}
            {whatsapp && <a href={whatsapp} target="_blank" rel="noreferrer"><MessageCircle size={18} /> WhatsApp booking</a>}
            {tenant.address && <span><MapPin size={18} /> {tenant.address}</span>}
            {tenant.google_maps_url && <a href={tenant.google_maps_url} target="_blank" rel="noreferrer"><MapPin size={18} /> Open Google Maps</a>}
          </div>
          <div className="sixt-socials">
            {socials.map(([label, url]) => <a href={url} target="_blank" rel="noreferrer" key={label}><Star size={16} /> {label}</a>)}
            {!socials.length && <span>Social links will appear here after they are added in settings.</span>}
          </div>
        </aside>
      </div>
    </section>
  );
}

export default function SixthTemplate({ tenant, services = [], packages = [], staff = [], gallery = [], booking_endpoint, action_endpoint }) {
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
    <div className="sixth-template-site">
      <Head title={tenant.business_name}>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="" />
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Marcellus&display=swap" rel="stylesheet" />
        <link rel="icon" href={defaultLogo} type="image/png" />
        <link rel="stylesheet" href={`${templateBase}/assets/css/spaio-sixth.css`} />
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
      <footer className="sixt-footer">
        <div className="sixt-shell">
          <strong>{tenant.business_name}</strong>
          <span>{tenant.footer_content || `Copyright ${new Date().getFullYear()} ${tenant.business_name}. All rights reserved.`}</span>
        </div>
      </footer>

      {notice && (
        <div className="sixt-alert" role="alert" aria-live="polite">
          <CheckCircle2 size={20} />
          <span>{notice}</span>
          <button type="button" aria-label="Dismiss alert" onClick={() => setNotice('')}><X size={16} /></button>
        </div>
      )}

      <BookingModal booking={booking} endpoint={booking_endpoint} currency={tenant.currency_code} onClose={() => setBooking(null)} onBooked={setNotice} />
    </div>
  );
}
