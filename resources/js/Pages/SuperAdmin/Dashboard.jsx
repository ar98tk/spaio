import { Head, Link } from '@inertiajs/react';
import { ArrowRight, Building2, CreditCard, Tags, Users } from 'lucide-react';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

const statIcons = {
  tenants: Building2,
  users: Users,
  plans: Tags,
  subscriptions: CreditCard,
};

export default function Dashboard({ stats, tenants = [], plans = [], subscriptionsByStatus = {} }) {
  const primaryStats = ['tenants', 'users', 'plans', 'subscriptions'];

  return (
    <SuperAdminLayout title="Platform Overview" subtitle="Manage Spaio website content, plans, subscriptions, tenants, and platform operations.">
      <Head title="Super admin" />
      <div className="grid gap-6">
        <section className="rounded-3xl bg-spa-green p-7 text-white shadow-premium">
          <div className="flex flex-col justify-between gap-8 xl:flex-row xl:items-end">
            <div>
              <p className="text-sm font-semibold uppercase tracking-[0.22em] text-spa-gold">Control Center</p>
              <h2 className="mt-4 max-w-3xl text-4xl font-semibold tracking-tight">Run the Spaio SaaS platform from one premium admin dashboard.</h2>
            </div>
            <div className="grid gap-3 sm:grid-cols-2">
              <Link href="/admin/marketing-settings" className="rounded-2xl bg-white px-5 py-4 text-sm font-semibold text-spa-green">Website Settings</Link>
              <Link href="/admin/subscriptions" className="rounded-2xl bg-white/10 px-5 py-4 text-sm font-semibold text-white">Subscriptions</Link>
            </div>
          </div>
        </section>

        <section className="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
          {primaryStats.map((label) => {
            const Icon = statIcons[label];
            return (
              <article key={label} className="rounded-3xl border border-mist bg-white p-6 shadow-sm">
                <div className="flex items-center justify-between">
                  <p className="text-sm font-semibold capitalize text-slate-500">{label}</p>
                  <span className="grid h-11 w-11 place-items-center rounded-2xl bg-spa-gold-soft text-spa-green"><Icon className="h-5 w-5" /></span>
                </div>
                <p className="mt-6 text-4xl font-semibold text-spa-green">{stats[label]}</p>
              </article>
            );
          })}
        </section>

        <section className="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
          <div className="rounded-3xl border border-mist bg-white p-6 shadow-sm">
            <div className="flex items-center justify-between">
              <div>
                <h2 className="text-lg font-semibold text-charcoal">Recent tenants</h2>
                <p className="mt-1 text-sm text-slate-500">Newest spa businesses registered on Spaio.</p>
              </div>
              <Link href="/admin/tenants" className="inline-flex items-center gap-2 text-sm font-semibold text-spa-green">View all <ArrowRight className="h-4 w-4" /></Link>
            </div>
            <div className="mt-5 overflow-x-auto">
              <table className="w-full text-left text-sm">
                <thead className="text-slate-500">
                  <tr>
                    <th className="py-3">Business</th>
                    <th className="py-3">Owner</th>
                    <th className="py-3">Plan</th>
                    <th className="py-3">Status</th>
                  </tr>
                </thead>
                <tbody>
                  {tenants.map((tenant) => (
                    <tr key={tenant.id} className="border-t border-mist">
                      <td className="py-4 font-semibold"><Link href={`/admin/tenants/${tenant.id}`}>{tenant.business_name}</Link></td>
                      <td className="py-4 text-slate-600">{tenant.owner?.email ?? 'No owner'}</td>
                      <td className="py-4 text-slate-600">{tenant.subscription?.plan?.name ?? 'None'}</td>
                      <td className="py-4"><span className="rounded-full bg-spa-gold-soft px-3 py-1 text-xs font-semibold text-spa-green">{tenant.status}</span></td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>

          <div className="grid gap-6">
            <div className="rounded-3xl border border-mist bg-white p-6 shadow-sm">
              <h2 className="text-lg font-semibold text-charcoal">Plan mix</h2>
              <div className="mt-5 space-y-3">
                {plans.map((plan) => (
                  <div key={plan.id} className="rounded-2xl bg-clinical p-4">
                    <div className="flex items-center justify-between">
                      <span className="font-semibold text-spa-green">{plan.name}</span>
                      <span className="text-sm text-slate-500">${Number(plan.monthly_price).toFixed(0)}/mo</span>
                    </div>
                  </div>
                ))}
              </div>
            </div>
            <div className="rounded-3xl border border-mist bg-white p-6 shadow-sm">
              <h2 className="text-lg font-semibold text-charcoal">Subscription status</h2>
              <div className="mt-5 grid grid-cols-2 gap-3">
                {['trialing', 'active', 'past_due', 'cancelled'].map((status) => (
                  <div key={status} className="rounded-2xl bg-spa-ivory p-4">
                    <p className="text-sm font-semibold capitalize text-slate-500">{status.replace('_', ' ')}</p>
                    <p className="mt-2 text-2xl font-semibold text-spa-green">{subscriptionsByStatus[status] ?? 0}</p>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </section>
      </div>
    </SuperAdminLayout>
  );
}
