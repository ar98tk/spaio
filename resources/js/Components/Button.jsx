import { Link } from '@inertiajs/react';
import { Loader2 } from 'lucide-react';

const base = 'btn inline-flex min-h-12 items-center justify-center gap-2 rounded-[20px] px-10 py-4 text-[18px] font-medium leading-6 transition focus:outline-none focus:ring-[3px] focus:ring-teal-muted/20 focus:ring-offset-0 disabled:cursor-not-allowed disabled:opacity-70';
const variants = {
  primary: 'btn-primary bg-teal-muted text-white hover:bg-teal-deep hover:shadow-[rgba(25,55,101,0.08)_0px_4px_16px_0px] active:bg-teal-deep active:shadow-[rgba(25,55,101,0.12)_0px_2px_8px_0px]',
  secondary: 'btn-light border border-[#D5D9E0] bg-clinical text-charcoal hover:border-slate-tertiary hover:bg-[#FCFCFD] active:bg-teal-soft',
  ghost: 'btn-outline-primary border border-teal-muted bg-transparent text-teal-muted hover:border-teal-deep hover:bg-teal-muted/8 active:bg-teal-muted/12',
  danger: 'btn-danger border border-red-200 bg-red-50 text-red-700 hover:bg-red-100',
};

export function Button({ as = 'button', href, variant = 'primary', loading = false, children, className = '', ...props }) {
  const classes = `${base} ${variants[variant]} ${className}`;

  if (as === 'link') {
    return (
      <Link href={href} className={classes} {...props}>
        {children}
      </Link>
    );
  }

  return (
    <button className={classes} disabled={loading || props.disabled} {...props}>
      {loading && <Loader2 className="h-4 w-4 animate-spin" />}
      {children}
    </button>
  );
}
