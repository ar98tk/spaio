import { Head, Link, useForm } from '@inertiajs/react';
import { ArrowRight, CalendarCheck, LayoutDashboard, LockKeyhole, Mail, ShieldCheck, Sparkles } from 'lucide-react';

const templateImages = [
  '/template-kits/ninth-template/images/main-slider/slide1.jpg',
  '/template-kits/eighth-template/images/home-1/slider/slider-1.jpg',
  '/template-kits/twelfth-template/assets/images/sliders/slide-bg/1.jpg',
];

export default function Login() {
  const form = useForm({
    email: '',
    password: '',
    remember: false,
  });

  function submit(event) {
    event.preventDefault();
    form.post('/login');
  }

  return (
    <>
      <Head title="Log in">
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="" />
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700;800;900&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="/template-kits/main-website-template/assets/css/spaio-auth.css" />
      </Head>

      <main className="spa-auth">
        <section className="spa-auth__shell spa-login">
          <aside className="spa-auth__visual">
            <Link href="/" className="spa-auth__brand"><img src="/spaio-logo.png" alt="Spaio logo" /></Link>
            <div className="spa-auth__visual-copy spa-auth__visual-copy--panel">
              <p><Sparkles size={18} /> Metronic tenant panel</p>
              <h1>One clean panel for daily spa operations.</h1>
              <span>Sign in to manage bookings, reception, services, packages, staff, templates, users, billing, and website settings from the new Livewire workspace.</span>
              <div className="spa-login-metrics">
                <div>
                  <CalendarCheck size={22} />
                  <strong>Live bookings</strong>
                  <small>Website and reception requests in one pipeline.</small>
                </div>
                <div>
                  <LayoutDashboard size={22} />
                  <strong>Metronic UI</strong>
                  <small>Compact operational views built for daily use.</small>
                </div>
              </div>
            </div>
            <div className="spa-auth__stack">
              {templateImages.map((image, index) => (
                <img src={image} alt="Spaio template preview" key={image} style={{ '--delay': `${index * 90}ms` }} />
              ))}
            </div>
          </aside>

          <section className="spa-auth__panel">
            <div className="spa-auth__mobile-brand"><img src="/spaio-logo.png" alt="Spaio logo" /></div>
            <div className="spa-auth__eyebrow"><ShieldCheck size={17} /> Secure panel access</div>
            <h2>Sign in to Spaio</h2>
            <p>Tenant users continue to the new Livewire panel. Super admins continue to the control center automatically.</p>

            <div className="spa-login-destination">
              <span>After login</span>
              <strong>Tenant panel: /panel</strong>
            </div>

            <form onSubmit={submit} className="spa-form">
              <label>
                <span>Email address</span>
                <div className="spa-input">
                  <Mail size={18} />
                  <input type="email" value={form.data.email} onChange={(event) => form.setData('email', event.target.value)} autoFocus />
                </div>
                {form.errors.email && <small>{form.errors.email}</small>}
              </label>

              <label>
                <span>Password</span>
                <div className="spa-input">
                  <LockKeyhole size={18} />
                  <input type="password" value={form.data.password} onChange={(event) => form.setData('password', event.target.value)} />
                </div>
                {form.errors.password && <small>{form.errors.password}</small>}
              </label>

              <div className="spa-form__row">
                <label className="spa-check">
                  <input type="checkbox" checked={form.data.remember} onChange={(event) => form.setData('remember', event.target.checked)} />
                  <span>Remember this device</span>
                </label>
                <Link href="/forgot-password">Forgot password?</Link>
              </div>

              <button className="spa-auth-btn" type="submit" disabled={form.processing}>
                {form.processing ? 'Signing in...' : 'Open panel'} <ArrowRight size={18} />
              </button>
            </form>

            <p className="spa-auth__switch">
              New to Spaio? <Link href="/register">Start free</Link>
            </p>
          </section>
        </section>
      </main>
    </>
  );
}
