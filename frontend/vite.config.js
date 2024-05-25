import {defineConfig} from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'
import UnheadVite from '@unhead/addons/vite'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    UnheadVite(),
    vue({
      template: {
        compilerOptions: {
          isCustomElement: (tag) => ['swiper-container', 'swiper-slide'].includes(tag),
        },
      },
    }),
  ],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'src'),
    },
    mainFields: [
      'browser',
      'module',
      'main',
      'jsnext:main',
      'jsnext',
    ]
  },
})
