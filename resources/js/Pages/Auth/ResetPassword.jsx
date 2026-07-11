import { Head, useForm } from '@inertiajs/react';
import { Button } from '../../Components/Button';
import { Field, TextInput } from '../../Components/Form';
import AuthLayout from '../../Layouts/AuthLayout';

export default function ResetPassword({ token, email }) {
  const form = useForm({
    token,
    email: email ?? '',
    password: '',
    password_confirmation: '',
  });

  function submit(event) {
    event.preventDefault();
    form.post('/reset-password');
  }

  return (
    <AuthLayout title="Choose a new password" subtitle="Set a new password for your Spaio account.">
      <Head title="Reset password" />
      <form onSubmit={submit} className="space-y-5">
        <Field label="Email address" error={form.errors.email}>
          <TextInput type="email" value={form.data.email} onChange={(e) => form.setData('email', e.target.value)} />
        </Field>
        <Field label="New password" error={form.errors.password}>
          <TextInput type="password" value={form.data.password} onChange={(e) => form.setData('password', e.target.value)} />
        </Field>
        <Field label="Confirm new password" error={form.errors.password_confirmation}>
          <TextInput type="password" value={form.data.password_confirmation} onChange={(e) => form.setData('password_confirmation', e.target.value)} />
        </Field>
        <Button className="w-full" loading={form.processing}>Update password</Button>
      </form>
    </AuthLayout>
  );
}
