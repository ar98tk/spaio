import { Head, Link, useForm } from '@inertiajs/react';
import {
  ArrowLeft,
  ArrowRight,
  BadgeCheck,
  Building2,
  CheckCircle2,
  Globe2,
  ImagePlus,
  LinkIcon,
  LockKeyhole,
  Mail,
  MapPin,
  MessageCircle,
  MousePointerClick,
  Rocket,
  Sparkles,
  UserRound,
} from 'lucide-react';
import { useState } from 'react';
import { PhoneInput } from 'react-international-phone';
import 'react-international-phone/style.css';

const currencies = [
  ['USD', 'USD - US Dollar'],
  ['AED', 'AED - UAE Dirham'],
  ['SAR', 'SAR - Saudi Riyal'],
  ['QAR', 'QAR - Qatari Riyal'],
  ['KWD', 'KWD - Kuwaiti Dinar'],
  ['BHD', 'Bahraini Dinar'],
  ['OMR', 'Omani Rial'],
  ['EGP', 'Egyptian Pound'],
  ['EUR', 'Euro'],
  ['GBP', 'British Pound'],
];

const fallbackTemplates = [
  { id: '', name: 'First Template', preview_image_path: '/default.png', description: 'Dark luxury Arabic spa design.' },
  { id: '', name: 'Second Template', preview_image_path: '/template-kits/second-template/images/section-bg/slider-1.jpg', description: 'Elegant light wellness design.' },
  { id: '', name: 'Ninth Template', preview_image_path: '/template-kits/ninth-template/images/main-slider/slide1.jpg', description: 'Beauty salon and spa design.' },
];

const steps = [
  ['Business', 'Brand and account', Building2],
  ['Channels', 'Contact and socials', MessageCircle],
  ['Template', 'Choose website look', ImagePlus],
  ['Launch', 'Final preferences', Rocket],
];

function fieldComplete(value) {
  return String(value ?? '').trim().length > 0;
}

function Field({ label, error, icon: Icon, children }) {
  return (
    <label className="spa-field">
      <span>{label}</span>
      <div className="spa-input">
        {Icon && <Icon size={18} />}
        {children}
      </div>
      {error && <small>{error}</small>}
    </label>
  );
}

function PhoneField({ label, value, error, onChange }) {
  return (
    <label className="spa-field">
      <span>{label}</span>
      <div className="spa-phone spa-phone--intl">
        <PhoneInput
          defaultCountry="ae"
          value={value}
          onChange={onChange}
          preferredCountries={['ae', 'sa', 'eg', 'qa', 'kw', 'bh', 'om', 'ma', 'tr', 'us', 'gb']}
          forceDialCode
        />
      </div>
      {error && <small>{error}</small>}
    </label>
  );
}

function Toggle({ checked, label, onChange }) {
  return (
    <button type="button" className={`spa-toggle ${checked ? 'is-on' : ''}`} onClick={() => onChange(!checked)}>
      <span>{label}</span>
      <i />
    </button>
  );
}

