import { useEffect } from 'react';

const ACTION_PATTERNS = [
  ['call', (href) => href.startsWith('tel:')],
  ['whatsapp', (href) => href.includes('wa.me/') || href.includes('whatsapp.com/')],
  ['location', (href) => href.includes('maps.google.') || href.includes('google.com/maps') || href.includes('maps.app.goo.gl')],
];

function actionTypeFromHref(href) {
  const normalized = String(href || '').toLowerCase();
  const match = ACTION_PATTERNS.find(([, predicate]) => predicate(normalized));

  return match?.[0] || null;
}

function csrfToken() {
  return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

function sendAction(endpoint, payload) {
  if (!endpoint || !payload.action_type) return;

  const body = new FormData();
  body.append('_token', csrfToken());
  Object.entries(payload).forEach(([key, value]) => {
    if (value) body.append(key, String(value).slice(0, key === 'target_url' ? 1000 : 255));
  });

  if (navigator.sendBeacon && navigator.sendBeacon(endpoint, body)) {
    return;
  }

  fetch(endpoint, {
    method: 'POST',
    body,
    credentials: 'same-origin',
    keepalive: true,
    headers: {
      Accept: 'application/json',
    },
  }).catch(() => {});
}

export function useTenantActionTracking(actionEndpoint) {
  useEffect(() => {
    if (!actionEndpoint || typeof document === 'undefined') return undefined;

    const onClick = (event) => {
      const anchor = event.target?.closest?.('a[href]');
      if (!anchor) return;

      const actionType = actionTypeFromHref(anchor.href);
      if (!actionType) return;

      sendAction(actionEndpoint, {
        action_type: actionType,
        context: anchor.dataset.spaioContext || anchor.closest('section')?.id || '',
        label: anchor.getAttribute('aria-label') || anchor.textContent?.trim() || actionType,
        target_url: anchor.href,
      });
    };

    document.addEventListener('click', onClick, true);

    return () => document.removeEventListener('click', onClick, true);
  }, [actionEndpoint]);
}
