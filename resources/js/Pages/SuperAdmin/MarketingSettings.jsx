import { Head, useForm } from '@inertiajs/react';
import { FileSliders } from 'lucide-react';
import { Button } from '../../Components/Button';
import { Field, TextInput } from '../../Components/Form';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

export default function MarketingSettings({ settings = {} }) {
  const form = useForm({
    hero_title: settings.hero_title ?? 'Launch Your Spa Website in Minutes',
    hero_subtitle: settings.hero_subtitle ?? 'Create a beautiful spa website, accept bookings, track every visitor, and grow your business-all from one platform.',
    primary_cta: settings.primary_cta ?? 'Start Free Trial',
    secondary_cta: settings.secondary_cta ?? 'Watch Demo',
    footer_note: settings.footer_note ?? 'Premium SaaS platform for spa, hammam, massage, wellness, and beauty businesses.',
  });

  function submit(event) {
    event.preventDefault();
    form.put('/admin/marketing-settings');
  }

  return (
    <SuperAdminLayout title="Website Settings" subtitle="Manage core copy for the main Spaio marketing website.">
      <Head title="Website Settings" />
      <section className="grid gap-6 xl:grid-cols-[1fr_0.8fr]">
        <form onSubmit={submit} className="rounded-3xl border border-mist bg-white p-6 shadow-sm">
          <div className="flex items-center gap-3">
            <span className="grid h-12 w-12 place-items-center rounded-2xl bg-spa-gold-soft text-spa-green">
              <FileSliders className="h-5 w-5" />
            </span>
            <div>
              <h2 className="text-lg font-semibold text-charcoal">Marketing content</h2>
              <p className="mt-1 text-sm text-slate-500">These settings are stored for the main SaaS website.</p>
            </div>
          </div>
          <div className="mt-6 grid gap-5">
            <Field label="Hero title" error={form.errors.hero_title}>
              <TextInput value={form.data.hero_title} onChange={(event) => form.setData('hero_title', event.target.value)} />
            </Field>
            <Field label="Hero subtitle" error={form.errors.hero_subtitle}>
              <TextInput value={form.data.hero_subtitle} onChange={(event) => form.setData('hero_subtitle', event.target.value)} />
            </Field>
            <Field label="Primary CTA" error={form.errors.primary_cta}>
              <TextInput value={form.data.primary_cta} onChange={(event) => form.setData('primary_cta', event.target.value)} />
            </Field>
            <Field label="Secondary CTA" error={form.errors.secondary_cta}>
              <TextInput value={form.data.secondary_cta} onChange={(event) => form.setData('secondary_cta', event.target.value)} />
            </Field>
            <Field label="Footer note" error={form.errors.footer_note}>
              <TextInput value={form.data.footer_note} onChange={(event) => form.setData('footer_note', event.target.value)} />
            </Field>
            <Button className="w-fit" loading={form.processing}>Save Website Settings</Button>
          </div>
        </form>

        <aside className="rounded-3xl bg-spa-green p-7 text-white shadow-premium">
          <p className="text-sm font-semibold uppercase tracking-[0.22em] text-spa-gold">Preview</p>
          <h2 className="mt-5 text-4xl font-semibold tracking-tight">{form.data.hero_title}</h2>
          <p className="mt-4 text-sm leading-6 text-slate-200">{form.data.hero_subtitle}</p>
          <div className="mt-8 flex flex-wrap gap-3">
            <span className="rounded-xl bg-white px-4 py-3 text-sm font-semibold text-spa-green">{form.data.primary_cta}</span>
            <span className="rounded-xl border border-white/20 px-4 py-3 text-sm font-semibold">{form.data.secondary_cta}</span>
          </div>
        </aside>
      </section>
    </SuperAdminLayout>
  );
}
