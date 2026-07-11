import { Head } from '@inertiajs/react';
import { Settings, ShieldCheck } from 'lucide-react';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

export default function PlatformSettings() {
  const items = [
    ['Registration', 'Open registration is enabled for spa owners.'],
    ['Tenancy', 'Single database isolation with tenant_id.'],
    ['Subdomains', 'Default subdomain is generated from business name.'],
    ['Payments', 'Gateway integration is reserved for a later hardening phase.'],
    ['Custom domains', 'Reserved for future DNS and SSL workflows.'],
  ];

  return (
    <SuperAdminLayout title="Platform Settings" subtitle="Operational settings and roadmap controls for Spaio.">
      <Head title="Platform Settings" />
      <div className="grid gap-6 xl:grid-cols-[0.8fr_1.2fr]">
        <section className="rounded-3xl bg-spa-green p-7 text-white shadow-premium">
          <Settings className="h-7 w-7 text-spa-gold" />
          <h2 className="mt-5 text-4xl font-semibold tracking-tight">SaaS infrastructure controls</h2>
          <p className="mt-4 text-sm leading-6 text-slate-200">
            This area reserves platform-wide settings before adding payment gateways, custom domain automation, and production operations.
          </p>
        </section>
        <section className="grid gap-4">
          {items.map(([title, body]) => (
            <article key={title} className="flex gap-4 rounded-3xl border border-mist bg-white p-5 shadow-sm">
              <span className="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-spa-gold-soft text-spa-green">
                <ShieldCheck className="h-5 w-5" />
              </span>
              <div>
                <h3 className="font-semibold text-charcoal">{title}</h3>
                <p className="mt-1 text-sm leading-6 text-slate-600">{body}</p>
              </div>
            </article>
          ))}
        </section>
      </div>
    </SuperAdminLayout>
  );
}
