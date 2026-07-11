import { Head, Link, router, useForm } from '@inertiajs/react';
import { Pencil, Plus, Trash2 } from 'lucide-react';
import { useState } from 'react';
import { Button } from '../../Components/Button';
import { Field, TextInput } from '../../Components/Form';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

const emptyTenant = {
  business_name: '',
  subdomain: '',
  status: 'active',
  plan_id: '',
};

export default function Tenants({ tenants, plans = [] }) {
  const [editing, setEditing] = useState(null);
  const form = useForm(emptyTenant);

  function reset() {
    setEditing(null);
    form.setData(emptyTenant);
    form.clearErrors();
  }

  function edit(tenant) {
    setEditing(tenant);
    form.setData({
      business_name: tenant.business_name,
      subdomain: tenant.subdomain,
      status: tenant.status,
      plan_id: tenant.subscription?.plan?.id ?? '',
    });
    form.clearErrors();
  }

  function submit(event) {
    event.preventDefault();

    if (editing) {
      form.put(`/admin/tenants/${editing.id}`, { preserveScroll: true, onSuccess: reset });
      return;
    }

    form.post('/admin/tenants', { preserveScroll: true, onSuccess: reset });
  }

  function destroy(tenant) {
    if (confirm(`Delete tenant ${tenant.business_name}? This also removes related users and subscription records.`)) {
      router.delete(`/admin/tenants/${tenant.id}`, { preserveScroll: true });
    }
  }

  return (
    <SuperAdminLayout title="Tenants" subtitle="Create, update, and delete spa workspaces.">
      <Head title="Tenants" />
      <div className="grid gap-6 xl:grid-cols-[0.8fr_1.2fr]">
        <form onSubmit={submit} className="rounded-3xl border border-mist bg-white p-6 shadow-sm">
          <div className="flex items-center justify-between gap-4">
            <div>
              <p className="text-sm font-semibold uppercase tracking-[0.22em] text-spa-gold">{editing ? 'Edit Tenant' : 'Create Tenant'}</p>
              <h2 className="mt-2 text-2xl font-semibold tracking-tight text-spa-green">{editing?.business_name ?? 'New spa workspace'}</h2>
            </div>
            <Button type="button" variant="secondary" onClick={reset}><Plus className="h-4 w-4" /> New</Button>
          </div>
          <div className="mt-6 grid gap-5">
            <Field label="Business name" error={form.errors.business_name}>
              <TextInput value={form.data.business_name} onChange={(event) => form.setData('business_name', event.target.value)} />
            </Field>
            <Field label="Subdomain" error={form.errors.subdomain}>
              <TextInput value={form.data.subdomain} onChange={(event) => form.setData('subdomain', event.target.value)} placeholder="auto-generated if empty" />
            </Field>
            <Field label="Status" error={form.errors.status}>
              <select className="min-h-12 w-full rounded-lg border border-mist bg-white px-4 text-sm text-charcoal outline-none focus:border-teal-muted focus:ring-4 focus:ring-teal-muted/10" value={form.data.status} onChange={(event) => form.setData('status', event.target.value)}>
                <option value="active">Active</option>
                <option value="paused">Paused</option>
                <option value="cancelled">Cancelled</option>
              </select>
            </Field>
            {!editing && (
              <Field label="Initial plan" error={form.errors.plan_id}>
                <select className="min-h-12 w-full rounded-lg border border-mist bg-white px-4 text-sm text-charcoal outline-none focus:border-teal-muted focus:ring-4 focus:ring-teal-muted/10" value={form.data.plan_id} onChange={(event) => form.setData('plan_id', event.target.value)}>
                  <option value="">No subscription</option>
                  {plans.map((plan) => <option key={plan.id} value={plan.id}>{plan.name}</option>)}
                </select>
              </Field>
            )}
            <Button className="w-fit" loading={form.processing}>{editing ? 'Update Tenant' : 'Create Tenant'}</Button>
          </div>
        </form>

        <section className="rounded-3xl border border-mist bg-white p-6 shadow-sm">
          <div className="overflow-x-auto">
            <table className="w-full text-left text-sm">
              <thead className="text-slate-500">
                <tr>
                  <th className="py-3">Business</th>
                  <th className="py-3">Subdomain</th>
                  <th className="py-3">Owner</th>
                  <th className="py-3">Plan</th>
                  <th className="py-3">Status</th>
                  <th className="py-3 text-right">Actions</th>
                </tr>
              </thead>
              <tbody>
                {tenants.data.map((tenant) => (
                  <tr key={tenant.id} className="border-t border-mist">
                    <td className="py-4 font-semibold text-spa-green"><Link href={`/admin/tenants/${tenant.id}`}>{tenant.business_name}</Link></td>
                    <td className="py-4 text-slate-600">{tenant.subdomain}.spaio.test</td>
                    <td className="py-4 text-slate-600">{tenant.owner?.email ?? 'No owner'}</td>
                    <td className="py-4 text-slate-600">{tenant.subscription?.plan?.name ?? 'None'}</td>
                    <td className="py-4"><span className="rounded-full bg-spa-gold-soft px-3 py-1 text-xs font-semibold text-spa-green">{tenant.status}</span></td>
                    <td className="py-4">
                      <div className="flex justify-end gap-2">
                        <button onClick={() => edit(tenant)} className="grid h-10 w-10 place-items-center rounded-xl border border-mist text-slate-600 hover:text-spa-green" aria-label={`Edit ${tenant.business_name}`}>
                          <Pencil className="h-4 w-4" />
                        </button>
                        <button onClick={() => destroy(tenant)} className="grid h-10 w-10 place-items-center rounded-xl border border-mist text-slate-600 hover:text-red-600" aria-label={`Delete ${tenant.business_name}`}>
                          <Trash2 className="h-4 w-4" />
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </section>
      </div>
    </SuperAdminLayout>
  );
}
