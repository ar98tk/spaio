export function TemplateCard({ title, subtitle, actions, children, className = '', bodyClassName = '' }) {
  return (
    <section className={`card ${className}`}>
      {(title || subtitle || actions) && (
        <div className="card-header border-0 flex-wrap gap-2">
          <div>
            {title && <h4 className="card-title mb-1">{title}</h4>}
            {subtitle && <p className="mb-0 text-muted">{subtitle}</p>}
          </div>
          {actions && <div className="d-flex flex-wrap gap-2">{actions}</div>}
        </div>
      )}
      <div className={`card-body ${bodyClassName}`}>{children}</div>
    </section>
  );
}

export function TemplateTable({ children, className = '' }) {
  return (
    <div className={`card ${className}`}>
      <div className="card-body table-card-body p-0">
        <div className="table-responsive">{children}</div>
      </div>
    </div>
  );
}

export function TemplateBadge({ tone = 'primary', children, className = '' }) {
  const tones = {
    primary: 'text-bg-primary',
    success: 'text-bg-success',
    warning: 'text-bg-warning',
    danger: 'text-bg-danger',
    muted: 'text-bg-light text-muted',
    info: 'text-bg-info',
  };

  return <span className={`badge badge-sm ${tones[tone] ?? tones.primary} ${className}`}>{children}</span>;
}

export function TemplateModal({ title, children, footer, onClose, className = '' }) {
  return (
    <div className="modal d-block spaio-react-modal" tabIndex="-1" role="dialog" aria-modal="true">
      <button type="button" className="spaio-react-modal__backdrop" onClick={onClose} aria-label="Close modal" />
      <div className={`modal-dialog modal-dialog-centered ${className}`} role="document">
        <div className="modal-content">
          <div className="modal-header">
            <h4 className="modal-title">{title}</h4>
            <button type="button" className="btn-close" onClick={onClose} aria-label="Close" />
          </div>
          <div className="modal-body">{children}</div>
          {footer && <div className="modal-footer">{footer}</div>}
        </div>
      </div>
    </div>
  );
}
