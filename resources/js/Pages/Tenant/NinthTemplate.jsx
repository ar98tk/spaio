import { Head, useForm, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { useTenantActionTracking } from '../../lib/tenantActionTracking';
import {
  ArrowRight,
  CalendarCheck,
  CheckCircle2,
  Clock3,
  Crown,
  Gem,
  Heart,
  Mail,
  MapPin,
  Menu,
  MessageCircle,
  Phone,
  Scissors,
  Send,
  Sparkles,
  Star,
  X,
} from 'lucide-react';

const templateBase = '/template-kits/ninth-template';
const defaultLogo = '/default.png';

const fallbacks = {
  hero: `${templateBase}/images/main-slider/slide1.jpg`,
  heroAccent: `${templateBase}/images/main-slider/pic-1.png`,
  about: `${templateBase}/images/about/img1.jpg`,
  aboutTwo: `${templateBase}/images/about/img2.jpg`,
  service: `${templateBase}/images/our-services/pic1.jpg`,
  package: `${templateBase}/images/product/item1.jpg`,
  staff: `${templateBase}/images/our-team/pic1.jpg`,
  gallery: `${templateBase}/images/gallery/masonry/image-1.jpg`,
};

const serviceFallbacks = [
  `${templateBase}/images/our-services/pic1.jpg`,
  `${templateBase}/images/our-services/pic2.jpg`,
  `${templateBase}/images/our-services/pic3.jpg`,
  `${templateBase}/images/our-services/pic4.jpg`,
  `${templateBase}/images/our-services/hair/pic1.jpg`,
  `${templateBase}/images/our-services/bridal/pic1.jpg`,
];

const packageFallbacks = [
  `${templateBase}/images/product/item1.jpg`,
  `${templateBase}/images/product/item2.jpg`,
  `${templateBase}/images/product/item3.jpg`,
  `${templateBase}/images/product/item4.jpg`,
];

const staffFallbacks = [
  `${templateBase}/images/our-team/pic1.jpg`,
  `${templateBase}/images/our-team/pic2.jpg`,
  `${templateBase}/images/our-team/pic3.jpg`,
  `${templateBase}/images/our-team/pic4.jpg`,
];

const galleryFallbacks = [
  `${templateBase}/images/gallery/masonry/image-1.jpg`,
  `${templateBase}/images/gallery/masonry/image-2.jpg`,
  `${templateBase}/images/gallery/masonry/image-3.jpg`,
  `${templateBase}/images/gallery/masonry/image-4.jpg`,
  `${templateBase}/images/gallery/masonry/image-5.jpg`,
  `${templateBase}/images/gallery/masonry/image-6.jpg`,
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
    ['Home', '#home'],
    ['Services', '#services'],
    ['Offers', '#offers'],
    ['Team', '#staff'],
    ['Gallery', '#gallery'],
    ['Contact', '#contact'],
  ];
  const socials = [
    ['Instagram', tenant.instagram_url, Heart],
    ['Facebook', tenant.facebook_url, Heart],
    ['Snapchat', tenant.snapchat_url, Star],
    ['YouTube', tenant.youtube_url, Star],
    ['TikTok', tenant.tiktok_url, Star],
    ['X', tenant.x_url, Sparkles],
  ].filter(([, url]) => url);

  return (
    <header className="nint-header">
      <div className="nint-topbar">
        <div className="nint-shell nint-topbar__inner">
          <div>
            <span><Clock3 size={14} /> Open daily by appointment</span>
            {tenant.phone && <a href={`tel:${tenant.phone}`}><Phone size={14} /> {tenant.phone}</a>}
          </div>
          <div>
            {tenant.address && <span><MapPin size={14} /> {tenant.address}</span>}
            {socials.map(([label, url, Icon]) => (
              <a key={label} href={url} target="_blank" rel="noreferrer" aria-label={label}><Icon size={14} /></a>
            ))}
          </div>
        </div>
      </div>

      <nav className="nint-shell nint-nav" aria-label="Primary navigation">
        <button className="nint-menu" type="button" aria-label="Toggle navigation" aria-expanded={open} onClick={() => setOpen((value) => !value)}>
          {open ? <X size={22} /> : <Menu size={22} />}
        </button>

        <a className="nint-brand" href="#home" onClick={() => setOpen(false)}>
          <img src={logo} alt={`${tenant.business_name} logo`} />
          <span>{tenant.business_name}</span>
        </a>

        <div className={`nint-links ${open ? 'is-open' : ''}`}>
          {links.map(([label, href]) => (
            <a href={href} key={label} onClick={() => setOpen(false)}>{label}</a>
          ))}
        </div>

        <a className="nint-nav-cta" href="#services">Book Now</a>
      </nav>
    </header>
  );
}

