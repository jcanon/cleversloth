/**
 * Lightweight release checks for the Vue + PHP FTP package.
 * This intentionally avoids a browser dependency so it can run on any machine.
 */
import { existsSync, readFileSync } from 'node:fs';
import { resolve } from 'node:path';

const root = resolve(import.meta.dirname, '..');
const requireFile = (file) => {
  if (!existsSync(resolve(root, file))) throw new Error('Missing required file: ' + file);
};
const read = (file) => readFileSync(resolve(root, file), 'utf8');

[
  'src/App.vue',
  'src/views/HomeView.vue',
  'src/views/LegalView.vue',
  'src/components/ContactForm.vue',
  'src/styles.css',
  'public/api/contact.php',
  'public/api/config.php',
  'public/site-config.js',
  'public/images/sloth-success.png',
  'public/fonts/Outfit-Variable.ttf',
  'public/fonts/DM-Sans-Variable.ttf',
  'DEPLOYMENT.md',
].forEach(requireFile);

const home = read('src/views/HomeView.vue');
const form = read('src/components/ContactForm.vue');
const endpoint = read('public/api/contact.php');
const app = read('src/App.vue');

for (const target of ['#services', '#about', '#process', '#contact']) {
  if (!home.includes(target) && !app.includes(target)) throw new Error('Missing navigable section: ' + target);
}
for (const expected of ['csrfToken', 'website', 'formStartedAt', 'privacyAccepted', 'isSubmitted']) {
  if (!form.includes(expected)) throw new Error('Contact form is missing expected protection or state: ' + expected);
}
for (const expected of ['hash_equals', 'enforceRateLimit', 'verifyTurnstile', 'FILTER_VALIDATE_EMAIL', 'minimum_form_seconds']) {
  if (!endpoint.includes(expected)) throw new Error('PHP endpoint is missing protection: ' + expected);
}
if (!app.includes('new Date().getFullYear()')) throw new Error('Footer year is not automatic.');
if (!read('src/styles.css').includes('prefers-reduced-motion')) throw new Error('Reduced motion support missing.');
if (!read('src/styles.css').includes('.skip-link')) throw new Error('Skip link styles missing.');

console.log('PASS: Vue routes, contact-form state, PHP safeguards, automatic year, assets, and accessibility hooks are present.');
