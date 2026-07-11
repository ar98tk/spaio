import { Head } from '@inertiajs/react';
import { useTenantActionTracking } from '../../lib/tenantActionTracking';
import {
  ArrowRight,
  CalendarCheck,
  CheckCircle2,
  ExternalLink,
  Image as ImageIcon,
  LinkIcon,
  MapPin,
  Menu,
  MessageCircle,
  Phone,
  Sparkles,
  Star,
  Users,
} from 'lucide-react';

const fallbackImages = {
  hero: 'https://images.unsplash.com/photo-1600334129128-685c5582fd35?auto=format&fit=crop&w=1800&q=85',
  service: 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=1000&q=85',
  package: 'https://images.unsplash.com/photo-1515377905703-c4788e51af15?auto=format&fit=crop&w=1000&q=85',
  staff: 'https://images.unsplash.com/photo-1580618672591-eb180b1a973f?auto=format&fit=crop&w=900&q=85',
  gallery: 'https://images.unsplash.com/photo-1519823551278-64ac92734fb1?auto=format&fit=crop&w=900&q=85',
};
const defaultLogo = '/default.png';

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
  return (
    <header className="fixed inset-x-0 top-0 z-30 border-b border-white/20 bg-white/85 backdrop-blur-2xl">
      <div className="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 lg:px-8">
        <a href="#top" className="flex items-center gap-3">
          <img src={uploadUrl(tenant.logo_path) ?? defaultLogo} alt={tenant.business_name} className="h-11 w-11 rounded-xl object-contain" />
          <span className="text-lg font-bold text-[#123C36]">{tenant.business_name}</span>
        </a>
        <nav className="hidden items-center gap-6 text-sm font-semibold text-slate-600 lg:flex">
          <a href="#services" className="hover:text-[#123C36]">Services</a>
          <a href="#packages" className="hover:text-[#123C36]">Packages</a>
          <a href="#team" className="hover:text-[#123C36]">Team</a>
          <a href="#gallery" className="hover:text-[#123C36]">Gallery</a>
          <a href="#contact" className="hover:text-[#123C36]">Contact</a>
        </nav>
        <div className="flex items-center gap-3">
          <a href="#contact" className="hidden rounded-[20px] bg-[#123C36] px-5 py-3 text-sm font-bold text-white shadow-[rgba(18,60,54,0.18)_0px_12px_28px_0px] transition hover:bg-[#0D2E29] sm:inline-flex">
            Book now
          </a>
          <button className="grid h-11 w-11 place-items-center rounded-xl border border-[#E4E8E3] text-[#123C36] lg:hidden" aria-label="Open menu">
            <Menu className="h-5 w-5" />
          </button>
        </div>
      </div>
    </header>
  );
}

function SectionTitle({ eyebrow, title, description }) {
  return (
    <div className="mx-auto max-w-3xl text-center">
      <p className="text-sm font-bold uppercase tracking-[0.24em] text-[#C7A560]">{eyebrow}</p>
      <h2 className="mt-4 text-3xl font-bold leading-tight text-[#123C36] md:text-5xl">{title}</h2>
      {description && <p className="mt-5 text-lg font-medium leading-8 text-slate-600">{description}</p>}
    </div>
  );
}

function CardActions({ tenant, label }) {
  const message = `Hello, I am interested in ${label}.`;
  const whatsApp = whatsappUrl(tenant.whatsapp_number, message);

  if (!tenant.show_whatsapp_button && !tenant.show_call_button && !tenant.show_location_button) {
    return null;
  }

  return (
    <div className="mt-5 flex flex-wrap gap-2">
      {tenant.show_whatsapp_button && whatsApp && (
        <a href={whatsApp} target="_blank" rel="noreferrer" className="inline-flex items-center gap-2 rounded-full bg-[#F0FDFA] px-4 py-2 text-sm font-bold text-[#0F766E]">
          <MessageCircle className="h-4 w-4" /> WhatsApp
        </a>
      )}
      {tenant.show_call_button && tenant.phone && (
        <a href={`tel:${tenant.phone}`} className="inline-flex items-center gap-2 rounded-full bg-[#F8F6EF] px-4 py-2 text-sm font-bold text-[#8A6A22]">
          <Phone className="h-4 w-4" /> Call
        </a>
      )}
      {tenant.show_location_button && tenant.google_maps_url && (
        <a href={tenant.google_maps_url} target="_blank" rel="noreferrer" className="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-sm font-bold text-[#123C36] ring-1 ring-[#E4E8E3]">
          <MapPin className="h-4 w-4" /> Location
        </a>
      )}
    </div>
  );
}