function Heading({ eyebrow, title, text, light = false }) {
  return (
    <div className={`nint-heading ${light ? 'nint-heading--light' : ''}`}>
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
    <div className="nint-card-actions">
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
    <div className="nint-booking" role="dialog" aria-modal="true" aria-labelledby="nint-booking-title">
      <button className="nint-booking__shade" type="button" aria-label="Close booking form" onClick={onClose} />
      <form className="nint-booking__panel" onSubmit={submit}>
        <button className="nint-booking__close" type="button" aria-label="Close booking form" onClick={onClose}><X size={18} /></button>
        <p>Reserve your visit</p>
        <h2 id="nint-booking-title">Book {booking.name}</h2>
        <strong>{money(booking.price, currency)}</strong>

        <div className="nint-booking__fields">
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
          <div className="nint-booking__row">
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

        <button className="nint-button nint-button--primary" type="submit" disabled={form.processing}>
          <Send size={16} /> {form.processing ? 'Sending...' : 'Send Booking Request'}
        </button>
      </form>
    </div>
  );
}

function Hero({ tenant, services, onBook }) {
  const hero = uploadUrl(tenant.hero_image_path) ?? fallbacks.hero;
  const featured = services[0];

  return (
    <section className="nint-hero" id="home">
      <div className="nint-hero__bg" style={{ backgroundImage: `url(${hero})` }} />
      <div className="nint-shell nint-hero__grid">
        <div className="nint-hero__copy">
          <p><Crown size={17} /> Boutique beauty and spa studio</p>
          <h1>{tenant.hero_title || `Beauty Services at ${tenant.business_name}`}</h1>
          <span>{tenant.hero_subtitle || 'A polished salon and spa experience for premium treatments, curated offers, expert staff, and instant online booking.'}</span>
          <div>
            <a className="nint-button nint-button--primary" href="#services">Explore Services</a>
            {featured && (
              <button className="nint-button nint-button--light" type="button" onClick={() => onBook({ type: 'service', id: featured.id, name: featured.name, price: featured.price })}>
                Book Signature
              </button>
            )}
          </div>
        </div>
        <div className="nint-hero__visual" aria-hidden="true">
          <img src={fallbacks.heroAccent} alt="" />
          <div className="nint-hero__stat nint-hero__stat--one">
            <strong>4.9</strong>
            <span>client rating</span>
          </div>
          <div className="nint-hero__stat nint-hero__stat--two">
            <strong>{services.length || 6}</strong>
            <span>rituals online</span>
          </div>
        </div>
      </div>
    </section>
  );
}

function About({ tenant }) {
  return (
    <section className="nint-about" id="about">
      <div className="nint-shell nint-about__grid">
        <div className="nint-about__collage">
          <img src={fallbacks.about} alt={`${tenant.business_name} treatment room`} />
          <img src={fallbacks.aboutTwo} alt={`${tenant.business_name} beauty ritual`} />
          <div><Sparkles size={22} /> Signature spa rituals</div>
        </div>
        <div className="nint-about__copy">
          <Heading eyebrow="About us" title="Beauty care with a calm, polished rhythm" text={tenant.about_content || `${tenant.business_name} brings together expert therapists, premium treatments, and a comfortable booking experience for every guest.`} />
          <div className="nint-about__features">
            {[
              ['Certified specialists', 'Experienced team profiles and service expertise.'],
              ['Online reservations', 'Customers can book services and offers directly.'],
              ['Connected actions', 'WhatsApp, call, maps, and social links stay visible.'],
            ].map(([title, text]) => (
              <article key={title}>
                <CheckCircle2 size={20} />
                <div>
                  <h3>{title}</h3>
                  <p>{text}</p>
                </div>
              </article>
            ))}
          </div>
        </div>
      </div>
    </section>
  );
}

