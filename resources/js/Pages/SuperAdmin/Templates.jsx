import { Head, router, useForm } from '@inertiajs/react';
import { Eye, EyeOff, LayoutTemplate, Pencil, Plus, Trash2 } from 'lucide-react';
import { useState } from 'react';
import { Button } from '../../Components/Button';
import { Field, TextInput } from '../../Components/Form';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

const emptyTemplate = {
  name: '',
  slug: '',
  component: 'Tenant/FirstTemplate',
  description: '',
  preview_image_path: '/default.png',
  is_active: true,
  sort_order: 10,
};

export default function Templates({ templates = [] }) {
  const [editing, setEditing] = useState(null);
  const form = useForm(emptyTemplate);

  function reset() {
    setEditing(null);
    form.setData(emptyTemplate);
    form.clearErrors();
  }

  function edit(template) {
    setEditing(template);
    form.setData({
      name: template.name,
      slug: template.slug,
      component: template.component,
      description: template.description ?? '',
      preview_image_path: template.preview_image_path ?? '',
      is_active: template.is_active,
      sort_order: template.sort_order,
    });
    form.clearErrors();
  }

  function submit(event) {
    event.preventDefault();

    if (editing) {
      form.put(`/admin/templates/${editing.id}`, { preserveScroll: true, onSuccess: reset });
      return;
    }

    form.post('/admin/templates', { preserveScroll: true, onSuccess: reset });
  }

  function destroy(template) {
    if (confirm(`Delete template "${template.name}"?`)) {
      router.delete(`/admin/templates/${template.id}`, { preserveScroll: true });
    }
  }

  return (
    <SuperAdminLayout title="Templates" subtitle="Name, activate, hide, and manage tenant website templates.">
      <Head title="Templates" />
      <div className="grid gap-6 xl:grid-cols-[0.85fr_1.15fr]">
        <form onSubmit={submit} className="rounded-3xl border border-mist bg-white p-6 shadow-sm">
          <div className="flex items-center justify-between gap-4">
            <div>
              <p className="text-sm font-semibold uppercase tracking-[0.22em] text-spa-gold">{editing ? 'Edit Template' : 'Create Template'}</p>
              <h2 className="mt-2 text-2xl font-semibold tracking-tight text-spa-green">{editing?.name ?? 'Template record'}</h2>
            </div>
            <Button type="button" variant="secondary" onClick={reset}><Plus className="h-4 w-4" /> New</Button>
          </div>

          <div className="mt-6 grid gap-5">
            <Field label="Template name" error={form.errors.name}>
              <TextInput value={form.data.name} onChange={(event) => form.setData('name', event.target.value)} />
            </Field>
            <Field label="Slug" error={form.errors.slug}>
              <TextInput value={form.data.slug} onChange={(event) => form.setData('slug', event.target.value)} placeholder="auto-generated if empty" />
            </Field>
            <Field label="React component" error={form.errors.component}>
              <select className="min-h-12 w-full rounded-lg border border-mist bg-white px-4 text-sm text-charcoal outline-none focus:border-teal-muted focus:ring-4 focus:ring-teal-muted/10" value={form.data.component} onChange={(event) => form.setData('component', event.target.value)}>
                <option value="Tenant/FirstTemplate">Tenant/FirstTemplate</option>
                <option value="Tenant/SecondTemplate">Tenant/SecondTemplate</option>
                <option value="Tenant/ThirdTemplate">Tenant/ThirdTemplate</option>
                <option value="Tenant/FourthTemplate">Tenant/FourthTemplate</option>
                <option value="Tenant/FifthTemplate">Tenant/FifthTemplate</option>
                <option value="Tenant/SixthTemplate">Tenant/SixthTemplate</option>
                <option value="Tenant/SeventhTemplate">Tenant/SeventhTemplate</option>
                <option value="Tenant/EighthTemplate">Tenant/EighthTemplate</option>
                <option value="Tenant/NinthTemplate">Tenant/NinthTemplate</option>
                <option value="Tenant/TenthTemplate">Tenant/TenthTemplate</option>
                <option value="Tenant/EleventhTemplate">Tenant/EleventhTemplate</option>
                <option value="Tenant/TwelfthTemplate">Tenant/TwelfthTemplate</option>
              </select>
            </Field>
            <Field label="Description" error={form.errors.description}>
              <TextInput value={form.data.description} onChange={(event) => form.setData('description', event.target.value)} />
            </Field>
            <Field label="Preview image path" error={form.errors.preview_image_path}>
              <TextInput value={form.data.preview_image_path} onChange={(event) => form.setData('preview_image_path', event.target.value)} placeholder="/default.png" />
            </Field>
            <Field label="Sort order" error={form.errors.sort_order}>
              <TextInput type="number" min="0" value={form.data.sort_order} onChange={(event) => form.setData('sort_order', event.target.value)} />
            </Field>
            <label className="flex items-center gap-3 text-sm font-semibold text-charcoal">
              <input type="checkbox" checked={form.data.is_active} onChange={(event) => form.setData('is_active', event.target.checked)} className="h-4 w-4 accent-spa-green" />
              Active and visible to tenants
            </label>
            <Button className="w-fit" loading={form.processing}>{editing ? 'Update Template' : 'Create Template'}</Button>
          </div>
        </form>

        <section className="grid gap-5">
          {templates.map((template) => (
            <article key={template.id} className="rounded-3xl border border-mist bg-white p-5 shadow-sm">
              <div className="grid gap-5 md:grid-cols-[140px_1fr_auto]">
                <div className="grid h-32 place-items-center overflow-hidden rounded-2xl bg-clinical">
                  {template.preview_image_path ? <img src={template.preview_image_path} alt={template.name} className="h-full w-full object-cover" /> : <LayoutTemplate className="h-8 w-8 text-slate-400" />}
                </div>
                <div>
                  <div className="flex flex-wrap items-center gap-3">
                    <h2 className="text-2xl font-semibold tracking-tight text-spa-green">{template.name}</h2>
                    <span className={`inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold ${template.is_active ? 'bg-spa-gold-soft text-spa-green' : 'bg-slate-100 text-slate-500'}`}>
                      {template.is_active ? <Eye className="h-3.5 w-3.5" /> : <EyeOff className="h-3.5 w-3.5" />}
                      {template.is_active ? 'Active' : 'Hidden'}
                    </span>
                  </div>
                  <p className="mt-2 text-sm leading-6 text-slate-600">{template.description ?? 'No description.'}</p>
                  <div className="mt-4 grid gap-3 text-sm text-slate-600 sm:grid-cols-3">
                    <span><strong className="text-charcoal">Slug:</strong> {template.slug}</span>
                    <span><strong className="text-charcoal">Component:</strong> {template.component}</span>
                    <span><strong className="text-charcoal">Tenants:</strong> {template.tenants_count}</span>
                  </div>
                </div>
                <div className="flex gap-2 md:flex-col">
                  <button onClick={() => edit(template)} className="grid h-10 w-10 place-items-center rounded-xl border border-mist text-slate-600 hover:text-spa-green" aria-label={`Edit ${template.name}`}>
                    <Pencil className="h-4 w-4" />
                  </button>
                  <button onClick={() => destroy(template)} className="grid h-10 w-10 place-items-center rounded-xl border border-mist text-slate-600 hover:text-red-600" aria-label={`Delete ${template.name}`}>
                    <Trash2 className="h-4 w-4" />
                  </button>
                </div>
              </div>
            </article>
          ))}
          {!templates.length && <div className="rounded-3xl border border-dashed border-mist bg-white p-8 text-center text-slate-600">No templates yet.</div>}
        </section>
      </div>
    </SuperAdminLayout>
  );
}
