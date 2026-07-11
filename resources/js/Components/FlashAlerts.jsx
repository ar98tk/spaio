import { useEffect, useState } from 'react';
import { AlertTriangle, CheckCircle2, X } from 'lucide-react';

export function FlashAlerts({ flash = {}, tone = 'tenant' }) {
  const [alert, setAlert] = useState(null);

  useEffect(() => {
    if (flash.success) {
      setAlert({ type: 'success', message: flash.success });
      return;
    }

    if (flash.error) {
      setAlert({ type: 'error', message: flash.error });
    }
  }, [flash.success, flash.error]);

  useEffect(() => {
    if (!alert) return undefined;

    const timeout = window.setTimeout(() => setAlert(null), 5200);

    return () => window.clearTimeout(timeout);
  }, [alert]);

  if (!alert) return null;

  const Icon = alert.type === 'success' ? CheckCircle2 : AlertTriangle;
  const successClass = tone === 'super'
    ? 'alert-success border-spa-gold/30 bg-spa-gold-soft text-spa-green'
    : 'alert-success border-teal-muted/30 bg-teal-soft text-teal-deep';
  const errorClass = 'alert-danger border-red-200 bg-red-50 text-red-700';

  return (
    <div className="pointer-events-none fixed right-5 top-24 z-50 w-[min(420px,calc(100vw-40px))] lg:right-8">
      <div
        role="alert"
        aria-live="polite"
        className={`alert pointer-events-auto flex items-start gap-3 rounded-2xl border px-4 py-3 text-sm font-semibold shadow-premium ${alert.type === 'success' ? successClass : errorClass}`}
      >
        <Icon className="mt-0.5 h-5 w-5 shrink-0" />
        <span className="leading-6">{alert.message}</span>
        <button
          type="button"
          onClick={() => setAlert(null)}
          className="btn-close ml-auto grid h-7 w-7 shrink-0 place-items-center rounded-lg bg-white/55 transition hover:bg-white"
          aria-label="Dismiss alert"
        >
          <X className="h-4 w-4" />
        </button>
      </div>
    </div>
  );
}
