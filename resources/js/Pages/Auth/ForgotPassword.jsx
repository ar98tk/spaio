import { Head, useForm, usePage } from '@inertiajs/react';
import { Button } from '../../Components/Button';
import { Field, TextInput } from '../../Components/Form';
import AuthLayout from '../../Layouts/AuthLayout';

export default function ForgotPassword() {
  const { flash } = usePage().props;
  const form = useForm({ email: '' });

  function submit(event) {
    event.preventDefault();
    form.post('/forgot-password');
  }

  return (
    <AuthLayout title="Reset your password" subtitle="Enter your email and Spaio will send a password reset link if mail is configured.">
      <Head title="Forgot password" />
      {flash.success && <div className="mb-5 rounded-lg bg-teal-soft px-4 py-3 text-sm font-semibold text-teal-deep">{flash.success}</div>}
      <form onSubmit={submit} className="space-y-5">
        <Field label="Email address" error={form.errors.email}>
          <TextInput type="email" value={form.data.email} onChange={(e) => form.setData('email', e.target.value)} autoFocus />
        </Field>
        <Button className="w-full" loading={form.processing}>Send reset link</Button>
      </form>
    </AuthLayout>
  );
}
