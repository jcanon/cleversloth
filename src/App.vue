<script setup>
import { ref } from 'vue';
import { RouterLink, RouterView, useRoute } from 'vue-router';

const isMenuOpen = ref(false);
const route = useRoute();

function closeMenu() {
  isMenuOpen.value = false;
}
</script>

<template>
  <a class="skip-link" href="#main-content">Skip to content</a>

  <header class="site-header">
    <div class="container header-inner">
      <RouterLink class="brand" to="/" aria-label="Clever Sloth LLC home" @click="closeMenu">
        <img src="/images/sloth-mark.png" width="54" height="54" alt="" />
        <span>
          Clever<span class="brand-green">Sloth</span>
          <small>WEB DEVELOPMENT CONSULTING</small>
        </span>
      </RouterLink>

      <button
        class="menu-toggle"
        type="button"
        :aria-expanded="isMenuOpen"
        aria-controls="primary-navigation"
        :aria-label="isMenuOpen ? 'Close navigation menu' : 'Open navigation menu'"
        @click="isMenuOpen = !isMenuOpen"
      >
        <svg viewBox="0 0 24 24" width="26" height="26" aria-hidden="true">
          <path d="M4 7h16M4 12h16M4 17h16" />
        </svg>
      </button>

      <nav
        id="primary-navigation"
        class="navigation"
        :class="{ 'is-open': isMenuOpen }"
        aria-label="Main navigation"
      >
        <RouterLink :to="{ path: '/', hash: '#services' }" @click="closeMenu">Services</RouterLink>
        <RouterLink :to="{ path: '/', hash: '#about' }" @click="closeMenu">Why Clever Sloth</RouterLink>
        <RouterLink :to="{ path: '/', hash: '#process' }" @click="closeMenu">Our process</RouterLink>
        <RouterLink class="button button-small" :to="{ path: '/', hash: '#contact' }" @click="closeMenu">Let’s talk <span aria-hidden="true">↗</span></RouterLink>
      </nav>
    </div>
  </header>

  <main id="main-content" tabindex="-1">
    <RouterView />
  </main>

  <footer class="site-footer">
    <div class="container footer-main">
      <RouterLink class="brand" to="/" aria-label="Clever Sloth LLC home">
        <img src="/images/sloth-mark.png" width="48" height="48" alt="" />
        <span>
          Clever<span class="brand-green">Sloth</span>
          <small>WEB DEVELOPMENT CONSULTING</small>
        </span>
      </RouterLink>
      <p>Thoughtful web development for growing businesses.</p>
      <nav aria-label="Footer navigation">
        <RouterLink :to="{ path: '/', hash: '#services' }">Services</RouterLink>
        <RouterLink :to="{ path: '/', hash: '#about' }">Why Clever Sloth</RouterLink>
        <RouterLink :to="{ path: '/', hash: '#process' }">Our process</RouterLink>
      </nav>
    </div>
    <div class="container footer-bottom">
      <span class="footer-identity">
        <span>© {{ new Date().getFullYear() }} Clever Sloth LLC. All rights reserved.</span>
        <span class="footer-legal"><RouterLink to="/privacy">Privacy</RouterLink><span aria-hidden="true">·</span><RouterLink to="/terms">Terms</RouterLink></span>
      </span>
      <RouterLink v-if="route.path === '/'" :to="{ path: '/', hash: '#home' }">Back to top <span aria-hidden="true">↑</span></RouterLink>
      <RouterLink v-else to="/">Back home <span aria-hidden="true">→</span></RouterLink>
    </div>
  </footer>
</template>