function Services({ tenant, services, currency, onBook }) {
  return (
    <section className="nint-services" id="services">
      <div className="nint-shell">
        <Heading eyebrow="Beauty menu" title="Choose your treatment" text="Service cards keep actions, pricing, and booking visible while preserving the original BeautyZone visual style." />
        <div className="nint-service-grid">
          {services.map((service, index) => {
            const image = uploadUrl(service.image_path) ?? serviceFallbacks[index % serviceFallbacks.length] ?? fallbacks.service;

            return (
              <article className="nint-service-card" key={service.id}>
                <div className="nint-service-card__image">
                  <img src={image} alt={service.name} />
                  <span><Scissors size={18} /></span>
                </div>
                <div className="nint-service-card__body">
                  <h3>{service.name}</h3>
                  <p>{service.description}</p>
                  <strong>{money(service.price, currency)}</strong>
                  <button type="button" onClick={() => onBook({ type: 'service', id: service.id, name: service.name, price: service.price })}>
                    Book now <ArrowRight size={15} />
                  </button>
                  <CardActions tenant={tenant} label={service.name} />
                </div>
              </article>
            );
          })}
        </div>
      </div>
    </section>
  );
}

function Offers({ tenant, packages, currency, onBook }) {
  if (!packages.length) return null;

  return (
    <section className="nint-offers" id="offers">
      <div className="nint-shell">
        <Heading eyebrow="Packages" title="Curated beauty offers" text="Promotional packages use product-inspired cards so offers feel retail-ready and easy to compare." light />
        <div className="nint-offer-grid">
          {packages.map((item, index) => {
            const image = uploadUrl(item.image_path) ?? packageFallbacks[index % packageFallbacks.length] ?? fallbacks.package;

            return (
              <article className="nint-offer-card" key={item.id}>
                <img src={image} alt={item.name} />
                <div>
                  <span>{money(item.original_price, currency)}</span>
                  <h3>{item.name}</h3>
                  <p>{item.included_description}</p>
                  <strong>{money(item.discounted_price, currency)}</strong>
                  <button type="button" onClick={() => onBook({ type: 'package', id: item.id, name: item.name, price: item.discounted_price })}>
                    Book offer <CalendarCheck size={15} />
                  </button>
                  <CardActions tenant={tenant} label={item.name} />
                </div>
              </article>
            );
          })}
        </div>
      </div>
    </section>
  );
}

function Staff({ staff }) {
  if (!staff.length) return null;

  return (
    <section className="nint-staff" id="staff">
      <div className="nint-shell">
        <Heading eyebrow="Experts" title="Meet the beauty team" text="Staff profiles stay structured but use portrait-heavy cards from the ninth kit." />
        <div className="nint-staff-grid">
          {staff.map((person, index) => {
            const image = uploadUrl(person.photo_path) ?? staffFallbacks[index % staffFallbacks.length] ?? fallbacks.staff;

            return (
              <article className="nint-staff-card" key={person.id}>
                <img src={image} alt={person.name} />
                <div>
                  <h3>{person.name}</h3>
                  <span>{person.role}</span>
                  <p>{person.bio}</p>
                </div>
              </article>
            );
          })}
        </div>
      </div>
    </section>
  );
}

function Gallery({ gallery }) {
  const items = gallery.length ? gallery : galleryFallbacks.map((image, index) => ({
    id: `fallback-${index}`,
    title: `Spa gallery ${index + 1}`,
    alt_text: `Spa gallery ${index + 1}`,
    image_path: image,
  }));

  return (
    <section className="nint-gallery" id="gallery">
      <div className="nint-shell">
        <Heading eyebrow="Gallery" title="A closer look inside" text="A lively masonry grid gives tenants a rich place to show the spa, treatments, and rooms." />
        <div className="nint-gallery-grid">
          {items.slice(0, 8).map((item, index) => {
            const image = uploadUrl(item.image_path) ?? galleryFallbacks[index % galleryFallbacks.length] ?? fallbacks.gallery;
            return (
              <figure key={item.id} className={index % 3 === 0 ? 'is-tall' : ''}>
                <img src={image} alt={item.alt_text || item.title || 'Spa gallery'} />
                <figcaption>{item.title || 'Spa moment'}</figcaption>
              </figure>
            );
          })}
        </div>
      </div>
    </section>
  );
}

