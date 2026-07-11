import { Head, Link, usePage } from '@inertiajs/react';
import {
  ArrowRight,
  BarChart3,
  CalendarCheck,
  CheckCircle2,
  ChevronDown,
  CircleDollarSign,
  Clock3,
  Crown,
  Globe2,
  Image,
  Languages,
  LineChart,
  MapPin,
  Menu,
  MessageCircle,
  MonitorSmartphone,
  MousePointerClick,
  Phone,
  Search,
  Server,
  Sparkles,
  Star,
  Users,
  Wand2,
  X,
  XCircle,
} from 'lucide-react';
import { useMemo, useState } from 'react';

const templateBase = '/template-kits/main-website-template/assets/img';

const assets = {
  heroBg: `${templateBase}/hero-bg-2.jpg`,
  heroShape: `${templateBase}/hero-shape-1.svg`,
  star: `${templateBase}/icons/star-1.svg`,
};

const problems = [
  ['No Professional Website', 'Customers judge the spa before they ever visit, and a weak site lowers trust instantly.', Globe2],
  ['WhatsApp Only Sales', 'Messages come in, but the owner cannot measure which service or campaign created the lead.', MessageCircle],
  ['No Online Booking', 'High-intent customers leave when they cannot reserve a service or package immediately.', CalendarCheck],
  ['No Analytics', 'Owners spend on marketing without knowing visitors, clicks, leads, or conversion rate.', BarChart3],
  ['Weak SEO Visibility', 'Spa websites often miss metadata, structured URLs, fast loading, and local search basics.', Search],
  ['No Marketing Measurement', 'Every call, WhatsApp click, and booking should become a measurable business signal.', MousePointerClick],
];
const why = [
  ['Build', 'Create your professional spa website in minutes.', Wand2],
  ['Grow', 'Generate more bookings and leads.', LineChart],
  ['Track', 'Understand how visitors interact with your business.', MousePointerClick],
  ['Convert', 'Turn website visitors into paying customers.', CircleDollarSign],
];
const templates = [
  ['First Template', '/default.png', 'Dark luxury Arabic spa design with gold accents and premium editorial cards.'],
  ['Second Template', '/template-kits/second-template/images/section-bg/slider-1.jpg', 'Elegant light wellness layout with soft green and cream spa styling.'],
  ['Third Template', '/template-kits/third-template/assets/images/bg/bg-image-1.jpg', 'Clean clinical massage and wellness template with blue calm accents.'],
  ['Fourth Template', '/template-kits/fourth-template/img/slider/slider_bg.png', 'Vibrant beauty and spa layout with bold promotional energy.'],
  ['Fifth Template', '/template-kits/fifth-template/images/1920x1100.jpg', 'Soft editorial massage therapy template with calm coral and aqua details.'],
  ['Sixth Template', '/template-kits/sixth-template/assets/images/bg/introBg1_1.jpg', 'Warm beauty and spa design with rounded services and curated offers.'],
  ['Seventh Template', 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&w=1200&q=88', 'Private botanical wellness template with dark premium atmosphere.'],
  ['Eighth Template', '/template-kits/eighth-template/images/home-1/slider/slider-1.jpg', 'Green spa resort style with nature-inspired imagery and Playfair headings.'],
  ['Ninth Template', '/template-kits/ninth-template/images/main-slider/slide1.jpg', 'BeautyZone-inspired salon and spa layout with pink topbar and circular services.'],
  ['Tenth Template', '/template-kits/tenth-template/images/main-slider/slider1/slide1.jpg', 'Pink and charcoal beauty spa template with polished service sections.'],
  ['Eleventh Template', '/template-kits/eleventh-template/images/contactus-img.jpg', 'Classic spa lounge layout with portrait hero and masonry gallery structure.'],
  ['Twelfth Template', '/template-kits/twelfth-template/assets/images/sliders/slide-bg/1.jpg', 'Dark salon-inspired template with full-screen hero and pricing menu sections.'],
];
const templateImages = templates.map(([, image]) => image);
const steps = ['Choose a Template', 'Upload Logo', 'Add Services', 'Publish Website', 'Start Receiving Bookings'];
const features = [
  ['Website Builder', 'Launch without technical work.', Globe2],
  ['Booking Management', 'Manage all booking statuses.', CalendarCheck],
  ['Marketing Analytics', 'Measure visitors, clicks, and conversions.', BarChart3],
  ['SEO Optimization', 'Built for Google visibility.', Search],
  ['Gallery Manager', 'Showcase rooms and treatments.', Image],
  ['Staff Profiles', 'Present your team professionally.', Users],
  ['Offers & Packages', 'Promote premium bundles.', Crown],
  ['WhatsApp Integration', 'Track high-intent conversations.', MessageCircle],
  ['Google Maps Integration', 'Help customers find you fast.', MapPin],
  ['Custom Domains', 'Connect your branded domain.', Globe2],
  ['Responsive Design', 'Desktop, tablet, and mobile ready.', MonitorSmartphone],
  ['Multi Language Support', 'Prepare Arabic and English websites.', Languages],
  ['Fast Hosting', 'Load quickly for better conversion.', Clock3],
  ['Cloud Infrastructure', 'Ready for SaaS scale.', Server],
];
const faqs = [
  ['Do I need hosting?', 'No. Spaio includes hosted infrastructure for every spa website.'],
  ['Can I use my own domain?', 'Yes. Tenants start with a subdomain and can connect a custom domain later.'],
  ['Can customers book online?', 'Yes. Customers can book both services and promotional packages.'],
  ['Can I change templates later?', 'Yes. Templates can change without affecting your content.'],
  ['Is SEO included?', 'Yes. Spaio supports metadata, clean URLs, sitemap, robots.txt, and fast pages.'],
  ['Can I track visitors?', 'Yes. Analytics are central to Spaio, including visitors, clicks, leads, and bookings.'],
];
const testimonials = [
  ['Lina Hassan', 'Mira Beauty Spa', 'UAE', 'Spaio finally gave our website the same premium feeling as our spa.', 5, templateImages[1]],
  ['Omar Saad', 'Hammam Luxe', 'Morocco', 'We can see where leads come from instead of guessing from WhatsApp messages.', 5, templateImages[7]],
  ['Nadine Fouad', 'Aura Wellness', 'Egypt', 'The platform feels focused on spa businesses, not generic websites.', 5, templateImages[10]],
];

function Header() {
  const { auth } = usePage().props;
  const [open, setOpen] = useState(false);
  const links = [
    ['Features', '#features'],
    ['Templates', '#templates'],
    ['Analytics', '#analytics'],
    ['Pricing', '#pricing'],
    ['FAQ', '#faq'],
  ];

  return (
    <header className="spm-header">
      <div className="spm-shell spm-nav">
        <Link className="spm-brand" href="/" onClick={() => setOpen(false)}>
          <img src="/spaio-logo.png" alt="Spaio logo" />
        </Link>

        <button className="spm-menu" type="button" aria-label="Toggle navigation" aria-expanded={open} onClick={() => setOpen((value) => !value)}>
          {open ? <X size={22} /> : <Menu size={22} />}
        </button>

        <nav className={`spm-links ${open ? 'is-open' : ''}`} aria-label="Main navigation">
          {links.map(([label, href]) => (
            <a key={label} href={href} onClick={() => setOpen(false)}>{label}</a>
          ))}
        </nav>

        <div className="spm-nav-actions">
          {auth.user ? (
            <Link className="spm-btn spm-btn--light" href="/panel">Dashboard</Link>
          ) : (
            <>
              <Link className="spm-login" href="/login">Log in</Link>
              <Link className="spm-btn spm-btn--shine" href="/register">Start Free Trial</Link>
            </>
          )}
        </div>
      </div>
    </header>
  );
}

function SectionHeading({ eyebrow, title, text, light = false }) {
  return (
    <div className={`spm-heading ${light ? 'spm-heading--light' : ''}`}>
      <p><img src={assets.star} alt="" /> {eyebrow} <img src={assets.star} alt="" /></p>
      <h2>{title}</h2>
      {text && <span>{text}</span>}
    </div>
  );
}

function HeroMockup() {
  const bars = [56, 82, 66, 94, 72, 88, 62];

  return (
    <div className="spm-hero-mockup">
      <div className="spm-hero-preview spm-hero-preview--desktop">
        <div className="spm-preview-browser">
          <span />
          <span />
          <span />
        </div>
        <img src={templateImages[8]} alt="Spaio beauty website desktop preview" />
        <div className="spm-preview-caption">
          <img src="/spaio-logo.png" alt="Spaio logo" />
          <strong>Live spa website</strong>
        </div>
      </div>
      <div className="spm-hero-preview spm-hero-preview--mobile">
        <img src={templateImages[1]} alt="Spaio mobile template preview" />
        <strong>Mobile ready</strong>
      </div>
      <div className="spm-dashboard-card">
        <div className="spm-dashboard-top">
          <div>
            <span>Dashboard Analytics</span>
            <strong>12,480</strong>
          </div>
          <em>+28%</em>
        </div>
        <div className="spm-bars">
          {bars.map((bar, index) => (
            <i key={`${bar}-${index}`} style={{ height: `${bar}%`, animationDelay: `${index * 90}ms` }} />
          ))}
        </div>
      </div>
    </div>
  );
}

function Hero() {
  return (
    <section className="spm-hero" style={{ backgroundImage: `url(${assets.heroBg})` }}>
      <img className="spm-hero-shape" src={assets.heroShape} alt="" />
      <div className="spm-shell spm-hero-grid">
        <div className="spm-hero-copy">
          <p><Sparkles size={18} /> Spaio for premium wellness businesses</p>
          <h1>Launch Your Spa Website in Minutes</h1>
          <span>Create a beautiful spa website, accept bookings, track every visitor, and grow your business-all from one platform.</span>
          <div>
            <Link className="spm-btn spm-btn--shine" href="/register">Start Free Trial <ArrowRight size={18} /></Link>
            <a className="spm-btn spm-btn--outline" href="#demo">Watch Demo</a>
          </div>
          <div className="spm-hero-proof">
            <img src="/spaio-logo.png" alt="Spaio logo" />
            <strong>Built for spas, hammams, massage centers, wellness centers, and beauty businesses.</strong>
          </div>
        </div>
        <HeroMockup />
      </div>
    </section>
  );
}

function FreeTrial() {
  return (
    <section className="spm-trial">
      <div className="spm-shell spm-trial-card">
        <div className="spm-trial-orb" aria-hidden="true" />
        <div className="spm-trial-copy">
          <p><Sparkles size={18} /> Limited launch offer</p>
          <h2>Start with 10 Days Free</h2>
          <span>No hosting setup. No technical work. Create your spa website, test bookings, preview templates, and see how Spaio can turn visitors into real customers.</span>
        </div>
        <div className="spm-trial-count">
          <strong>10</strong>
          <span>free days</span>
        </div>
        <div className="spm-trial-actions">
          <Link className="spm-btn spm-btn--shine" href="/register">Start Free Trial <ArrowRight size={18} /></Link>
          <a className="spm-btn spm-btn--glass" href="#pricing">View Plans</a>
        </div>
      </div>
    </section>
  );
}

function Problems() {
  return (
    <section className="spm-section spm-section--soft">
      <div className="spm-shell">
        <SectionHeading eyebrow="The problem" title="Spa owners should not lose bookings to poor digital systems" text="Most spa businesses still rely on scattered WhatsApp messages, weak websites, and no visibility into marketing performance." />
        <div className="spm-problem-grid">
          {problems.map(([title, text, Icon], index) => (
            <article key={title}>
              <div className="spm-problem-index">{String(index + 1).padStart(2, '0')}</div>
              <div className="spm-problem-icon"><Icon size={23} /></div>
              <h3>{title}</h3>
              <p>{text}</p>
              <span><XCircle size={16} /> Growth blocker</span>
            </article>
          ))}
        </div>
        <div className="spm-solve">Spaio solves all of these problems in one platform.</div>
      </div>
    </section>
  );
}

function WhySpaio() {
  return (
    <section className="spm-section">
      <div className="spm-shell">
        <SectionHeading eyebrow="Why Spaio" title="A SaaS platform built for spa growth" text="Spaio is not a generic website builder. Every page, workflow, and analytic is designed for wellness businesses." />
        <div className="spm-why-grid">
          {why.map(([title, text, Icon]) => (
            <article key={title}>
              <span><Icon size={26} /></span>
              <h3>{title}</h3>
              <p>{text}</p>
            </article>
          ))}
        </div>
      </div>
    </section>
  );
}

function Templates() {
  return (
    <section className="spm-section spm-section--dark" id="templates">
      <div className="spm-shell">
        <SectionHeading eyebrow="Website templates" title="Change the look without losing your content" text="Templates are separate from tenant content, so every spa can switch styles while keeping services, packages, staff, gallery, and settings." light />
        <div className="spm-template-grid">
          {templates.map(([name, image, text]) => (
            <article key={name}>
              <img src={image} alt={`${name} template preview`} />
              <div>
                <h3>{name}</h3>
                <p>{text}</p>
                <div>
                  <a href="#templates">Preview Template <ArrowRight size={14} /></a>
                </div>
              </div>
            </article>
          ))}
        </div>
      </div>
    </section>
  );
}

function Process() {
  return (
    <section className="spm-section" id="how-it-works">
      <div className="spm-shell">
        <SectionHeading eyebrow="Website builder process" title="From signup to bookings in five steps" />
        <div className="spm-process">
          {steps.map((step, index) => (
            <article key={step}>
              <strong>{String(index + 1).padStart(2, '0')}</strong>
              <span>{step}</span>
              {index < steps.length - 1 && <ArrowRight size={20} />}
            </article>
          ))}
        </div>
      </div>
    </section>
  );
}

function Features() {
  return (
    <section className="spm-section spm-section--soft" id="features">
      <div className="spm-shell">
        <SectionHeading eyebrow="Features" title="Everything a spa website needs to convert" />
        <div className="spm-feature-grid">
          {features.map(([title, text, Icon]) => (
            <article key={title}>
              <span><Icon size={22} /></span>
              <h3>{title}</h3>
              <p>{text}</p>
            </article>
          ))}
        </div>
      </div>
    </section>
  );
}

function Analytics() {
  const metrics = [
    ['Website Visitors', '12,480'],
    ['Unique Visitors', '8,920'],
    ['WhatsApp Clicks', '380'],
    ['Call Clicks', '214'],
    ['Bookings', '148'],
    ['Conversion Rate', '8.4%'],
  ];

  return (
    <section className="spm-section" id="analytics">
      <div className="spm-shell spm-split">
        <div>
          <SectionHeading eyebrow="Analytics" title="Know Exactly What's Working" text="Stop guessing. Measure every visitor, every click, and every booking." />
          <div className="spm-lead-flow">
            {['2500 Visitors', '380 WhatsApp Clicks', '140 Leads', '65 Customers'].map((item) => <span key={item}>{item}</span>)}
          </div>
        </div>
        <div className="spm-analytics-card">
          <div className="spm-analytics-preview">
            <img src={templateImages[2]} alt="Spaio website analytics preview" />
            <div>
              <img src="/spaio-logo.png" alt="Spaio logo" />
              <span>Live tenant analytics</span>
            </div>
          </div>
          <div className="spm-metric-grid">
            {metrics.map(([name, value]) => (
              <article key={name}>
                <span>{name}</span>
                <strong>{value}</strong>
              </article>
            ))}
          </div>
        </div>
      </div>
    </section>
  );
}

function Operations() {
  return (
    <section className="spm-section spm-section--soft">
      <div className="spm-shell spm-ops-grid">
        {[
          ['Booking Management', 'Track today, upcoming, pending, confirmed, completed, and cancelled bookings from website, reception, and walk-in customers.', CalendarCheck],
          ['Reception Dashboard', 'Receptionists can manually create sessions and bookings so every real-world interaction stays inside analytics.', Users],
          ['SEO Ready', 'Meta titles, descriptions, clean URLs, automatic sitemap, robots.txt, and fast loading pages help every spa show up on Google.', Search],
          ['Mobile Experience', 'Every tenant website adapts automatically to desktop, tablet, and mobile devices.', MonitorSmartphone],
        ].map(([title, text, Icon]) => (
          <article key={title}>
            <Icon size={26} />
            <h3>{title}</h3>
            <p>{text}</p>
          </article>
        ))}
      </div>
    </section>
  );
}

function Pricing({ plans }) {
  const displayPlans = plans.length ? plans : [
    { id: 'starter', name: 'Starter', monthly_price: 9, features: ['20 Services', '50 Gallery Images', '1 Template', 'Basic Analytics', 'Free Subdomain'] },
    { id: 'pro', name: 'Pro', monthly_price: 19, is_featured: true, features: ['Unlimited Services', 'Unlimited Gallery', 'All Templates', 'Full Analytics', 'SEO Settings', 'Custom Domain'] },
    { id: 'premium', name: 'Premium', monthly_price: 39, features: ['Everything in Pro', 'Multi Language', 'Staff Accounts', 'Advanced Reports', 'Priority Support'] },
  ];

  return (
    <section className="spm-section" id="pricing">
      <div className="spm-shell">
        <SectionHeading eyebrow="Pricing" title="Simple plans for every stage" text="Start small, then unlock full analytics, SEO, templates, staff accounts, and custom domains as your spa grows." />
        <div className="spm-pricing-grid">
          {displayPlans.map((plan) => (
            <article className={plan.is_featured ? 'is-featured' : ''} key={plan.id}>
              {plan.is_featured && <em>Most Popular</em>}
              <h3>{plan.name}</h3>
              <strong>${Number(plan.monthly_price).toFixed(0)}<span>/month</span></strong>
              <ul>
                {(plan.features || []).map((feature) => <li key={feature}><CheckCircle2 size={17} /> {feature}</li>)}
              </ul>
                  <Link className="spm-btn spm-btn--shine" href="/register">Start Free Trial</Link>
            </article>
          ))}
        </div>
      </div>
    </section>
  );
}

function Faq() {
  const [open, setOpen] = useState(0);

  return (
    <section className="spm-section spm-section--soft" id="faq">
      <div className="spm-shell spm-faq-grid">
        <div>
          <SectionHeading eyebrow="FAQ" title="Questions spa owners ask before starting" text="Spaio handles the technical foundation so owners can focus on treatments, customers, and growth." />
          <div className="spm-faq-showcase">
            <img src="/spaio-logo.png" alt="Spaio logo" />
            <div>
              {templateImages.slice(5, 9).map((image, index) => (
                <span key={image} style={{ backgroundImage: `url(${image})`, animationDelay: `${index * 90}ms` }} />
              ))}
            </div>
          </div>
        </div>
        <div className="spm-faq-list">
          {faqs.map(([question, answer], index) => (
            <article className={open === index ? 'is-open' : ''} key={question}>
              <button type="button" onClick={() => setOpen(open === index ? -1 : index)}>
                {question} <ChevronDown size={20} />
              </button>
              {open === index && <p>{answer}</p>}
            </article>
          ))}
        </div>
      </div>
    </section>
  );
}

function Testimonials() {
  return (
    <section className="spm-section">
      <div className="spm-shell">
        <SectionHeading eyebrow="Testimonials" title="Designed for premium service businesses" />
        <div className="spm-testimonial-grid">
          {testimonials.map(([name, business, country, review, rating, photo]) => (
            <article key={name}>
              <div>
                <img src={photo} alt={name} />
                <span>
                  <strong>{business}</strong>
                  <small>{country}</small>
                </span>
              </div>
              <p>{review}</p>
              <footer>{Array.from({ length: rating }).map((_, index) => <Star key={index} size={16} fill="currentColor" />)}</footer>
            </article>
          ))}
        </div>
      </div>
    </section>
  );
}

function RoiCalculator() {
  const [customers, setCustomers] = useState(120);
  const [bookingValue, setBookingValue] = useState(45);
  const [growth, setGrowth] = useState(15);
  const increase = useMemo(() => Math.round(customers * (growth / 100) * bookingValue), [customers, bookingValue, growth]);

  return (
    <section className="spm-section spm-section--dark" id="demo">
      <div className="spm-shell spm-roi">
        <div>
          <SectionHeading eyebrow="ROI calculator" title="Estimate your monthly revenue increase" text="Spaio pays for itself when better booking flow and clearer lead tracking convert more visitors into customers." light />
          <div className="spm-coming-soon">
            <strong>Coming Soon</strong>
            <span>See Your Spa Website Before You Buy</span>
          </div>
        </div>
        <div className="spm-roi-card">
          {[
            ['Monthly Customers', customers, setCustomers, 20, 1000],
            ['Average Booking Value', bookingValue, setBookingValue, 10, 300],
            ['Expected Growth', growth, setGrowth, 1, 80],
          ].map(([label, value, setter, min, max]) => (
            <label key={label}>
              <span>{label}<strong>{label === 'Expected Growth' ? `${value}%` : label === 'Average Booking Value' ? `$${value}` : value}</strong></span>
              <input type="range" min={min} max={max} value={value} onChange={(event) => setter(Number(event.target.value))} />
            </label>
          ))}
          <div>
            <span>Estimated Monthly Revenue Increase</span>
            <strong>${increase.toLocaleString()}</strong>
          </div>
        </div>
      </div>
    </section>
  );
}

function FinalCta() {
  return (
    <section className="spm-final">
      <div className="spm-shell">
        <div>
          <h2>Ready to Grow Your Spa Business?</h2>
          <p>Launch your professional spa website today and start converting visitors into customers.</p>
        </div>
        <div>
          <Link className="spm-btn spm-btn--shine" href="/register">Start Free Trial</Link>
          <a className="spm-btn spm-btn--outline" href="#contact">Book a Demo</a>
        </div>
        <div className="spm-final-previews" aria-label="Spaio template previews">
          {templateImages.slice(0, 6).map((image) => (
            <img src={image} alt="Spaio template preview" key={image} />
          ))}
        </div>
      </div>
    </section>
  );
}

function Footer() {
  const columns = [
    ['Product', 'Features', 'Templates', 'Pricing', 'Blog'],
    ['Support', 'Documentation', 'Contact', 'Privacy Policy', 'Terms of Service'],
    ['Social Media', 'Instagram', 'Facebook', 'LinkedIn', 'YouTube'],
  ];

  return (
    <footer className="spm-footer">
      <div className="spm-shell">
        <div>
          <img src="/spaio-logo.png" alt="Spaio logo" />
          <p>Premium SaaS infrastructure for spa, hammam, massage, wellness, and beauty businesses.</p>
        </div>
        {columns.map(([title, ...items]) => (
          <nav key={title}>
            <strong>{title}</strong>
            {items.map((item) => <a href="/" key={item}>{item}</a>)}
          </nav>
        ))}
      </div>
      <p>Copyright 2026 Spaio. All rights reserved.</p>
    </footer>
  );
}

export default function Home({ plans = [] }) {
  return (
    <>
      <Head title="Launch Your Spa Website in Minutes">
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="" />
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700;800;900&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="/template-kits/main-website-template/assets/css/spaio-main.css" />
      </Head>

      <main className="spm-site">
        <Header />
        <Hero />
        <FreeTrial />
        <Problems />
        <WhySpaio />
        <Templates />
        <Process />
        <Features />
        <Analytics />
        <Operations />
        <Pricing plans={plans} />
        <Faq />
        <Testimonials />
        <RoiCalculator />
        <FinalCta />
        <Footer />
      </main>
    </>
  );
}
