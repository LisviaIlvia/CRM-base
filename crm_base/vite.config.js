import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import vuetify from 'vite-plugin-vuetify'
import { resolve } from 'path';
import svgLoader from 'vite-svg-loader';
import dynamicImport from 'vite-plugin-dynamic-import-vars';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                }
            },
        }),
		vuetify({
			styles: {
				configFile: 'resources/sass/settings.scss',
			},
		}),
		svgLoader(),
		dynamicImport({
            warnOnError: true,
        }),
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
			'@': resolve(__dirname, 'resources/js')
        },
    },
	css: {
        preprocessorOptions: {
            scss: {
                api: 'modern-compiler',
            }
        }
    },
	mode: 'development'
});