function ServiceCard({ service, tenant }) {
  return (
    <article className="group overflow-hidden rounded-[20px] border border-[#E4E8E3] bg-white shadow-[rgba(25,55,101,0.04)_0px_8px_34px_0px] transition duration-300 hover:-translate-y-1 hover:shadow-[rgba(25,55,101,0.10)_0px_16px_44px_0px]">
      <img src={uploadUrl(service.image_path) ?? fallbackImages.service} alt={service.name} className="h-64 w-full object-cover transition duration-500 group-hover:scale-105" />
      <div className="p-6">
        <div className="flex items-start justify-between gap-4">
          <h3 className="text-2xl font-bold text-[#123C36]">{service.name}</h3>
          <span className="rounded-full bg-[#F8F6EF] px-4 py-2 text-sm font-bold text-[#8A6A22]">{money(service.price, tenant.currency_code)}</span>
        </div>
        <p className="mt-4 text-base font-medium leading-7 text-slate-600">{service.description}</p>
        <CardActions tenant={tenant} label={service.name} />
      </div>
    </article>
  );
}

function PackageCard({ pkg, tenant }) {
  return (
    <article className="group overflow-hidden rounded-[20px] border border-[#E4E8E3] bg-white shadow-[rgba(25,55,101,0.04)_0px_8px_34px_0px] transition duration-300 hover:-translate-y-1 hover:shadow-[rgba(25,55,101,0.10)_0px_16px_44px_0px]">
      <img src={uploadUrl(pkg.image_path) ?? fallbackImages.package} alt={pkg.name} className="h-72 w-full object-cover transition duration-500 group-hover:scale-105" />
      <div className="p-6">
        <div className="flex items-start justify-between gap-4">
          <h3 className="text-2xl font-bold text-[#123C36]">{pkg.name}</h3>
          <div className="text-right">
            <p className="text-sm font-bold text-slate-400 line-through">{money(pkg.original_price, tenant.currency_code)}</p>
            <p className="text-lg font-bold text-[#0F766E]">{money(pkg.discounted_price, tenant.currency_code)}</p>
          </div>
        </div>
        <p className="mt-4 text-base font-medium leading-7 text-slate-600">{pkg.included_description}</p>
        <CardActions tenant={tenant} label={pkg.name} />
      </div>
    </article>
  );
}

function EmptyState({ icon: Icon, title }) {
  return (
    <div className="rounded-[20px] border border-dashed border-[#D5D9E0] bg-white p-10 text-center">
      <Icon className="mx-auto h-10 w-10 text-slate-300" />
      <p className="mt-4 text-base font-semibold text-slate-500">{title}</p>
    </div>
  );
}

