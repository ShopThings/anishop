import {defineConfig} from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [
        vue({
            template: {
                compilerOptions: {
                    isCustomElement: (tag) => ['swiper-container', 'swiper-slide'].includes(tag),
                }
            }
        })
    ],
    resolve: {
        mainFields: [
            'browser',
            'module',
            'main',
            'jsnext:main',
            'jsnext'
        ]
    },
})
