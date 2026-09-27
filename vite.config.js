import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import { resolve } from 'node:path';

export default defineConfig({
  plugins: [vue()],
  build: {
    outDir: 'deploy',
    emptyOutDir: true,
    assetsDir: 'assets',
    minify: false,
  },
  resolve: {
    alias: { '@': resolve(import.meta.dirname, 'src') },
  },
});
