import { Head, useForm, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { useTenantActionTracking } from '../../lib/tenantActionTracking';
import {
  ArrowRight,
  CalendarCheck,
  CheckCircle2,
  Flower2,
  Gem,
  Leaf,
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

const templateBase = '/template-kits/eighth-template';
const defaultLogo = '/default.png';

const fallbackImages = {
  hero: `${templateBase}/images/home-1/slider/slider-1.jpg`,
  about: `${templateBase}/images/home-1/beautifull-spa.jpg`,
  service: `${templateBase}/images/home-1/services/facial/massage.jpg`,
  package: `${templateBase}/images/service/middle-banar.jpg`,
  staff: `${templateBase}/images/team/img-1.png`,
  gallery: `${templateBase}/images/gallery/img-1.jpg`,
};

const galleryFallbacks = [
  `${templateBase}/images/gallery/img-1.jpg`,
  `${templateBase}/images/gallery/img-2.jpg`,
  `${templateBase}/images/gallery/img-3.jpg`,
  `${templateBase}/images/home-1/services/facial/massage-slider/img-1.jpg`,
  `${templateBase}/images/home-1/services/body/thumb/img-2.jpg`,
  `${templateBase}/images/home-1/services/foot/thumb/img-3.jpg`,
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
  const links = [
    ['About', '#about'],
    ['Services', '#services'],
    ['Offers', '#offers'],
    ['Experts', '#staff'],
    ['Gallery', '#gallery'],
    ['Contact', '#contact'],
  ];

  return (
    <header className="sevt-header">
      <div className="sevt-shell sevt-nav">
        <a className="sevt-brand" href="#home" onClick={() => setOpen(false)}>
          <img src={logo} alt={`${tenant.business_name} logo`} />
          <span>{tenant.business_name}</span>
        </a>

        <button className="sevt-menu" type="button" aria-label="Toggle menu" aria-expanded={open} onClick={() => setOpen((value) => !value)}>
          {open ? <X size={22} /> : <Menu size={22} />}
        </button>

        <nav className={`sevt-links ${open ? 'is-open' : ''}`} aria-label="Main navigation">
          {links.map(([label, href]) => (
            <a key={label} href={href} onClick={() => setOpen(false)}>{label}</a>
          ))}
          <a className="sevt-nav-cta" href="#services" onClick={() => setOpen(false)}>
            Book Now <ArrowRight size={16} />
          </a>
        </nav>
      </div>
    </header>
  );
}

function SectionTitle({ eyebrow, title, text, light = false, center = true }) {
  return (
    <div className={`sevt-section-title ${light ? 'sevt-section-title--light' : ''} ${center ? 'is-center' : ''}`}>
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
    <div className="sevt-card-actions">
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
    <div className="sevt-booking" role="dialog" aria-modal="true" aria-labelledby="sevt-booking-title">
      <button className="sevt-booking__shade" type="button" aria-label="Close booking form" onClick={onClose} />
      <form className="sevt-booking__panel" onSubmit={submit}>
        <button className="sevt-booking__close" type="button" aria-label="Close booking form" onClick={onClose}><X size={18} /></button>
        <p className="sevt-kicker">Reserve your visit</p>
        <h2 id="sevt-booking-title">Book {booking.name}</h2>
        <strong>{money(booking.price, currency)}</strong>

        <div className="sevt-booking__fields">
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
          <div className="sevt-booking__row">
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

        <button className="sevt-button sevt-button--dark" type="submit" disabled={form.processing}>
          <Send size={17} /> {form.processing ? 'Sending...' : 'Send Booking Request'}
        </button>
      </form>
    </div>
  );
}

function Hero({ tenant, services, onBook }) {
  const heroImage = uploadUrl(tenant.hero_image_path) ?? fallbackImages.hero;
  const featuredService = services[0];

  return (
    <section className="sevt-hero" id="home">
      <div className="sevt-hero__media" style={{ backgroundImage: `url("${heroImage}")` }} />
      <div className="sevt-shell sevt-hero__grid">
        <div className="sevt-hero__content">
          <p className="sevt-kicker"><Sparkles size={16} /> Private wellness studio</p>
          <h1>{tenant.hero_title || `A serene escape at ${tenant.business_name}`}</h1>
          <span>{tenant.hero_subtitle || 'Premium spa rituals, beauty care, massage therapy, and packages managed from one Spaio dashboard.'}</span>
          <div className="sevt-hero__actions">
            <a className="sevt-button sevt-button--dark" href="#services">Explore Services <ArrowRight size={17} /></a>
            {featuredService && (
              <button className="sevt-button sevt-button--light" type="button" onClick={() => onBook({ type: 'service', id: featuredService.id, name: featuredService.name, price: featuredService.price })}>
                Book Featured
              </button>
            )}
          </div>
        </div>

        <aside className="sevt-hero-card">
          <div>
            <span><Leaf size={18} /> Signature ritual</span>
            <strong>{featuredService?.name || 'Luxury Spa Journey'}</strong>
            <p>{featuredService?.description || 'Relaxing body work, calm interiors, and attentive care in one refined appointment.'}</p>
          </div>
          <div className="sevt-hero-card__bottom">
            <strong>{featuredService ? money(featuredService.price, tenant.currency_code) : 'Book today'}</strong>
            <a href="#offers">View offers</a>
          </div>
        </aside>
      </div>
    </section>
  );
}

function About({ tenant }) {
  return (
    <section className="sevt-section sevt-about" id="about">
      <div className="sevt-shell sevt-about__grid">
        <div className="sevt-about__image">
          <img src={uploadUrl(tenant.hero_image_path) ?? fallbackImages.about} alt={`${tenant.business_name} spa interior`} />
          <div><Flower2 size={22} /> Calm, clean, premium</div>
        </div>
        <div>
          <SectionTitle eyebrow="About the spa" title={`Designed around ${tenant.business_name}`} text={tenant.about_content || 'A focused wellness destination for guests who expect calm service, polished presentation, and simple booking.'} center={false} />
          <div className="sevt-about__features">
            <span><ShieldCheck size={18} /> Professional tenant-managed website content</span>
            <span><Gem size={18} /> Services, packages, staff, and gallery stay synced</span>
            <span><CalendarCheck size={18} /> Booking requests arrive inside the dashboard</span>
          </div>
        </div>
      </div>
    </section>
  );
}

function Services({ tenant, services, onBook }) {
  return (
    <section className="sevt-section sevt-services" id="services">
      <div className="sevt-shell">
        <SectionTitle eyebrow="Treatments" title="Signature spa services" text="Each card uses tenant services, tenant currency, card actions, and the shared booking flow." />
        <div className="sevt-service-grid">
          {services.map((service, index) => (
            <article className="sevt-service-card" key={service.id}>
              <img src={uploadUrl(service.image_path) ?? galleryFallbacks[index % galleryFallbacks.length] ?? fallbackImages.service} alt={service.name} />
              <div className="sevt-service-card__content">
                <span>0{(index % 9) + 1}</span>
                <h3>{service.name}</h3>
                <p>{service.description}</p>
                <div className="sevt-price-line">
                  <strong>{money(service.price, tenant.currency_code)}</strong>
                  <button type="button" onClick={() => onBook({ type: 'service', id: service.id, name: service.name, price: service.price })}>
                    <CalendarCheck size={16} /> Book Now
                  </button>
                </div>
                <CardActions tenant={tenant} label={service.name} />
              </div>
            </article>
          ))}
        </div>
        {!services.length && <p className="sevt-empty">Services will appear here after they are published.</p>}
      </div>
    </section>
  );
}

function Offers({ tenant, packages, onBook }) {
  return (
    <section className="sevt-section sevt-offers" id="offers">
      <div className="sevt-shell">
        <SectionTitle light eyebrow="Offers" title="Curated wellness packages" text="Packages keep the same tenant data while this template presents them as premium editorial offers." />
        <div className="sevt-offer-grid">
          {packages.map((pkg, index) => (
            <article className={`sevt-offer-card ${index === 1 ? 'is-featured' : ''}`} key={pkg.id}>
              <div className="sevt-offer-card__image">
                <img src={uploadUrl(pkg.image_path) ?? fallbackImages.package} alt={pkg.name} />
              </div>
              <div>
                <span>{index === 1 ? 'Most requested' : 'Package'}</span>
                <h3>{pkg.name}</h3>
                <ul>
                  {String(pkg.included_description ?? '').split(/\r?\n/).filter(Boolean).map((item) => (
                    <li key={item}><CheckCircle2 size={15} /> {item}</li>
                  ))}
                </ul>
                <div className="sevt-offer-card__price">
                  <small>{money(pkg.original_price, tenant.currency_code)}</small>
                  <strong>{money(pkg.discounted_price, tenant.currency_code)}</strong>
                </div>
                <button className="sevt-button sevt-button--gold" type="button" onClick={() => onBook({ type: 'package', id: pkg.id, name: pkg.name, price: pkg.discounted_price })}>
                  <CalendarCheck size={16} /> Book Now
                </button>
                <CardActions tenant={tenant} label={pkg.name} />
              </div>
            </article>
          ))}
        </div>
        {!packages.length && <p className="sevt-empty sevt-empty--light">Offers will appear here after they are published.</p>}
      </div>
    </section>
  );
}

function Staff({ staff }) {
  return (
    <section className="sevt-section" id="staff">
      <div className="sevt-shell">
        <SectionTitle eyebrow="Experts" title="Meet the spa team" text="Staff profiles are pulled directly from the tenant dashboard." />
        <div className="sevt-staff-grid">
          {staff.map((member, index) => (
            <article className="sevt-staff-card" key={member.id}>
            <img src={uploadUrl(member.photo_path) ?? `${templateBase}/images/team/img-${(index % 6) + 1}.png`} alt={member.name} />
              <div>
                <h3>{member.name}</h3>
                {member.role && <span>{member.role}</span>}
                {member.bio && <p>{member.bio}</p>}
                <small><Star size={14} /> Specialist #{(index % 9) + 1}</small>
              </div>
            </article>
          ))}
        </div>
        {!staff.length && <p className="sevt-empty">Staff profiles will appear here after they are published.</p>}
      </div>
    </section>
  );
}

function Gallery({ gallery, tenant }) {
  const items = gallery.length ? gallery : [{ id: 'fallback', title: tenant.business_name, image_path: null }];

  return (
    <section className="sevt-section sevt-gallery" id="gallery">
      <div className="sevt-shell">
        <SectionTitle eyebrow="Ambience" title={`Inside ${tenant.business_name}`} text="Gallery images are tenant uploads, arranged as a luxury visual journal." />
        <div className="sevt-gallery-grid">
          {items.map((image, index) => (
            <figure key={image.id} className={index % 5 === 0 ? 'is-wide' : ''}>
              <img src={uploadUrl(image.image_path) ?? galleryFallbacks[index % galleryFallbacks.length] ?? fallbackImages.gallery} alt={image.alt_text ?? image.title ?? 'Spa gallery'} />
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
    <section className="sevt-section sevt-contact-section" id="contact">
      <div className="sevt-shell sevt-contact">
        <div>
          <p className="sevt-kicker"><Sparkles size={16} /> Plan your appointment</p>
          <h2>Begin with one calm request</h2>
          <p>{tenant.footer_content || `Contact ${tenant.business_name} for bookings, offers, directions, and private wellness reservations.`}</p>
        </div>
        <aside>
          {tenant.phone && <a href={`tel:${tenant.phone}`}><Phone size={18} /> {tenant.phone}</a>}
          {whatsapp && <a href={whatsapp} target="_blank" rel="noreferrer"><MessageCircle size={18} /> WhatsApp booking</a>}
          {tenant.address && <span><MapPin size={18} /> {tenant.address}</span>}
          {tenant.google_maps_url && <a href={tenant.google_maps_url} target="_blank" rel="noreferrer"><MapPin size={18} /> Open Google Maps</a>}
          <div className="sevt-socials">
            {socials.map(([label, url]) => <a href={url} target="_blank" rel="noreferrer" key={label}><Star size={15} /> {label}</a>)}
            {!socials.length && <span>Social links will appear here after they are added in settings.</span>}
          </div>
        </aside>
      </div>
    </section>
  );
}

export default function EighthTemplate({ tenant, services = [], packages = [], staff = [], gallery = [], booking_endpoint, action_endpoint }) {
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
    <div className="eighth-template-site">
      <Head title={tenant.business_name}>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="" />
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700;800&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet" />
        <link rel="icon" href={defaultLogo} type="image/png" />
        <link rel="stylesheet" href={`${templateBase}/assets/css/spaio-eighth.css`} />
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
      <footer className="sevt-footer">
        <div className="sevt-shell">
          <strong>{tenant.business_name}</strong>
          <span>{tenant.footer_content || `Copyright ${new Date().getFullYear()} ${tenant.business_name}. All rights reserved.`}</span>
        </div>
      </footer>

      {notice && (
        <div className="sevt-alert" role="alert" aria-live="polite">
          <CheckCircle2 size={20} />
          <span>{notice}</span>
          <button type="button" aria-label="Dismiss alert" onClick={() => setNotice('')}><X size={16} /></button>
        </div>
      )}

      <BookingModal booking={booking} endpoint={booking_endpoint} currency={tenant.currency_code} onClose={() => setBooking(null)} onBooked={setNotice} />
    </div>
  );
}
