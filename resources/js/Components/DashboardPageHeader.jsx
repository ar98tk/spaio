export function DashboardPageHeader({ eyebrow, title, description, actions, stats = [] }) {
  return (
    <section className="card spaio-page-header mb-6 overflow-hidden rounded-2xl border border-mist bg-white shadow-[rgba(25,55,101,0.04)_0px_8px_34px_0px]">
      <div className="card-header spaio-page-header__header grid gap-5 bg-gradient-to-br from-white via-clinical to-teal-soft/60 p-6 lg:grid-cols-[1fr_auto] lg:items-end">
        <div>
          {eyebrow && <p className="spaio-page-header__eyebrow text-sm font-semibold uppercase tracking-[0.22em] text-teal-muted">{eyebrow}</p>}
          <h1 className="card-title mt-2 text-3xl font-semibold tracking-tight text-charcoal">{title}</h1>
          {description && <p className="spaio-page-header__description mt-3 max-w-3xl text-sm leading-6 text-slate-600">{description}</p>}
        </div>
        {actions && <div className="spaio-page-header__actions flex flex-wrap gap-3 lg:justify-end">{actions}</div>}
      </div>
      {!!stats.length && (
        <div className="card-body spaio-page-header__stats grid divide-y divide-mist border-t border-mist p-0 sm:grid-cols-3 sm:divide-x sm:divide-y-0">
          {stats.map((stat) => (
            <div key={stat.label} className="spaio-page-header__stat p-5">
              <p className="spaio-page-header__stat-label text-xs font-bold uppercase tracking-[0.16em] text-slate-500">{stat.label}</p>
              <p className="spaio-page-header__stat-value mt-2 text-2xl font-semibold text-charcoal">{stat.value}</p>
              {stat.note && <p className="spaio-page-header__stat-note mt-1 text-sm text-slate-500">{stat.note}</p>}
            </div>
          ))}
        </div>
      )}
    </section>
  );
}
