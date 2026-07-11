import { Link, usePage } from '@inertiajs/react';
import { Menu, X } from 'lucide-react';
import { useState } from 'react';
import { Brand } from '../Components/Brand';
import { Button } from '../Components/Button';

export default function MarketingLayout({ children }) {
  const { auth } = usePage().props;
  const [open, setOpen] = useState(false);
  const links = [
    ['Features', '/#features'],
    ['Templates', '/#templates'],
    ['Analytics', '/#analytics'],
    ['How it works', '/#how-it-works'],
    ['Pricing', '/pricing'],
  ];

  return (
    <div className="min-h-screen bg-white text-charcoal">
      <header className="sticky top-0 z-40 border-b border-[#D5D9E0]/70 bg-white/92 backdrop-blur-xl shadow-[rgba(17,23,156,0.07)_0px_-13px_50px_3px]">
        <div className="mx-auto flex max-w-[1200px] items-center justify-between px-5 py-4 lg:px-0">
          <Brand />
          <nav className="hidden items-center gap-8 lg:flex">
            {links.map(([label, href]) => (
              <Link key={label} href={href} className="rounded-lg px-3 py-2 text-[18px] font-medium text-slate-primary hover:bg-teal-muted/4 hover:text-charcoal">
                {label}
              </Link>
            ))}
          </nav>
          <div className="hidden items-center gap-3 lg:flex">
            {auth.user ? (
              <Button as="link" href="/panel">Dashboard</Button>
            ) : (
              <>
                <Button as="link" href="/login" variant="secondary">Log in</Button>
                <Button as="link" href="/register">Start free</Button>
              </>
            )}
          </div>
          <button className="rounded-lg p-2 text-charcoal lg:hidden" onClick={() => setOpen(!open)} aria-label="Toggle navigation">
            {open ? <X /> : <Menu />}
          </button>
        </div>
        {open && (
          <div className="border-t border-mist bg-white px-5 py-4 lg:hidden">
            <div className="grid gap-3">
              {links.map(([label, href]) => (
                <Link key={label} href={href} className="rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-mist-light">
                  {label}
                </Link>
              ))}
              <Button as="link" href={auth.user ? '/panel' : '/register'}>{auth.user ? 'Dashboard' : 'Start free'}</Button>
            </div>
          </div>
        )}
      </header>
      <main>{children}</main>
    </div>
  );
}
