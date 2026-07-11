import { Head } from '@inertiajs/react';
import { CheckCircle2, Sparkles } from 'lucide-react';
import { Button } from '../../Components/Button';
import MarketingLayout from '../../Layouts/MarketingLayout';

export default function Pricing({ plans = [] }) {
  return (
    <MarketingLayout>
      <Head title="Pricing" />
      <section className="premium-gradient py-20">
        <div className="mx-auto max-w-7xl px-5 lg:px-8">
          <div className="mx-auto max-w-3xl text-center">
            <p className="inline-flex rounded-full border border-spa-gold/30 bg-white/80 px-4 py-2 text-xs font-semibold uppercase tracking-[0.22em] text-spa-green">
              Spaio Pricing
            </p>
            <h1 className="mt-6 text-5xl font-semibold tracking-tight text-spa-green md:text-6xl">
              Premium spa websites at simple monthly prices.
            </h1>
            <p className="mt-5 text-lg leading-8 text-slate-600">
              Start with the right plan for your spa business and upgrade when you need more templates, analytics, domains, languages, and staff access.
            </p>
          </div>

          <div className="mt-14 grid gap-6 lg:grid-cols-3">
            {plans.map((plan) => (
              <article
                key={plan.id}
                className={`relative rounded-3xl border p-8 shadow-sm ${plan.is_featured ? 'border-spa-gold bg-white shadow-premium' : 'border-white bg-white/78'}`}
              >
                {plan.is_featured && (
                  <div className="absolute -top-4 left-8 inline-flex items-center gap-2 rounded-full bg-spa-green px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-white">
                    <Sparkles className="h-4 w-4 text-spa-gold" />
                    Most Popular
                  </div>
                )}
                <h2 className="text-3xl font-semibold tracking-tight text-spa-green">{plan.name}</h2>
                <p className="mt-4 min-h-14 text-sm leading-6 text-slate-600">{plan.description}</p>
                <p className="mt-8 text-5xl font-semibold tracking-tight text-charcoal">
                  ${Number(plan.monthly_price).toFixed(0)}
                  <span className="text-base font-medium text-slate-500">/month</span>
                </p>
                <p className="mt-2 text-sm text-slate-500">${Number(plan.yearly_price).toFixed(0)} yearly</p>
                <ul className="mt-8 space-y-4">
                  {plan.features?.map((feature) => (
                    <li key={feature} className="flex gap-3 text-sm text-slate-700">
                      <CheckCircle2 className="h-5 w-5 shrink-0 text-spa-green" />
                      <span>{feature}</span>
                    </li>
                  ))}
                </ul>
                <Button as="link" href="/register" className="mt-9 w-full" variant={plan.is_featured ? 'primary' : 'secondary'}>
                  Start Free Trial
                </Button>
              </article>
            ))}
          </div>
        </div>
      </section>
    </MarketingLayout>
  );
}
