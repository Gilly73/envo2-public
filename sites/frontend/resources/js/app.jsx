import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { MultiStepFormProvider } from './context/MultiStepFormContext.jsx';
import { Elements } from '@stripe/react-stripe-js';
import { loadStripe } from '@stripe/stripe-js';



const queryClient = new QueryClient();

createInertiaApp({
    // Use Vite's import.meta.glob to load all page components.
    // resolve: (name) => {
    //   // This will create an object mapping file paths to modules.
    //   const pages = import.meta.glob('./Pages/**/*.jsx', { eager: true });

    //   // Access the page component using the resolved file path.
    //   const page = pages[`./Pages/${name}.jsx`];
    //   if (!page) {
    //     throw new Error(`Page not found: ${name}`);
    //   }
    //   return page;
    // },
    resolve: (name) => resolvePageComponent(`./Pages/${name}.jsx`, import.meta.glob('./Pages/**/*.jsx')),
    setup({ el, App, props }) {
        const stripePromise = loadStripe(props.initialPage.props.stripeKey);
        createRoot(el).render(
            <QueryClientProvider client={queryClient}>
                <Elements stripe={stripePromise}>
                    <MultiStepFormProvider>
                        <App {...props} />
                    </MultiStepFormProvider>
                </Elements>
            </QueryClientProvider>
        );
    },
});