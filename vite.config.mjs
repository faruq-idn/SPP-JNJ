import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/sass/app.scss', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        chunkSizeWarningLimit: 1000,
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true,
                pure_funcs: ['console.log'],
                passes: 3,
                unsafe: true,
                unsafe_math: true
            },
            mangle: {
                toplevel: true
            }
        },
        rollupOptions: {
            output: {
                manualChunks: {
                    'vendor': [
                        'axios',
                        'lodash',
                        '@popperjs/core'
                    ]
                }
            }
        }
    },
    server: {
        fs: {
            allow: ['public/vendor', 'resources'],
        },
        host: true,
        hmr: {
            host: 'localhost'
        },
        watch: {
            usePolling: true
        }
    }
});