import { Link, router, usePage } from '@inertiajs/react';
import { Building2, CreditCard, FileSliders, LayoutDashboard, LayoutTemplate, LogOut, Settings, Sparkles, Tags, Users } from 'lucide-react';
import { Brand } from '../Components/Brand';
import { FlashAlerts } from '../Components/FlashAlerts';

const nav = [
  ['Overview', '/admin', LayoutDashboard],
  ['Tenants', '/admin/tenants', Building2],
  ['Users', '/admin/users', Users],
  ['Plans', '/admin/plans', Tags],
  ['Subscriptions', '/admin/subscriptions', CreditCard],
  ['Templates', '/admin/templates', LayoutTemplate],
  ['Website Settings', '/admin/marketing-settings', FileSliders],
  ['Platform Settings', '/admin/platform-settings', Settings],
];

export default function SuperAdminLayout({ title, subtitle, children }) {
  const { auth, flash } = usePage().props;
  const path = window.location.pathname;

  return (
    <div className="min-h-screen bg-clinical text-charcoal">
      <aside className="fixed inset-y-0 left-0 hidden w-80 border-r border-mist bg-white px-5 py-5 lg:block">
        <Brand href="/admin" />
        <div className="mt-8 rounded-3xl bg-spa-green p-5 text-white shadow-premium">
          <div className="flex items-center gap-3">
            <span className="grid h-10 w-10 place-items-center rounded-xl bg-white/10">
              <Sparkles className="h-5 w-5 text-spa-gold" />
            </span>
            <div>
              <p className="text-sm font-semibold">Spaio Control Center</p>
              <p className="text-xs text-slate-300">Platform operations</p>
            </div>
          </div>
        </div>
        <nav className="mt-6 space-y-1">
          {nav.map(([label, href, Icon]) => {
            const active = path === href;
            return (
              <Link
                key={label}
                href={href}
                className={`flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition ${active ? 'bg-spa-green text-white shadow-sm' : 'text-slate-600 hover:bg-spa-ivory hover:text-spa-green'}`}
              >
                <Icon className="h-4 w-4" />
                {label}
              </Link>
            );
          })}
        </nav>
        <Link href="/dashboard" className="mt-5 block rounded-xl border border-mist px-3 py-3 text-sm font-semibold text-slate-600 hover:border-spa-gold hover:text-spa-green">
          Tenant dashboard
        </Link>
      </aside>

      <div className="lg:pl-80">
        <header className="sticky top-0 z-20 border-b border-mist bg-white/86 px-5 py-4 backdrop-blur-xl lg:px-8">
          <div className="flex items-center justify-between gap-4">
            <div>
              <p className="text-xs font-semibold uppercase tracking-[0.22em] text-spa-gold">Super Admin</p>
              <h1 className="mt-1 text-2xl font-semibold tracking-tight text-spa-green">{title}</h1>
              {subtitle && <p className="mt-1 text-sm text-slate-500">{subtitle}</p>}
            </div>
            <div className="flex items-center gap-3">
              <span className="hidden text-right text-sm text-slate-600 sm:block">
                <span className="block font-semibold text-charcoal">{auth.user?.name}</span>
                <span>{auth.user?.email}</span>
              </span>
              <button onClick={() => router.post('/logout')} className="grid h-11 w-11 place-items-center rounded-xl border border-mist bg-white text-slate-600 hover:text-spa-green" aria-label="Log out">
                <LogOut className="h-4 w-4" />
              </button>
            </div>
          </div>
        </header>
        <FlashAlerts flash={flash} tone="super" />
        <main className="px-5 py-6 lg:px-8">{children}</main>
      </div>
    </div>
  );
}
