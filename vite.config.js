import { defineConfig } from 'vite';
import path from 'path';

export default defineConfig({
    publicDir: false,
    build: {
        outDir: 'public',
        emptyOutDir: false,
        rollupOptions: {
            input: {
                repeater: path.resolve(__dirname, 'resources/js/app.js'),
            },
            output: {
                entryFileNames: 'js/repeater.js',
                assetFileNames: (assetInfo) => {
                    if (assetInfo.name && assetInfo.name.endsWith('.css')) {
                        return 'css/repeater.css';
                    }
                    return 'assets/[name][extname]';
                },
            },
        },
    },
    resolve: {
        alias: {
            '~orchid': path.resolve(__dirname, 'vendor/orchid/platform/resources'),
            'squirrelly': path.resolve(__dirname, 'node_modules/squirrelly/dist/browser/squirrelly.min.js'),
        },
    },
});
