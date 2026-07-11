import { Head, router, useForm } from '@inertiajs/react';
import { CheckCircle2, Pencil, Plus, Tags, Trash2 } from 'lucide-react';
import { useState } from 'react';
import { Button } from '../../Components/Button';
import { Field, TextInput } from '../../Components/Form';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

const emptyPlan = {
  name: '',
  slug: '',
  description: '',
  monthly_price: '',
  yearly_price: '',
  features_text: '',
  is_featured: false,
  sort_order: 10,
};

export default function Plans({ plans = [] }) {
  const [editing, setEditing] = useState(null);
  const form = useForm(emptyPlan);

  function startCreate() {
    setEditing(null);
    form.setData(emptyPlan);
    form.clearErrors();
  }

  function startEdit(plan) {
    setEditing(plan);
    form.setData({
      name: plan.name,
      slug: plan.slug,
      description: plan.description,
      monthly_price: plan.monthly_price,
      yearly_price: plan.yearly_price,
      features_text: plan.features?.join('\n') ?? '',
      is_featured: plan.is_featured,
      sort_order: plan.sort_order,
    });
    form.clearErrors();
  }

  function submit(event) {
    event.preventDefault();

    if (editing) {
      form.put(`/admin/plans/${editing.id}`, { preserveScroll: true, onSuccess: startCreate });
      return;
    }

    form.post('/admin/plans', { preserveScroll: true, onSuccess: startCreate });
  }

  function destroy(plan) {
    if (confirm(`Delete ${plan.name}?`)) {
      router.delete(`/admin/plans/${plan.id}`, { preserveScroll: true });
    }
  }

  return (
    <SuperAdminLayout title="Plans" subtitle="Create, update, and delete Spaio pricing tiers.">
      <Head title="Plans" />
      <div className="grid gap-6 xl:grid-cols-[0.9fr_1.1fr]">
        <form onSubmit={submit} className="rounded-3xl border border-mist bg-white p-6 shadow-sm">
          <div className="flex items-center justify-between gap-4">
            <div>
              <p className="text-sm font-semibold uppercase tracking-[0.22em] text-spa-gold">{editing ? 'Edit Plan' : 'Create Plan'}</p>
              <h2 className="mt-2 text-2xl font-semibold tracking-tight text-spa-green">{editing?.name ?? 'New pricing plan'}</h2>
            </div>
            <Button type="button" variant="secondary" onClick={startCreate}>
              <Plus className="h-4 w-4" /> New
            </Button>
          </div>

          <div className="mt-6 grid gap-5">
            <Field label="Name" error={form.errors.name}>
              <TextInput value={form.data.name} onChange={(event) => form.setData('name', event.target.value)} />
            </Field>
            <Field label="Slug" error={form.errors.slug}>
              <TextInput value={form.data.slug} onChange={(event) => form.setData('slug', event.target.value)} placeholder="auto-generated if empty" />
            </Field>
            <Field label="Description" error={form.errors.description}>
              <TextInput value={form.data.description} onChange={(event) => form.setData('description', event.target.value)} />
            </Field>
            <div className="grid gap-5 sm:grid-cols-3">
              <Field label="Monthly price" error={form.errors.monthly_price}>
                <TextInput type="number" min="0" step="0.01" value={form.data.monthly_price} onChange={(event) => form.setData('monthly_price', event.target.value)} />
              </Field>
              <Field label="Yearly price" error={form.errors.yearly_price}>
                <TextInput type="number" min="0" step="0.01" value={form.data.yearly_price} onChange={(event) => form.setData('yearly_price', event.target.value)} />
              </Field>
              <Field label="Sort order" error={form.errors.sort_order}>
                <TextInput type="number" min="0" value={form.data.sort_order} onChange={(event) => form.setData('sort_order', event.target.value)} />
              </Field>
            </div>
            <Field label="Features, one per line" error={form.errors.features_text}>
              <textarea
                className="min-h-36 w-full rounded-lg border border-mist bg-white px-4 py-3 text-sm text-charcoal outline-none transition focus:border-teal-muted focus:ring-4 focus:ring-teal-muted/10"
                value={form.data.features_text}
                onChange={(event) => form.setData('features_text', event.target.value)}
              />
            </Field>
            <label className="flex items-center gap-3 text-sm font-semibold text-charcoal">
              <input type="checkbox" checked={form.data.is_featured} onChange={(event) => form.setData('is_featured', event.target.checked)} className="h-4 w-4 accent-spa-green" />
              Highlight as featured plan
            </label>
            <Button className="w-fit" loading={form.processing}>{editing ? 'Update Plan' : 'Create Plan'}</Button>
          </div>
        </form>

        <div className="grid gap-5">
          {plans.map((plan) => (
            <article key={plan.id} className={`rounded-3xl border bg-white p-6 shadow-sm ${plan.is_featured ? 'border-spa-gold shadow-premium' : 'border-mist'}`}>
              <div className="flex items-start justify-between gap-4">
                <div className="flex gap-4">
                  <span className="grid h-12 w-12 place-items-center rounded-2xl bg-spa-gold-soft text-spa-green">
                    <Tags className="h-5 w-5" />
                  </span>
                  <div>
                    <h2 className="text-2xl font-semibold tracking-tight text-spa-green">{plan.name}</h2>
                    <p className="mt-2 text-sm leading-6 text-slate-600">{plan.description}</p>
                  </div>
                </div>
                <div className="flex gap-2">
                  <button onClick={() => startEdit(plan)} className="grid h-10 w-10 place-items-center rounded-xl border border-mist text-slate-600 hover:text-spa-green" aria-label={`Edit ${plan.name}`}>
                    <Pencil className="h-4 w-4" />
                  </button>
                  <button onClick={() => destroy(plan)} className="grid h-10 w-10 place-items-center rounded-xl border border-mist text-slate-600 hover:text-red-600" aria-label={`Delete ${plan.name}`}>
                    <Trash2 className="h-4 w-4" />
                  </button>
                </div>
              </div>
              <div className="mt-5 grid gap-4 sm:grid-cols-3">
                <div className="rounded-2xl bg-clinical p-4">
                  <p className="text-sm text-slate-500">Monthly</p>
                  <p className="mt-1 text-2xl font-semibold text-charcoal">${Number(plan.monthly_price).toFixed(0)}</p>
                </div>
                <div className="rounded-2xl bg-clinical p-4">
                  <p className="text-sm text-slate-500">Yearly</p>
                  <p className="mt-1 text-2xl font-semibold text-charcoal">${Number(plan.yearly_price).toFixed(0)}</p>
                </div>
                <div className="rounded-2xl bg-clinical p-4">
                  <p className="text-sm text-slate-500">Subscriptions</p>
                  <p className="mt-1 text-2xl font-semibold text-charcoal">{plan.subscriptions_count}</p>
                </div>
              </div>
              <ul className="mt-5 grid gap-2 sm:grid-cols-2">
                {plan.features?.map((feature) => (
                  <li key={feature} className="flex gap-2 text-sm text-slate-700">
                    <CheckCircle2 className="h-4 w-4 shrink-0 text-spa-green" />
                    {feature}
                  </li>
                ))}
              </ul>
            </article>
          ))}
        </div>
      </div>
    </SuperAdminLayout>
  );
}
