import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue2';
import vitePluginRequire from "vite-plugin-require";


// import vueJsx from '@vitejs/plugin-vue2-jsx'

import path from "path";

export default defineConfig({
    plugins: [
        // vitePluginRequire(),
        vitePluginRequire.default({ fileRegex:/(.jsx?|.tsx?|.vue)$/ }),
        // vueJsx({}),
        laravel([
            'resources/sass/app.scss',
            'resources/js/app.js',
        ]),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            src: path.resolve(__dirname)
        }
    },
});