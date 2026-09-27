<script setup>
import { computed } from 'vue';

const props = defineProps({ documentType: { type: String, required: true } });
const company = window.CLEVER_SLOTH_CONFIG || {};
const updatedDate = 'September 27, 2026';
const isPrivacy = computed(() => props.documentType === 'privacy');
</script>

<template>
  <section class="legal-page">
    <article class="container legal-content">
      <p class="eyebrow">{{ isPrivacy ? 'PRIVACY POLICY' : 'TERMS OF USE' }}</p>
      <h1>{{ isPrivacy ? 'Privacy Policy' : 'Terms of Use' }}</h1>
      <p class="legal-updated">Effective date: {{ updatedDate }}</p>

      <template v-if="isPrivacy">
        <p>This Privacy Policy describes how Clever Sloth LLC (“Clever Sloth,” “we,” “us,” or “our”) collects, uses, discloses, and protects personal information when you visit cleversloth.com, use the contact form, or otherwise interact with this website (the “Site”).</p>
        <h2>Information we collect</h2>
        <p>We collect information you choose to provide: your name, email address, company or organization, phone number, project category, and message. Please do not submit passwords, payment-card data, government identification numbers, health information, or other sensitive information through the contact form.</p>
        <p>When you visit the Site, we and our service providers may automatically collect technical and usage information, including your IP address, browser and device information, operating system, referring URL, pages viewed, interactions with the Site, and the date and time of access. This information may be collected through server logs, cookies, and similar technologies.</p>
        <h2>Google Analytics and cookies</h2>
        <p>Clever Sloth uses Google Analytics, including Google Analytics 4, to measure Site traffic and usage and to improve the Site’s performance, content, and usability. Google Analytics uses cookies and similar technologies to collect technical, device, browser, usage, and online-identifier information about your use of the Site. Google may receive and process this information under its own privacy policy.</p>
        <p>We do not send contact-form field values, payment information, passwords, or other sensitive information to Google Analytics. You can control cookies through your browser settings. You may also use Google’s Analytics Opt-out Browser Add-on, available from Google, to limit Google Analytics measurement in supported browsers.</p>
        <h2>How we use information</h2>
        <p>We use personal information to respond to inquiries; discuss potential services; communicate with you; operate, secure, troubleshoot, and improve the Site; measure Site usage; prevent spam, fraud, and misuse; maintain business records; and comply with legal obligations. We do not sell personal information or use contact-form submissions for recurring marketing email unless you separately agree to receive it.</p>
        <h2>Security and retention</h2>
        <p>We retain personal information for as long as reasonably necessary for the purposes described in this Policy, to resolve disputes, maintain appropriate business records, and comply with legal obligations. We use reasonable administrative, technical, and organizational safeguards designed to protect personal information, but no method of transmission or storage is completely secure.</p>
        <h2>Service providers and legal disclosures</h2>
        <p>We may disclose personal information to providers that host and secure the Site, deliver email, provide analytics, and support our operations. We may also disclose information when required or permitted by law; to protect rights, safety, and property; to investigate or prevent security incidents, fraud, or misuse; or in connection with a merger, acquisition, financing, reorganization, sale of assets, or similar transaction.</p>
        <h2>Your choices and rights</h2>
        <p>Depending on where you live and subject to applicable law, you may request access to, correction of, or deletion of personal information we hold about you. To make a request, email <a :href="`mailto:${company.companyEmail}`">{{ company.companyEmail }}</a> with “Privacy Request” in the subject line. We may need to verify your identity and may retain information where permitted or required by law.</p>
        <h2>Children’s privacy</h2>
        <p>The Site is intended for businesses and adults. We do not knowingly collect personal information from children under 13. If you believe a child has provided personal information through the Site, please contact us and we will take appropriate steps to delete it.</p>
        <h2>External websites and changes</h2>
        <p>The Site may link to websites or services we do not control. Their privacy practices are governed by their own policies. We may update this Privacy Policy from time to time; the effective date at the top of this page identifies the most recent version.</p>
        <h2>Contact</h2>
        <p>For privacy questions or requests, contact <a :href="`mailto:${company.companyEmail}`">{{ company.companyEmail }}</a>.</p>
      </template>

      <template v-else>
        <p>These Terms of Use (“Terms”) govern your access to and use of cleversloth.com and its content, features, and contact form (the “Site”). The Site is operated by Clever Sloth LLC (“Clever Sloth,” “we,” “us,” or “our”). By using the Site, you agree to these Terms. If you do not agree, do not use the Site.</p>
        <h2>Purpose of the Site; no professional engagement</h2>
        <p>The Site provides general information about Clever Sloth and its services. It is not an offer to provide services, a promise of results, or professional advice. Use of the Site, submission of a contact form, or communication with us does not create a client, vendor, partnership, employment, fiduciary, or other professional relationship. Services are provided only under a separate written agreement signed by authorized representatives of both parties.</p>
        <h2>Acceptable use</h2>
        <p>You may use the Site only for lawful purposes. You may not violate applicable law or another person’s rights; interfere with, damage, or attempt unauthorized access to the Site or related systems; submit malware, harmful code, deceptive content, or unsolicited messages; use bots, scrapers, or other automated means to access the Site or abuse the contact form without written permission; or remove notices or misuse our name, logos, content, or materials.</p>
        <h2>Intellectual property</h2>
        <p>Unless otherwise noted, the Site’s text, design, branding, logos, illustrations, graphics, code, and other content are owned by or licensed to Clever Sloth and protected by applicable intellectual-property laws. You receive a limited, revocable, nonexclusive, nontransferable license to view the Site for personal or internal business purposes. You may not copy, reproduce, modify, distribute, display, publish, create derivative works from, or commercially exploit Site content without our prior written permission.</p>
        <h2>Feedback</h2>
        <p>If you send feedback, ideas, or suggestions about the Site, you grant Clever Sloth a nonexclusive, worldwide, perpetual, irrevocable, royalty-free right to use, reproduce, modify, and incorporate that feedback without compensation or obligation to you. We handle personal information submitted through the contact form under our Privacy Policy.</p>
        <h2>Third-party links and services</h2>
        <p>The Site may contain links to or integrations with third-party websites and services. Those third parties are responsible for their own content, availability, privacy practices, and terms. We do not endorse and are not responsible for third-party websites or services.</p>
        <h2>Disclaimers and limitation of liability</h2>
        <p>THE SITE IS PROVIDED ON AN “AS IS” AND “AS AVAILABLE” BASIS. TO THE FULLEST EXTENT PERMITTED BY LAW, CLEVER SLOTH DISCLAIMS ALL WARRANTIES, WHETHER EXPRESS, IMPLIED, OR STATUTORY, INCLUDING WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, TITLE, NON-INFRINGEMENT, ACCURACY, AND UNINTERRUPTED OR ERROR-FREE OPERATION.</p>
        <h2>Limitation of liability</h2>
        <p>TO THE FULLEST EXTENT PERMITTED BY LAW, CLEVER SLOTH AND ITS MEMBERS, MANAGERS, EMPLOYEES, CONTRACTORS, AND SERVICE PROVIDERS WILL NOT BE LIABLE FOR ANY INDIRECT, INCIDENTAL, SPECIAL, CONSEQUENTIAL, EXEMPLARY, OR PUNITIVE DAMAGES, OR FOR ANY LOSS OF DATA, PROFITS, REVENUE, GOODWILL, OR BUSINESS INTERRUPTION, ARISING OUT OF OR RELATED TO THE SITE OR THESE TERMS. TO THE FULLEST EXTENT PERMITTED BY LAW, OUR TOTAL LIABILITY FOR ANY CLAIM ARISING OUT OF OR RELATED TO THE SITE OR THESE TERMS WILL NOT EXCEED ONE HUNDRED U.S. DOLLARS (US $100).</p>
        <h2>Indemnification</h2>
        <p>You will defend, indemnify, and hold harmless Clever Sloth and its members, managers, employees, contractors, and service providers from claims, liabilities, damages, losses, and expenses, including reasonable attorneys’ fees, arising from your violation of these Terms, misuse of the Site, or violation of another person’s rights.</p>
        <h2>Governing law</h2>
        <p>These Terms and any dispute arising from or relating to the Site are governed by the laws of the Commonwealth of Pennsylvania, without regard to conflict-of-law principles. Except where applicable law requires otherwise, exclusive jurisdiction and venue for disputes will lie in the state or federal courts located in Pennsylvania.</p>
        <h2>Changes, severability, and contact</h2>
        <p>We may revise these Terms by posting an updated version on the Site. If any provision is held unenforceable, the remaining provisions remain in effect. These Terms and the Privacy Policy are the entire agreement between you and Clever Sloth regarding the Site and do not replace a separate written services agreement. For questions, contact <a :href="`mailto:${company.companyEmail}`">{{ company.companyEmail }}</a>.</p>
      </template>
    </article>
  </section>
</template>