export default function Website({ tenant, services = [], packages = [], staff = [], gallery = [], action_endpoint }) {
  useTenantActionTracking(action_endpoint);
  const heroImage = uploadUrl(tenant.hero_image_path) ?? fallbackImages.hero;
  const heroTitle = tenant.hero_title || `Luxury wellness at ${tenant.business_name}`;
  const heroSubtitle = tenant.hero_subtitle || 'Book premium spa, massage, hammam, and wellness experiences in a calm modern space designed around you.';
  const contactWhatsapp = whatsappUrl(tenant.whatsapp_number, `Hello, I would like to book at ${tenant.business_name}.`);
  const socialLinks = [
    ['Facebook', tenant.facebook_url],
    ['Instagram', tenant.instagram_url],
    ['Snapchat', tenant.snapchat_url],
    ['YouTube', tenant.youtube_url],
    ['TikTok', tenant.tiktok_url],
    ['X', tenant.x_url],
  ].filter(([, url]) => url);

  return (
    <div id="top" className="min-h-screen bg-[#FBFAF7] text-slate-700">
      <Head title={tenant.business_name}>
        <link rel="icon" href={defaultLogo} type="image/png" />
      </Head>
      <Header tenant={tenant} />

      <main>
        <section className="relative min-h-[92vh] overflow-hidden pt-28">
          <div className="absolute inset-0">
            <img src={heroImage} alt={`${tenant.business_name} hero`} className="h-full w-full object-cover" />
            <div className="absolute inset-0 bg-[linear-gradient(90deg,rgba(18,60,54,0.92)_0%,rgba(18,60,54,0.74)_42%,rgba(18,60,54,0.18)_100%)]" />
          </div>
          <div className="relative mx-auto grid min-h-[calc(92vh-7rem)] max-w-7xl items-center gap-10 px-5 py-16 lg:grid-cols-[1.05fr_0.95fr] lg:px-8">
            <div className="max-w-3xl">
              <div className="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm font-bold text-[#E8D5A0] backdrop-blur">
                <Sparkles className="h-4 w-4" /> Premium spa experience
              </div>
              <h1 className="mt-6 text-5xl font-bold leading-[1.04] text-white md:text-7xl">{heroTitle}</h1>
              <p className="mt-6 max-w-2xl text-lg font-medium leading-8 text-white/82 md:text-xl">{heroSubtitle}</p>
              <div className="mt-8 flex flex-wrap gap-3">
                <a href="#contact" className="inline-flex min-h-12 items-center gap-2 rounded-[20px] bg-[#C7A560] px-8 py-4 text-base font-bold text-[#123C36] transition hover:bg-[#DFC17A]">
                  Book appointment <ArrowRight className="h-4 w-4" />
                </a>
                {contactWhatsapp && (
                  <a href={contactWhatsapp} target="_blank" rel="noreferrer" className="inline-flex min-h-12 items-center gap-2 rounded-[20px] border border-white/30 bg-white/10 px-8 py-4 text-base font-bold text-white backdrop-blur transition hover:bg-white/16">
                    WhatsApp us <MessageCircle className="h-4 w-4" />
                  </a>
                )}
              </div>
            </div>

            <div className="rounded-[20px] border border-white/20 bg-white/12 p-5 shadow-[rgba(0,0,0,0.18)_0px_24px_60px_0px] backdrop-blur-2xl">
              <div className="grid gap-4 rounded-2xl bg-white p-5">
                <div className="flex items-center justify-between border-b border-[#E4E8E3] pb-4">
                  <div>
                    <p className="text-sm font-bold uppercase tracking-[0.2em] text-[#C7A560]">Today</p>
                    <h2 className="mt-1 text-2xl font-bold text-[#123C36]">Available experiences</h2>
                  </div>
                  <CalendarCheck className="h-9 w-9 text-[#0F766E]" />
                </div>
                {(services.length ? services.slice(0, 3) : [{ name: 'Signature Massage', price: 45 }, { name: 'Moroccan Bath', price: 60 }, { name: 'Facial Glow', price: 35 }]).map((item) => (
                  <div key={item.name} className="flex items-center justify-between rounded-2xl bg-[#F3F5F8] p-4">
                    <span className="font-bold text-[#123C36]">{item.name}</span>
                    <span className="rounded-full bg-white px-3 py-1 text-sm font-bold text-[#0F766E]">{money(item.price, tenant.currency_code)}</span>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </section>

        <section className="mx-auto max-w-7xl px-5 py-20 lg:px-8">
          <div className="grid gap-5 md:grid-cols-3">
            {[
              ['Premium treatments', `${services.length} active services`, Star],
              ['Curated offers', `${packages.length} promotional packages`, CheckCircle2],
              ['Experienced team', `${staff.length} staff profiles`, Users],
            ].map(([title, value, Icon]) => (
              <div key={title} className="rounded-[20px] border border-[#E4E8E3] bg-white p-6 shadow-[rgba(25,55,101,0.04)_0px_8px_34px_0px]">
                <Icon className="h-7 w-7 text-[#C7A560]" />
                <h2 className="mt-5 text-xl font-bold text-[#123C36]">{title}</h2>
                <p className="mt-2 font-medium text-slate-600">{value}</p>
              </div>
            ))}
          </div>
        </section>

        <section id="services" className="bg-white py-24">
          <div className="mx-auto max-w-7xl px-5 lg:px-8">
            <SectionTitle eyebrow="Services" title="Spa treatments designed for deep renewal" description="Choose from carefully presented services managed directly by the spa team." />
            <div className="mt-14 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
              {services.map((service) => <ServiceCard key={service.id} service={service} tenant={tenant} />)}
            </div>
            {!services.length && <div className="mt-12"><EmptyState icon={Sparkles} title="Services will appear here after the spa owner adds them." /></div>}
          </div>
        </section>

        <section id="packages" className="bg-[#F8F6EF] py-24">
          <div className="mx-auto max-w-7xl px-5 lg:px-8">
            <SectionTitle eyebrow="Packages" title="Offers built around complete experiences" description="Promotional packages combine high-value services into clear, bookable experiences." />
            <div className="mt-14 grid gap-6 lg:grid-cols-2">
              {packages.map((pkg) => <PackageCard key={pkg.id} pkg={pkg} tenant={tenant} />)}
            </div>
            {!packages.length && <div className="mt-12"><EmptyState icon={Star} title="Packages and offers will appear here when published." /></div>}
          </div>
        </section>

        <section id="team" className="bg-white py-24">
          <div className="mx-auto max-w-7xl px-5 lg:px-8">
            <SectionTitle eyebrow="Team" title="Meet the specialists behind the experience" description="Staff profiles help customers feel confident before they book." />
            <div className="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
              {staff.map((member) => (
                <article key={member.id} className="overflow-hidden rounded-[20px] border border-[#E4E8E3] bg-white shadow-[rgba(25,55,101,0.04)_0px_8px_34px_0px]">
                  <img src={uploadUrl(member.photo_path) ?? fallbackImages.staff} alt={member.name} className="h-72 w-full object-cover" />
                  <div className="p-6">
                    <h3 className="text-2xl font-bold text-[#123C36]">{member.name}</h3>
                    {member.role && <p className="mt-1 text-sm font-bold uppercase tracking-[0.18em] text-[#C7A560]">{member.role}</p>}
                    {member.bio && <p className="mt-4 text-base font-medium leading-7 text-slate-600">{member.bio}</p>}
                  </div>
                </article>
              ))}
            </div>
            {!staff.length && <div className="mt-12"><EmptyState icon={Users} title="Staff profiles will appear here when published." /></div>}
          </div>
        </section>

        <section id="gallery" className="bg-[#123C36] py-24">
          <div className="mx-auto max-w-7xl px-5 lg:px-8">
            <div className="mx-auto max-w-3xl text-center">
              <p className="text-sm font-bold uppercase tracking-[0.24em] text-[#C7A560]">Gallery</p>
              <h2 className="mt-4 text-3xl font-bold leading-tight text-white md:text-5xl">A look inside {tenant.business_name}</h2>
              <p className="mt-5 text-lg font-medium leading-8 text-white/70">Showcase rooms, treatments, reception areas, and the details that make the spa feel premium.</p>
            </div>
            <div className="mt-14 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
              {(gallery.length ? gallery : [{ id: 'fallback', title: 'Spa ambience', image_path: null }]).map((image, index) => (
                <img
                  key={image.id}
                  src={uploadUrl(image.image_path) ?? fallbackImages.gallery}
                  alt={image.alt_text ?? image.title ?? 'Spa gallery'}
                  className={`h-72 w-full rounded-[20px] object-cover shadow-[rgba(0,0,0,0.16)_0px_18px_44px_0px] ${index === 0 ? 'lg:col-span-2' : ''}`}
                />
              ))}
            </div>
            {!gallery.length && (
              <div className="mt-8 flex items-center justify-center gap-2 text-sm font-semibold text-white/70">
                <ImageIcon className="h-4 w-4" /> Gallery images will replace this preview.
              </div>
            )}
          </div>
        </section>

        <section id="about" className="bg-white py-24">
          <div className="mx-auto grid max-w-7xl gap-10 px-5 lg:grid-cols-[0.85fr_1.15fr] lg:px-8">
            <div>
              <p className="text-sm font-bold uppercase tracking-[0.24em] text-[#C7A560]">About</p>
              <h2 className="mt-4 text-4xl font-bold leading-tight text-[#123C36]">A calmer way to plan your visit</h2>
            </div>
            <div className="rounded-[20px] border border-[#E4E8E3] bg-[#FBFAF7] p-8 text-lg font-medium leading-8 text-slate-600">
              {tenant.about_content || `${tenant.business_name} brings together premium treatments, thoughtful service, and a refined wellness atmosphere. Explore services, packages, and contact options in one place.`}
            </div>
          </div>
        </section>

        <section id="contact" className="bg-[#F8F6EF] py-24">
          <div className="mx-auto grid max-w-7xl gap-8 px-5 lg:grid-cols-[1fr_0.9fr] lg:px-8">
            <div className="rounded-[20px] bg-[#123C36] p-8 text-white shadow-[rgba(18,60,54,0.22)_0px_24px_60px_0px] md:p-10">
              <p className="text-sm font-bold uppercase tracking-[0.24em] text-[#C7A560]">Contact</p>
              <h2 className="mt-4 text-4xl font-bold leading-tight md:text-5xl">Ready to book your visit?</h2>
              <p className="mt-5 text-lg font-medium leading-8 text-white/72">Use the available contact options below and the team will help you choose the right service or package.</p>
              <div className="mt-8 grid gap-3">
                {tenant.phone && <a href={`tel:${tenant.phone}`} className="inline-flex items-center gap-3 rounded-2xl bg-white/10 px-5 py-4 font-bold text-white"><Phone className="h-5 w-5 text-[#C7A560]" /> {tenant.phone}</a>}
                {contactWhatsapp && <a href={contactWhatsapp} target="_blank" rel="noreferrer" className="inline-flex items-center gap-3 rounded-2xl bg-white/10 px-5 py-4 font-bold text-white"><MessageCircle className="h-5 w-5 text-[#C7A560]" /> WhatsApp booking</a>}
                {tenant.address && <div className="inline-flex items-start gap-3 rounded-2xl bg-white/10 px-5 py-4 font-bold text-white"><MapPin className="mt-0.5 h-5 w-5 shrink-0 text-[#C7A560]" /> {tenant.address}</div>}
                {tenant.google_maps_url && <a href={tenant.google_maps_url} target="_blank" rel="noreferrer" className="inline-flex items-center gap-3 rounded-2xl bg-white/10 px-5 py-4 font-bold text-white"><ExternalLink className="h-5 w-5 text-[#C7A560]" /> Open Google Maps</a>}
              </div>
            </div>
            <div className="rounded-[20px] border border-[#E4E8E3] bg-white p-8 shadow-[rgba(25,55,101,0.04)_0px_8px_34px_0px] md:p-10">
              <h3 className="text-2xl font-bold text-[#123C36]">Follow {tenant.business_name}</h3>
              <p className="mt-4 text-base font-medium leading-7 text-slate-600">Stay connected for new offers, seasonal packages, and latest wellness updates.</p>
              <div className="mt-6 grid gap-3">
                {socialLinks.map(([label, url]) => (
                  <a key={label} href={url} target="_blank" rel="noreferrer" className="flex items-center justify-between rounded-2xl border border-[#E4E8E3] px-5 py-4 font-bold text-[#123C36] transition hover:bg-[#F8F6EF]">
                    <span className="inline-flex items-center gap-3"><LinkIcon className="h-5 w-5 text-[#C7A560]" /> {label}</span>
                    <ArrowRight className="h-4 w-4" />
                  </a>
                ))}
                {!socialLinks.length && <p className="rounded-2xl bg-[#F3F5F8] p-5 font-medium text-slate-600">Social links will appear here after they are added in settings.</p>}
              </div>
            </div>
          </div>
        </section>
      </main>

      <footer className="bg-white py-10">
        <div className="mx-auto flex max-w-7xl flex-col justify-between gap-4 px-5 text-sm font-medium text-slate-500 md:flex-row md:items-center lg:px-8">
          <p className="font-bold text-[#123C36]">{tenant.business_name}</p>
          <p>{tenant.footer_content || `Copyright ${new Date().getFullYear()} ${tenant.business_name}. All rights reserved.`}</p>
        </div>
      </footer>
    </div>
  );
}
