import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import fs from 'fs';

export default defineConfig(({ command }) => {
    // When running dev, use the VITE_DEV_SERVER_URL as the public URL
    const devServerUrl = process.env.VITE_DEV_SERVER_URL || 'https://frontend:5173';
// console.log('APP_ENV:', process.env.APP_ENV);
// console.log('VITE_DEV_SERVER_URL:', process.env.VITE_DEV_SERVER_URL);
// console.log('devServerUrl', devServerUrl);
// console.log('command', command);
// console.log(command === 'serve');
// console.log(command === 'serve' ? devServerUrl + '/' : '/build/');
    return {
        // In dev mode, force asset URLs to use the public dev server URL.
        base: command === 'serve' ? devServerUrl + '/' : devServerUrl + '/build/',
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.jsx'],
                refresh: true,
            }),
            react()
        ].filter(Boolean),
        build: {
            manifest: true,
            outDir: "public/build",
            rollupOptions: {
                input: ["resources/css/app.css", "resources/js/app.jsx"],
            },
        },
        server: command === 'serve'
            ? {
                https: {
                    // Option 1: Use your own certificate files that have “frontend” in their SAN.
                    key: fs.readFileSync('/etc/nginx/certs/selfsigned.key'),
                    cert: fs.readFileSync('/etc/nginx/certs/selfsigned.crt'),
                    allowHTTP1: true,
                },
                host: '0.0.0.0', // Listen on all interfaces
                port: 5173,
                strictPort: true,
                cors: true,
                //hmr: false,
                hmr: {
                    host: 'frontend',  // Force HMR to use the public hostname
                    protocol: 'wss',
                    port: 5173,
                }
            }
            : undefined,
    };
});
