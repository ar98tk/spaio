import { Link } from '@inertiajs/react';

export function Brand({ compact = false, href = '/' }) {
  return (
    <Link href={href} className="flex items-center gap-3">
      <span className="grid h-11 w-11 place-items-center rounded-2xl border border-mist bg-white p-1.5 shadow-sm">
        <img src="/spaio-logo.png" alt="Spaio logo" className="h-full w-full object-contain" />
      </span>
      {!compact && (
        <span>
          <span className="block text-xl font-bold tracking-tight text-spa-green">Spaio</span>
        </span>
      )}
    </Link>
  );
}
