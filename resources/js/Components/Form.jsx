export function Field({ label, error, children }) {
  return (
    <label className="block">
      <span className="form-label mb-2 block text-sm font-semibold text-charcoal">{label}</span>
      {children}
      {error && <span className="invalid-feedback mt-2 block text-sm text-red-600">{error}</span>}
    </label>
  );
}

export function TextInput({ className = '', ...props }) {
  return (
    <input
      className={`form-control min-h-12 w-full rounded-lg border border-mist bg-white px-4 text-sm text-charcoal outline-none transition placeholder:text-slate-400 focus:border-teal-muted focus:ring-4 focus:ring-teal-muted/10 ${className}`}
      {...props}
    />
  );
}

export function FileInput({ className = '', ...props }) {
  return (
    <input
      className={`form-control block w-full rounded-lg border border-dashed border-mist bg-white px-4 py-3 text-sm text-slate-600 file:mr-4 file:rounded-md file:border-0 file:bg-mist-light file:px-4 file:py-2 file:text-sm file:font-semibold file:text-charcoal hover:file:bg-teal-soft ${className}`}
      type="file"
      {...props}
    />
  );
}