function Contact({ tenant }) {
  const socials = [
    ['Instagram', tenant.instagram_url],
    ['Facebook', tenant.facebook_url],
    ['Snapchat', tenant.snapchat_url],
    ['YouTube', tenant.youtube_url],
    ['TikTok', tenant.tiktok_url],
    ['X', tenant.x_url],
  ].filter(([, url]) => url);

  return (
    <section className="nint-contact" id="contact">
      <div className="nint-shell nint-contact__grid">
        <div>
          <Heading eyebrow="Contact" title="Plan your next visit" text="Every important customer action is available from the public website." light={false} />
          <p>{tenant.footer_content || 'Book a visit, ask about a package, or find the spa location in one click.'}</p>
        </div>
        <div className="nint-contact__cards">
          {tenant.phone && <a href={`tel:${tenant.phone}`}><Phone size={18} /> {tenant.phone}</a>}
          {tenant.whatsapp_number && <a href={whatsappUrl(tenant.whatsapp_number, `Hello ${tenant.business_name}`)} target="_blank" rel="noreferrer"><MessageCircle size={18} /> WhatsApp</a>}
          {tenant.address && <span><MapPin size={18} /> {tenant.address}</span>}
          {tenant.google_maps_url && <a href={tenant.google_maps_url} target="_blank" rel="noreferrer"><MapPin size={18} /> Open Google Maps</a>}
          <a href={`mailto:hello@${tenant.subdomain || 'spaio'}.com`}><Mail size={18} /> Send email</a>
          {socials.map(([label, url]) => (
            <a href={url} target="_blank" rel="noreferrer" key={label}><Star size={18} /> {label}</a>
          ))}
        </div>
      </div>
    </section>
  );
}

export default function NinthTemplate({
  tenant,
  services = [],
  packages = [],
  staff = [],
  gallery = [],
  booking_endpoint,
  action_endpoint,
}) {
  useTenantActionTracking(action_endpoint);
  const { props } = usePage();
  const [booking, setBooking] = useState(null);
  const [notice, setNotice] = useState(props.flash?.success ?? null);
  const currency = tenant.currency_code || 'USD';

  useEffect(() => {
    if (!notice) return undefined;
    const timeout = window.setTimeout(() => setNotice(null), 5000);
    return () => window.clearTimeout(timeout);
  }, [notice]);

  return (
    <>
      <Head title={tenant.business_name}>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="" />
        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700;900&family=Nunito:wght@500;700;800;900&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href={`${templateBase}/assets/css/spaio-ninth.css`} />
      </Head>

      <main className="nint-site">
        {notice && (
          <div className="nint-success" role="status">
            <CheckCircle2 size={18} />
            {notice}
          </div>
        )}

        <Header tenant={tenant} />
        <Hero tenant={tenant} services={services} onBook={setBooking} />
        <About tenant={tenant} />
        <Services tenant={tenant} services={services} currency={currency} onBook={setBooking} />
        <Offers tenant={tenant} packages={packages} currency={currency} onBook={setBooking} />
        <Staff staff={staff} />
        <Gallery gallery={gallery} />
        <Contact tenant={tenant} />

        <footer className="nint-footer">
          <div className="nint-shell">
            <div>
              <Gem size={22} />
              <strong>{tenant.business_name}</strong>
            </div>
            <span>{tenant.footer_content || 'Premium spa, beauty, and wellness booking powered by Spaio.'}</span>
          </div>
        </footer>

        <BookingModal booking={booking} endpoint={booking_endpoint} currency={currency} onClose={() => setBooking(null)} onBooked={setNotice} />
      </main>
    </>
  );
}