export default function Register({ templates = [] }) {
  const availableTemplates = templates.length ? templates : fallbackTemplates;
  const [step, setStep] = useState(0);
  const [logoPreview, setLogoPreview] = useState(null);

  const form = useForm({
    business_name: '',
    logo: null,
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    phone: '',
    whatsapp_number: '',
    address: '',
    google_maps_url: '',
    facebook_url: '',
    instagram_url: '',
    snapchat_url: '',
    youtube_url: '',
    x_url: '',
    tiktok_url: '',
    website_template_id: availableTemplates[0]?.id ?? '',
    currency_code: 'AED',
    show_whatsapp_button: true,
    show_call_button: true,
    show_location_button: true,
  });

  const stepIsValid = [
    fieldComplete(form.data.business_name)
      && fieldComplete(form.data.email)
      && fieldComplete(form.data.password)
      && form.data.password === form.data.password_confirmation,
    true,
    fieldComplete(form.data.website_template_id) || availableTemplates.length > 0,
    fieldComplete(form.data.name) && fieldComplete(form.data.currency_code),
  ];

  function goToStep(index) {
    if (index <= step || steps.slice(0, index).every((_, stepIndex) => stepIsValid[stepIndex])) {
      setStep(index);
    }
  }

  function next() {
    if (!stepIsValid[step]) return;
    setStep((value) => Math.min(value + 1, steps.length - 1));
  }

  function back() {
    setStep((value) => Math.max(value - 1, 0));
  }

  function chooseLogo(file) {
    form.setData('logo', file ?? null);
    setLogoPreview(file ? URL.createObjectURL(file) : null);
  }

  function submit(event) {
    event.preventDefault();
    form.post('/register', { forceFormData: true });
  }

  return (
    <>
      <Head title="Start Free Trial">
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="" />
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700;800;900&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="/template-kits/main-website-template/assets/css/spaio-auth.css" />
      </Head>

      <main className="spa-auth spa-register-page spa-register-page--simple">
        <section className="spa-register-simple">
          <header className="spa-register-hero">
            <Link href="/" className="spa-auth__brand"><img src="/spaio-logo.png" alt="Spaio logo" /></Link>
            <div>
              <p><Sparkles size={18} /> 10 days free trial</p>
              <h1>Start your Spaio workspace.</h1>
              <span>Create the tenant foundation in a few focused steps. You can edit everything later from the dashboard.</span>
            </div>
            <Link href="/login">Already have an account?</Link>
          </header>

          <div className="spa-top-stepper" aria-label="Registration steps">
            {steps.map(([title, text, Icon], index) => (
              <button
                type="button"
                className={index === step ? 'is-active' : index < step ? 'is-done' : ''}
                key={title}
                onClick={() => goToStep(index)}
              >
                <span>{index < step ? <CheckCircle2 size={18} /> : <Icon size={18} />}</span>
                <strong>{title}</strong>
                <small>{text}</small>
              </button>
            ))}
          </div>

          <form onSubmit={submit} className="spa-register-card">
            <div className="spa-register-top spa-register-top--simple">
              <div>
                <p>Step {step + 1} of {steps.length}</p>
                <h2>{steps[step][0]}</h2>
              </div>
              <span>{steps[step][1]}</span>
            </div>

            {step === 0 && (
              <div className="spa-step-card">
                <div className="spa-grid-2">
                  <Field label="Business name" error={form.errors.business_name} icon={Building2}>
                    <input value={form.data.business_name} onChange={(event) => form.setData('business_name', event.target.value)} autoFocus />
                  </Field>
                  <Field label="Email address" error={form.errors.email} icon={Mail}>
                    <input type="email" value={form.data.email} onChange={(event) => form.setData('email', event.target.value)} />
                  </Field>
                </div>

                <div className="spa-logo-upload spa-logo-upload--compact">
                  <div>
                    <ImagePlus size={22} />
                    <strong>Logo</strong>
                    <span>Optional. JPG, PNG, or WebP up to 2 MB.</span>
                  </div>
                  <label>
                    {logoPreview ? <img src={logoPreview} alt="Logo preview" /> : <span>Upload logo</span>}
                    <input type="file" accept=".jpg,.jpeg,.png,.webp" onChange={(event) => chooseLogo(event.target.files?.[0])} />
                  </label>
                  {form.errors.logo && <small>{form.errors.logo}</small>}
                </div>

                <PhoneField label="Business phone, optional" value={form.data.phone} error={form.errors.phone} onChange={(value) => form.setData('phone', value)} />

                <div className="spa-grid-2">
                  <Field label="Password" error={form.errors.password} icon={LockKeyhole}>
                    <input type="password" value={form.data.password} onChange={(event) => form.setData('password', event.target.value)} />
                  </Field>
                  <Field label="Confirm password" error={form.errors.password_confirmation} icon={LockKeyhole}>
                    <input type="password" value={form.data.password_confirmation} onChange={(event) => form.setData('password_confirmation', event.target.value)} />
                  </Field>
                </div>
              </div>
            )}

            {step === 1 && (
              <div className="spa-step-card">
                <div className="spa-grid-2">
                  <PhoneField label="WhatsApp number, optional" value={form.data.whatsapp_number} error={form.errors.whatsapp_number} onChange={(value) => form.setData('whatsapp_number', value)} />
                  <Field label="Google Maps URL" error={form.errors.google_maps_url} icon={MapPin}>
                    <input type="url" value={form.data.google_maps_url} onChange={(event) => form.setData('google_maps_url', event.target.value)} placeholder="https://maps.google.com/..." />
                  </Field>
                </div>

                <Field label="Address, optional" error={form.errors.address} icon={MapPin}>
                  <input value={form.data.address} onChange={(event) => form.setData('address', event.target.value)} placeholder="Dubai Marina, Dubai..." />
                </Field>

                <div className="spa-grid-2">
                  {[
                    ['Facebook URL', 'facebook_url'],
                    ['Instagram URL', 'instagram_url'],
                    ['Snapchat URL', 'snapchat_url'],
                    ['YouTube URL', 'youtube_url'],
                    ['X URL', 'x_url'],
                    ['TikTok URL', 'tiktok_url'],
                  ].map(([label, key]) => (
                    <Field label={label} error={form.errors[key]} icon={LinkIcon} key={key}>
                      <input type="url" value={form.data[key]} onChange={(event) => form.setData(key, event.target.value)} placeholder="https://..." />
                    </Field>
                  ))}
                </div>
              </div>
            )}

            {step === 2 && (
              <div className="spa-step-card">
                <div className="spa-template-picker spa-template-picker--simple">
                  {availableTemplates.map((template) => (
                    <button
                      type="button"
                      className={String(form.data.website_template_id) === String(template.id) ? 'is-selected' : ''}
                      key={`${template.id}-${template.slug}`}
                      onClick={() => form.setData('website_template_id', template.id)}
                    >
                      <img src={template.preview_image_path || '/default.png'} alt={template.name} />
                      <span>
                        <strong>{template.name}</strong>
                        <small>{template.description || 'Premium Spaio website template.'}</small>
                      </span>
                      <i><BadgeCheck size={18} /></i>
                    </button>
                  ))}
                </div>
                {form.errors.website_template_id && <small className="spa-error">{form.errors.website_template_id}</small>}
              </div>
            )}

            {step === 3 && (
              <div className="spa-step-card">
                <div className="spa-grid-2">
                  <Field label="Owner name" error={form.errors.name} icon={UserRound}>
                    <input value={form.data.name} onChange={(event) => form.setData('name', event.target.value)} />
                  </Field>
                  <label className="spa-field">
                    <span>Website currency</span>
                    <div className="spa-input">
                      <Globe2 size={18} />
                      <select value={form.data.currency_code} onChange={(event) => form.setData('currency_code', event.target.value)}>
                        {currencies.map(([value, label]) => <option value={value} key={value}>{label}</option>)}
                      </select>
                    </div>
                    {form.errors.currency_code && <small>{form.errors.currency_code}</small>}
                  </label>
                </div>

                <div className="spa-toggle-grid">
                  <Toggle checked={form.data.show_whatsapp_button} label="Show WhatsApp buttons" onChange={(value) => form.setData('show_whatsapp_button', value)} />
                  <Toggle checked={form.data.show_call_button} label="Show Call buttons" onChange={(value) => form.setData('show_call_button', value)} />
                  <Toggle checked={form.data.show_location_button} label="Show Location buttons" onChange={(value) => form.setData('show_location_button', value)} />
                </div>

                <div className="spa-launch-summary">
                  <strong>Your website starts at:</strong>
                  <span>{form.data.business_name ? form.data.business_name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '') : 'your-spa'}.spaio.test</span>
                </div>
              </div>
            )}

            <div className="spa-register-actions">
              <button type="button" className="spa-auth-btn spa-auth-btn--light" onClick={back} disabled={step === 0}>
                <ArrowLeft size={18} /> Back
              </button>
              {step < steps.length - 1 ? (
                <button type="button" className="spa-auth-btn" onClick={next} disabled={!stepIsValid[step]}>
                  Continue <ArrowRight size={18} />
                </button>
              ) : (
                <button type="submit" className="spa-auth-btn" disabled={form.processing || !stepIsValid[step]}>
                  {form.processing ? 'Creating workspace...' : 'Create workspace'} <ArrowRight size={18} />
                </button>
              )}
            </div>
          </form>
        </section>
      </main>
    </>
  );
}
