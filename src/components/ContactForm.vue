<script setup>
import { computed, onMounted, ref } from 'vue';

const config = window.CLEVER_SLOTH_CONFIG || {};
const form = ref({
  name: '',
  company: '',
  email: '',
  phone: '',
  projectType: '',
  message: '',
  privacyAccepted: false,
  website: '', // Honeypot: visually hidden and never described to a real visitor.
});
const csrfToken = ref('');
const startedAt = ref(Date.now());
const isSubmitting = ref(false);
const formError = ref('');
const isSubmitted = ref(false);
const turnstileToken = ref('');
const turnstileElement = ref(null);
const showTurnstile = computed(() => Boolean(config.turnstileSiteKey));

async function fetchCsrfToken() {
  try {
    const response = await fetch(config.contactApiUrl || '/api/contact.php', { credentials: 'same-origin' });
    const body = await response.json();
    csrfToken.value = body.csrfToken || '';
  } catch {
    // The form will explain the issue on submission. Do not expose server details.
  }
}

function renderTurnstile() {
  if (!showTurnstile.value || !window.turnstile || !turnstileElement.value) return;
  window.turnstile.render(turnstileElement.value, {
    sitekey: config.turnstileSiteKey,
    callback: (token) => { turnstileToken.value = token; },
    'expired-callback': () => { turnstileToken.value = ''; },
  });
}

function loadTurnstile() {
  if (!showTurnstile.value) return;
  if (window.turnstile) return renderTurnstile();
  const script = document.createElement('script');
  script.src = 'https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit';
  script.async = true;
  script.defer = true;
  script.onload = renderTurnstile;
  document.head.appendChild(script);
}

async function submitForm() {
  formError.value = '';
  if (!csrfToken.value) {
    formError.value = 'The form could not start securely. Please refresh the page and try again.';
    return;
  }
  if (showTurnstile.value && !turnstileToken.value) {
    formError.value = 'Please complete the security check before sending your message.';
    return;
  }

  isSubmitting.value = true;
  try {
    const response = await fetch(config.contactApiUrl || '/api/contact.php', {
      method: 'POST',
      credentials: 'same-origin',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrfToken.value },
      body: JSON.stringify({
        ...form.value,
        formStartedAt: startedAt.value,
        turnstileToken: turnstileToken.value,
      }),
    });
    const body = await response.json();
    if (!response.ok || !body.ok) {
      formError.value = body.message || 'Your message could not be sent. Please try again later.';
      return;
    }
    isSubmitted.value = true;
  } catch {
    formError.value = 'We could not reach the server. Please try again later or email info@cleversloth.com.';
  } finally {
    isSubmitting.value = false;
  }
}

onMounted(() => {
  fetchCsrfToken();
  loadTurnstile();
});
</script>

<template>
  <section class="contact-form-area" aria-labelledby="contact-form-title">
    <form v-if="!isSubmitted" class="contact-form" @submit.prevent="submitForm">
      <h3 id="contact-form-title">Tell us a little about your project.</h3>
      <p class="form-help">Fields marked <span aria-hidden="true">*</span><span class="sr-only">required</span> are required.</p>

      <div class="form-grid">
        <div class="form-field"><label for="name">Your name <span aria-hidden="true">*</span></label><input id="name" v-model.trim="form.name" name="name" autocomplete="name" required maxlength="100" /></div>
        <div class="form-field"><label for="email">Email address <span aria-hidden="true">*</span></label><input id="email" v-model.trim="form.email" name="email" type="email" autocomplete="email" required maxlength="254" /></div>
        <div class="form-field"><label for="company">Company or organization</label><input id="company" v-model.trim="form.company" name="company" autocomplete="organization" maxlength="150" /></div>
        <div class="form-field"><label for="phone">Phone number</label><input id="phone" v-model.trim="form.phone" name="phone" type="tel" autocomplete="tel" maxlength="40" /></div>
        <div class="form-field form-field-wide"><label for="project-type">What can we help with? <span aria-hidden="true">*</span></label><select id="project-type" v-model="form.projectType" name="projectType" required><option value="" disabled>Select one option</option><option>WordPress or website work</option><option>Custom web application or integration</option><option>WooCommerce or ecommerce</option><option>Modernization or project rescue</option><option>Ongoing support or maintenance</option><option>Technical consulting</option><option>Something else</option></select></div>
        <div class="form-field form-field-wide"><label for="message">A short description <span aria-hidden="true">*</span></label><textarea id="message" v-model.trim="form.message" name="message" rows="5" required maxlength="3000" aria-describedby="message-help"></textarea><p id="message-help" class="field-help">Please do not include passwords, payment details, or other sensitive information.</p></div>
      </div>

      <div class="honeypot" aria-hidden="true"><label for="website">Website</label><input id="website" v-model="form.website" name="website" tabindex="-1" autocomplete="off" /></div>
      <div v-if="showTurnstile" ref="turnstileElement" class="turnstile-slot" aria-label="Security verification"></div>

      <label class="consent"><input v-model="form.privacyAccepted" type="checkbox" required /><span>I agree that Clever Sloth LLC may use this information to respond to my inquiry, as described in the <RouterLink to="/privacy">Privacy Policy</RouterLink>. <strong aria-hidden="true">*</strong></span></label>
      <p v-if="formError" class="form-error" role="alert">{{ formError }}</p>
      <button class="button button-cream" type="submit" :disabled="isSubmitting">{{ isSubmitting ? 'Sending your message…' : 'Send your message' }} <span aria-hidden="true">↗</span></button>
    </form>

    <div v-else class="form-success" role="status" aria-live="polite">
      <img class="success-mascot" src="/images/sloth-success.png" width="1295" height="1214" alt="A smiling Clever Sloth mascot giving a thumbs up." />
      <div class="form-success-copy">
        <h3>High five — we’ve got it.</h3>
        <p>Thanks for reaching out. Your message is on its way to Clever Sloth LLC, and we’ll be in touch soon.</p>
      </div>
    </div>
  </section>
</template>
