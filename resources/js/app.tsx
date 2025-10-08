// import '../css/app.css';
//import Scss
import '../scss/themes.scss';

import { createInertiaApp } from '@inertiajs/react';
import { configureStore } from '@reduxjs/toolkit';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';
import { Provider } from 'react-redux';
import rootReducer from './slices';
// import { initializeTheme } from './hooks/use-appearance';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
const store = configureStore({ reducer: rootReducer, devTools: true });

// Pre-load glob patterns at module level (runs once)
const tailwindGlob = import.meta.glob('./tailwind/pages/**/*.tsx');
const mainGlob = import.meta.glob('./pages/**/*.tsx');

// Custom resolver with prefix-based resolution
const resolveComponent = (name: string) => {
    // Auth and settings pages use Tailwind directory
    if (
        name.startsWith('auth/') ||
        name.startsWith('dashboard') ||
        name.startsWith('settings/')
    ) {
        const tailwindPath = `./tailwind/pages/${name}.tsx`;
        return resolvePageComponent(tailwindPath, tailwindGlob);
    }

    // All other pages use main pages directory (Bootstrap)
    const mainPath = `./pages/${name}.tsx`;
    return resolvePageComponent(mainPath, mainGlob);
};

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) => resolveComponent(name),
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(
            <Provider store={store}>
                <App {...props} />
            </Provider>,
        );
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on load...
// initializeTheme();
