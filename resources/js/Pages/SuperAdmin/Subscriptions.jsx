import { Head, Link, router, useForm } from '@inertiajs/react';
import { CreditCard, Pencil, Plus, Trash2 } from 'lucide-react';
import { useState } from 'react';
import { Button } from '../../Components/Button';
import { Field, TextInput } from '../../Components/Form';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

const emptySubscription = {
  tenant_id: '',
  plan_id: '',
  status: 'trialing',
  billing_cycle: 'monthly',
  current_period_ends_at: '',
};

export default function Subscriptions({ subscriptions, summary, tenants = [], plans = [] }) {
  const [editing, setEditing] = useState(null);
  const form = useForm(emptySubscription);

  function reset() {
    setEditing(null);
    form.setData(emptySubscription);
    form.clearErrors();
  }

  function edit(subscription) {
    setEditing(subscription);
    form.setData({
      tenant_id: subscription.tenant_id,
      plan_id: subscription.plan_id,
      status: subscription.status,
      billing_cycle: subscription.billing_cycle,
      current_period_ends_at: subscription.current_period_ends_at ? subscription.current_period_ends_at.slice(0, 10) : '',
    });
    form.clearErrors();
  }

  function submit(event) {
    event.preventDefault();

    if (editing) {
      form.put(`/admin/subscriptions/${editing.id}`, { preserveScroll: true, onSuccess: reset });
      return;
    }

    form.post('/admin/subscriptions', { preserveScroll: true, onSuccess: reset });
  }

  function destroy(subscription) {
    if (confirm(`Delete subscription for ${subscription.tenant.business_name}?`)) {
      router.delete(`/admin/subscriptions/${subscription.id}`, { preserveScroll: true });
    }
  }

  return (
    <SuperAdminLayout title="Subscriptions" subtitle="Create, update, and delete tenant subscription records.">
      <Head title="Subscriptions" />
      <div className="grid gap-6">
        <section className="grid gap-4 md:grid-cols-4">
          {Object.entries(summary).map(([status, total]) => (
            <article key={status} className="rounded-3xl border border-mist bg-white p-5 shadow-sm">
              <div className="flex items-center justify-between">
                <p className="text-sm font-semibold capitalize text-slate-500">{status.replace('_', ' ')}</p>
                <CreditCard className="h-5 w-5 text-spa-green" />
              </div>
              <p className="mt-5 text-4xl font-semibold text-spa-green">{total}</p>
            </article>
          ))}
        </section>

        <section className="grid gap-6 xl:grid-cols-[0.8fr_1.2fr]">
          <form onSubmit={submit} className="rounded-3xl border border-mist bg-white p-6 shadow-sm">
            <div className="flex items-center justify-between gap-4">
              <div>
                <p className="text-sm font-semibold uppercase tracking-[0.22em] text-spa-gold">{editing ? 'Edit Subscription' : 'Create Subscription'}</p>
                <h2 className="mt-2 text-2xl font-semibold tracking-tight text-spa-green">{editing?.tenant?.business_name ?? 'Tenant billing record'}</h2>
              </div>
              <Button type="button" variant="secondary" onClick={reset}><Plus className="h-4 w-4" /> New</Button>
            </div>
            <div className="mt-6 grid gap-5">
              {!editing && (
                <Field label="Tenant" error={form.errors.tenant_id}>
                  <select className="min-h-12 w-full rounded-lg border border-mist bg-white px-4 text-sm text-charcoal outline-none focus:border-teal-muted focus:ring-4 focus:ring-teal-muted/10" value={form.data.tenant_id} onChange={(event) => form.setData('tenant_id', event.target.value)}>
                    <option value="">Select tenant</option>
                    {tenants.map((tenant) => <option key={tenant.id} value={tenant.id}>{tenant.business_name}</option>)}
                  </select>
                </Field>
              )}
              <Field label="Plan" error={form.errors.plan_id}>
                <select className="min-h-12 w-full rounded-lg border border-mist bg-white px-4 text-sm text-charcoal outline-none focus:border-teal-muted focus:ring-4 focus:ring-teal-muted/10" value={form.data.plan_id} onChange={(event) => form.setData('plan_id', event.target.value)}>
                  <option value="">Select plan</option>
                  {plans.map((plan) => <option key={plan.id} value={plan.id}>{plan.name}</option>)}
                </select>
              </Field>
              <Field label="Status" error={form.errors.status}>
                <select className="min-h-12 w-full rounded-lg border border-mist bg-white px-4 text-sm text-charcoal outline-none focus:border-teal-muted focus:ring-4 focus:ring-teal-muted/10" value={form.data.status} onChange={(event) => form.setData('status', event.target.value)}>
                  <option value="trialing">Trialing</option>
                  <option value="active">Active</option>
                  <option value="past_due">Past due</option>
                  <option value="cancelled">Cancelled</option>
                </select>
              </Field>
              <Field label="Billing cycle" error={form.errors.billing_cycle}>
                <select className="min-h-12 w-full rounded-lg border border-mist bg-white px-4 text-sm text-charcoal outline-none focus:border-teal-muted focus:ring-4 focus:ring-teal-muted/10" value={form.data.billing_cycle} onChange={(event) => form.setData('billing_cycle', event.target.value)}>
                  <option value="monthly">Monthly</option>
                  <option value="yearly">Yearly</option>
                </select>
              </Field>
              <Field label="Current period ends" error={form.errors.current_period_ends_at}>
                <TextInput type="date" value={form.data.current_period_ends_at} onChange={(event) => form.setData('current_period_ends_at', event.target.value)} />
              </Field>
              <Button className="w-fit" loading={form.processing}>{editing ? 'Update Subscription' : 'Create Subscription'}</Button>
            </div>
          </form>

          <div className="rounded-3xl border border-mist bg-white p-6 shadow-sm">
            <div className="overflow-x-auto">
              <table className="w-full text-left text-sm">
                <thead className="text-slate-500">
                  <tr>
                    <th className="py-3">Tenant</th>
                    <th className="py-3">Plan</th>
                    <th className="py-3">Status</th>
                    <th className="py-3">Cycle</th>
                    <th className="py-3 text-right">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  {subscriptions.data.map((subscription) => (
                    <tr key={subscription.id} className="border-t border-mist">
                      <td className="py-4 font-semibold text-spa-green">
                        <Link href={`/admin/tenants/${subscription.tenant.id}`}>{subscription.tenant.business_name}</Link>
                      </td>
                      <td className="py-4 text-slate-600">{subscription.plan?.name ?? 'None'}</td>
                      <td className="py-4"><span className="rounded-full bg-spa-gold-soft px-3 py-1 text-xs font-semibold text-spa-green">{subscription.status}</span></td>
                      <td className="py-4 text-slate-600">{subscription.billing_cycle}</td>
                      <td className="py-4">
                        <div className="flex justify-end gap-2">
                          <button onClick={() => edit(subscription)} className="grid h-10 w-10 place-items-center rounded-xl border border-mist text-slate-600 hover:text-spa-green" aria-label={`Edit subscription ${subscription.id}`}>
                            <Pencil className="h-4 w-4" />
                          </button>
                          <button onClick={() => destroy(subscription)} className="grid h-10 w-10 place-items-center rounded-xl border border-mist text-slate-600 hover:text-red-600" aria-label={`Delete subscription ${subscription.id}`}>
                            <Trash2 className="h-4 w-4" />
                          </button>
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        </section>
      </div>
    </SuperAdminLayout>
  );
}
