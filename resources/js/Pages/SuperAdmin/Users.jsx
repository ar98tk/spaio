import { Head, router, useForm } from '@inertiajs/react';
import { Pencil, Plus, Trash2 } from 'lucide-react';
import { useState } from 'react';
import { Button } from '../../Components/Button';
import { Field, TextInput } from '../../Components/Form';
import SuperAdminLayout from '../../Layouts/SuperAdminLayout';

const emptyUser = {
  tenant_id: '',
  name: '',
  email: '',
  role: 'owner',
  password: '',
};

export default function Users({ users, tenants = [] }) {
  const [editing, setEditing] = useState(null);
  const form = useForm(emptyUser);

  function reset() {
    setEditing(null);
    form.setData(emptyUser);
    form.clearErrors();
  }

  function edit(user) {
    setEditing(user);
    form.setData({
      tenant_id: user.tenant_id ?? '',
      name: user.name,
      email: user.email,
      role: user.role,
      password: '',
    });
    form.clearErrors();
  }

  function submit(event) {
    event.preventDefault();

    if (editing) {
      form.put(`/admin/users/${editing.id}`, { preserveScroll: true, onSuccess: reset });
      return;
    }

    form.post('/admin/users', { preserveScroll: true, onSuccess: reset });
  }

  function destroy(user) {
    if (confirm(`Delete user ${user.email}?`)) {
      router.delete(`/admin/users/${user.id}`, { preserveScroll: true });
    }
  }

  return (
    <SuperAdminLayout title="Users" subtitle="Create, update, and delete platform and tenant users.">
      <Head title="Users" />
      <div className="grid gap-6 xl:grid-cols-[0.8fr_1.2fr]">
        <form onSubmit={submit} className="rounded-3xl border border-mist bg-white p-6 shadow-sm">
          <div className="flex items-center justify-between gap-4">
            <div>
              <p className="text-sm font-semibold uppercase tracking-[0.22em] text-spa-gold">{editing ? 'Edit User' : 'Create User'}</p>
              <h2 className="mt-2 text-2xl font-semibold tracking-tight text-spa-green">{editing?.email ?? 'New user account'}</h2>
            </div>
            <Button type="button" variant="secondary" onClick={reset}><Plus className="h-4 w-4" /> New</Button>
          </div>
          <div className="mt-6 grid gap-5">
            <Field label="Name" error={form.errors.name}>
              <TextInput value={form.data.name} onChange={(event) => form.setData('name', event.target.value)} />
            </Field>
            <Field label="Email" error={form.errors.email}>
              <TextInput type="email" value={form.data.email} onChange={(event) => form.setData('email', event.target.value)} />
            </Field>
            <Field label="Role" error={form.errors.role}>
              <select className="min-h-12 w-full rounded-lg border border-mist bg-white px-4 text-sm text-charcoal outline-none focus:border-teal-muted focus:ring-4 focus:ring-teal-muted/10" value={form.data.role} onChange={(event) => form.setData('role', event.target.value)}>
                <option value="owner">Owner</option>
                <option value="staff">Staff</option>
                <option value="super_admin">Super Admin</option>
              </select>
            </Field>
            {form.data.role !== 'super_admin' && (
              <Field label="Tenant" error={form.errors.tenant_id}>
                <select className="min-h-12 w-full rounded-lg border border-mist bg-white px-4 text-sm text-charcoal outline-none focus:border-teal-muted focus:ring-4 focus:ring-teal-muted/10" value={form.data.tenant_id} onChange={(event) => form.setData('tenant_id', event.target.value)}>
                  <option value="">Select tenant</option>
                  {tenants.map((tenant) => <option key={tenant.id} value={tenant.id}>{tenant.business_name}</option>)}
                </select>
              </Field>
            )}
            <Field label={editing ? 'New password, optional' : 'Password'} error={form.errors.password}>
              <TextInput type="password" value={form.data.password} onChange={(event) => form.setData('password', event.target.value)} />
            </Field>
            <Button className="w-fit" loading={form.processing}>{editing ? 'Update User' : 'Create User'}</Button>
          </div>
        </form>

        <section className="rounded-3xl border border-mist bg-white p-6 shadow-sm">
          <div className="overflow-x-auto">
            <table className="w-full text-left text-sm">
              <thead className="text-slate-500">
                <tr>
                  <th className="py-3">Name</th>
                  <th className="py-3">Email</th>
                  <th className="py-3">Role</th>
                  <th className="py-3">Tenant</th>
                  <th className="py-3 text-right">Actions</th>
                </tr>
              </thead>
              <tbody>
                {users.data.map((user) => (
                  <tr key={user.id} className="border-t border-mist">
                    <td className="py-4 font-semibold text-charcoal">{user.name}</td>
                    <td className="py-4 text-slate-600">{user.email}</td>
                    <td className="py-4"><span className="rounded-full bg-spa-gold-soft px-3 py-1 text-xs font-semibold text-spa-green">{user.role}</span></td>
                    <td className="py-4 text-slate-600">{user.tenant?.business_name ?? 'Platform'}</td>
                    <td className="py-4">
                      <div className="flex justify-end gap-2">
                        <button onClick={() => edit(user)} className="grid h-10 w-10 place-items-center rounded-xl border border-mist text-slate-600 hover:text-spa-green" aria-label={`Edit ${user.email}`}>
                          <Pencil className="h-4 w-4" />
                        </button>
                        <button onClick={() => destroy(user)} className="grid h-10 w-10 place-items-center rounded-xl border border-mist text-slate-600 hover:text-red-600" aria-label={`Delete ${user.email}`}>
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
