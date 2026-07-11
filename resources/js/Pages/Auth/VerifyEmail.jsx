import { Head, Link, router, usePage } from '@inertiajs/react';
import { ArrowRight, MailCheck, RefreshCw, ShieldCheck, Sparkles } from 'lucide-react';

export default function VerifyEmail({ email }) {
  const { flash = {} } = usePage().props;

  function resend() {
    router.post('/email/verification-notification', {}, { preserveScroll: true });
  }

  return (
    <>
      <Head title="Verify email">
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="" />
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700;800;900&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="/template-kits/main-website-template/assets/css/spaio-auth.css" />
      </Head>

      <main className="spa-auth">
        <section className="spa-auth__shell spa-login">
          <aside className="spa-auth__visual">
            <Link href="/" className="spa-auth__brand"><img src="/spaio-logo.png" alt="Spaio logo" /></Link>
            <div className="spa-auth__visual-copy">
              <p><Sparkles size={18} /> Secure Spaio onboarding</p>
              <h1>One final step before your dashboard opens.</h1>
              <span>We verify every owner account so bookings, billing, and business settings stay protected.</span>
            </div>
          </aside>

          <section className="spa-auth__panel">
            <div className="spa-auth__mobile-brand"><img src="/spaio-logo.png" alt="Spaio logo" /></div>
            <div className="spa-auth__eyebrow"><ShieldCheck size={17} /> Email verification required</div>
            <h2>Check your inbox</h2>
            <p>We sent a verification link to <strong>{email}</strong>. Click the link to activate your Spaio dashboard access.</p>

            {flash.success && <div className="spa-auth__notice"><MailCheck size={18} /> {flash.success}</div>}

            <div className="spa-form">
              <button className="spa-auth-btn" type="button" onClick={resend}>
                Resend verification email <RefreshCw size={18} />
              </button>
              <Link className="spa-auth-btn spa-auth-btn--secondary" href="/logout" method="post" as="button">
                Use another account <ArrowRight size={18} />
              </Link>
            </div>
          </section>
        </section>
      </main>
    </>
  );
}
