import { createApp } from 'vue';
import { createRouter, createWebHashHistory } from 'vue-router';
import App from './App.vue';
import HomeView from './views/HomeView.vue';
import LegalView from './views/LegalView.vue';
import './styles.css';

const routes = [
  { path: '/', component: HomeView, meta: { title: 'Thoughtful web development' } },
  { path: '/privacy', component: LegalView, props: { documentType: 'privacy' }, meta: { title: 'Privacy Policy' } },
  { path: '/terms', component: LegalView, props: { documentType: 'terms' }, meta: { title: 'Terms of Use' } },
];

const router = createRouter({
  history: createWebHashHistory(),
  routes,
  scrollBehavior(to) {
    if (to.hash) return { el: to.hash, behavior: 'smooth' };
    return { top: 0 };
  },
});

router.afterEach((to) => {
  const suffix = to.meta.title || 'Clever Sloth LLC';
  document.title = `${suffix} | Clever Sloth LLC`;
});

createApp(App).use(router).mount('#app');
