import { Brand } from '../Components/Brand';

export default function AuthLayout({ title, subtitle, children }) {
  return (
    <div className="min-h-screen bg-clinical">
      <div className="mx-auto grid min-h-screen max-w-7xl lg:grid-cols-[0.9fr_1.1fr]">
        <aside className="hidden border-r border-mist bg-white px-10 py-12 lg:flex lg:flex-col">
          <Brand />
          <div className="mt-auto">
            <div className="overflow-hidden rounded-2xl">
              <img
                src="https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=1200&q=80"
                alt="Minimal spa treatment room"
                className="h-80 w-full object-cover"
              />
            </div>
            <p className="mt-8 max-w-md text-3xl font-semibold leading-tight tracking-tight text-charcoal">
              Build a premium spa SaaS foundation before the website builder begins.
            </p>
          </div>
        </aside>
        <section className="flex items-center justify-center px-5 py-10">
          <div className="w-full max-w-md rounded-2xl border border-mist bg-white p-8 shadow-soft">
            <div className="mb-8 lg:hidden">
              <Brand />
            </div>
            <p className="text-sm font-semibold uppercase tracking-[0.24em] text-teal-muted">Spaio</p>
            <h1 className="mt-3 text-3xl font-semibold tracking-tight text-charcoal">{title}</h1>
            <p className="mt-3 text-sm leading-6 text-slate-600">{subtitle}</p>
            <div className="mt-8">{children}</div>
          </div>
        </section>
      </div>
    </div>
  );
}
