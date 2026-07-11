import { Head } from '@inertiajs/react';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

export default function TenantDetail({ tenant }) {
  return (
    <SuperAdminLayout title={tenant.business_name} subtitle="Tenant profile, owner, subscription, and users.">
      <Head title={tenant.business_name} />
      <div className="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
        <section className="rounded-3xl border border-mist bg-white p-6 shadow-sm">
          <p className="text-sm font-semibold uppercase tracking-[0.22em] text-spa-gold">Tenant profile</p>
          <h2 className="mt-3 text-3xl font-semibold tracking-tight text-spa-green">{tenant.business_name}</h2>
          <dl className="mt-6 grid gap-4 text-sm">
            <div className="rounded-2xl bg-clinical p-4"><dt className="font-semibold text-slate-500">Subdomain</dt><dd className="mt-1 text-charcoal">{tenant.subdomain}.spaio.test</dd></div>
            <div className="rounded-2xl bg-clinical p-4"><dt className="font-semibold text-slate-500">Owner</dt><dd className="mt-1 text-charcoal">{tenant.owner?.email ?? 'No owner'}</dd></div>
            <div className="rounded-2xl bg-clinical p-4"><dt className="font-semibold text-slate-500">Plan</dt><dd className="mt-1 text-charcoal">{tenant.subscription?.plan?.name ?? 'None'}</dd></div>
            <div className="rounded-2xl bg-clinical p-4"><dt className="font-semibold text-slate-500">Status</dt><dd className="mt-1 text-charcoal">{tenant.status}</dd></div>
          </dl>
        </section>
        <section className="rounded-3xl border border-mist bg-white p-6 shadow-sm">
          <h3 className="text-lg font-semibold text-charcoal">Tenant users</h3>
          <div className="mt-5 space-y-3">
            {tenant.users.map((user) => (
              <div key={user.id} className="rounded-2xl border border-mist bg-clinical p-4">
                <p className="font-semibold text-charcoal">{user.name}</p>
                <p className="mt-1 text-sm text-slate-600">{user.email} - {user.role}</p>
              </div>
            ))}
          </div>
        </section>
      </div>
    </SuperAdminLayout>
  );
}
