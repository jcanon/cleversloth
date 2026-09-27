<script setup>
import ContactForm from '@/components/ContactForm.vue';

const services = [
  ['laptop', 'sage', 'WordPress & websites', 'Websites your team can confidently manage. Custom themes, plugins, multisite, and thoughtful builds around your content and goals.', 'WordPress · ACF · Elementor · WPBakery'],
  ['code', 'blue', 'Custom web solutions', 'Applications and integrations that fit the way you work. Connect your tools, simplify workflows, and solve the problems off-the-shelf software can’t.', 'PHP · APIs · CRM & system integrations'],
  ['cart', 'peach', 'WooCommerce & commerce', 'Practical online stores and payment workflows, with the custom functionality and connected systems your business needs.', 'WooCommerce · Payments · Integrations'],
  ['refresh', 'cream', 'Rescue & modernization', 'Stalled projects and aging platforms deserve a clear plan. We assess what you have, keep what works, and improve what’s holding you back.', 'Inherited sites · PHP · ColdFusion · .NET'],
  ['shield', 'blue', 'Care & ongoing support', 'A dependable technical partner after launch. Updates, troubleshooting, security hardening, backups, hosting, and steady improvements.', 'Maintenance · Performance · Reliability'],
  ['lightbulb', 'sage', 'Technical consulting', 'Make confident decisions with clear advice. Architecture, accessibility, platform reviews, and roadmaps grounded in your business needs.', 'Strategy · Accessibility · Documentation'],
];

const principles = [
  ['Understand before rebuilding.', 'Your existing system may need a thoughtful upgrade, not a fresh start. We recommend what makes sense for you.'],
  ['Build for the people who come next.', 'Clear architecture, useful documentation, and maintainable code help your team stay in control.'],
  ['Make quality part of the foundation.', 'Security, accessibility, and performance belong in the work from the beginning, with continued care after launch.'],
];

const process = [
  ['Listen & understand', 'We learn about your business, your people, and the technical landscape. Good questions come before recommendations.'],
  ['Plan & build', 'We agree on priorities, explain the tradeoffs, and deliver in manageable steps with clear communication along the way.'],
  ['Launch & look after', 'We test, document, and hand over with care. Then we stay available for support, improvements, and whatever comes next.'],
];
</script>

<template>
  <section id="home" class="hero" aria-labelledby="hero-title">
    <div class="container hero-grid">
      <div class="hero-content">
        <p class="eyebrow"><span class="eyebrow-line"></span> CLEVER THINKING. STEADY PROGRESS.</p>
        <h1 id="hero-title">Thoughtful web<br />development.<br /><span>Built with care.</span></h1>
        <p class="hero-description">Good technology should make your business easier to run. We build, improve, and care for websites and web applications that do exactly that.</p>
        <div class="actions">
          <RouterLink class="button" :to="{ path: '/', hash: '#contact' }">Let’s build something good <img src="/icons/arrow.svg" alt="" width="21" height="21" /></RouterLink>
          <RouterLink class="text-link" :to="{ path: '/', hash: '#services' }">Explore our services <span aria-hidden="true">↓</span></RouterLink>
        </div>
        <p class="hero-assurance"><img src="/icons/check.svg" width="20" height="20" alt="" /> Clear communication. Practical solutions. No unnecessary complexity.</p>
      </div>
      <div class="hero-visual">
        <div class="art-halo" aria-hidden="true"></div>
        <img class="hero-mascot" src="/images/sloth-hero.png" width="1536" height="1024" alt="The Clever Sloth mascot smiling at a laptop, surrounded by leafy green plants." fetchpriority="high" />
        <p class="art-note"><img src="/icons/leaf.svg" width="24" height="24" alt="" /> <span>A little more thought.<br /><strong>A lot more peace of mind.</strong></span></p>
      </div>
    </div>
    <div class="container values-strip" aria-label="Clever Sloth values">
      <span><img src="/icons/leaf.svg" alt="" width="22" height="22" /> Thoughtful</span>
      <span><img src="/icons/shield.svg" alt="" width="22" height="22" /> Reliable</span>
      <span><img src="/icons/lightbulb.svg" alt="" width="22" height="22" /> Clever</span>
      <span><img src="/icons/people.svg" alt="" width="22" height="22" /> Human</span>
      <p>Serious about your work.<br /><strong>Easy to work with.</strong></p>
    </div>
  </section>

  <section id="services" class="section services" aria-labelledby="services-title">
    <div class="container">
      <div class="section-heading">
        <div><p class="eyebrow">WHAT WE CAN HELP WITH</p><h2 id="services-title">The right support.<br /><span>Right where you need it.</span></h2></div>
        <p>A new idea, an inherited website, or a system that needs some attention. We help you find a sensible way forward.</p>
      </div>
      <div class="service-grid">
        <article v-for="service in services" :key="service[2]" class="service-card">
          <span :class="['icon-badge', service[1]]"><img :src="`/icons/${service[0]}.svg`" width="32" height="32" alt="" /></span>
          <h3>{{ service[2] }}</h3><p>{{ service[3] }}</p><p class="card-detail">{{ service[4] }}</p>
        </article>
      </div>
      <p class="services-footnote"><span>Not sure where your project fits?</span> <RouterLink :to="{ path: '/', hash: '#contact' }">Start with a conversation <span aria-hidden="true">↗</span></RouterLink></p>
    </div>
  </section>

  <section id="about" class="section about" aria-labelledby="about-title">
    <div class="container about-grid">
      <article class="philosophy-panel">
        <p class="eyebrow">THE CLEVER SLOTH WAY</p>
        <h2>Move thoughtfully.<br />Build confidently.</h2>
        <p>The name is a reminder: taking time to understand the problem makes the solution better.</p>
        <div class="panel-rule"></div><div class="experience-number">20<span>+</span></div>
        <p class="experience-label">years of hands-on web<br />and application experience</p>
        <p class="experience-context">Experience spanning consulting, public service, higher education, healthcare, and mission-driven organizations.</p>
        <img class="panel-leaf" src="/icons/leaf.svg" alt="" width="100" height="100" />
      </article>
      <div class="about-copy">
        <p class="eyebrow">A TECHNICAL PARTNER, IN YOUR CORNER</p>
        <h2 id="about-title">Strong engineering.<br /><span>A human approach.</span></h2>
        <p class="section-description">We bring the care of a long-term partner and the discipline of enterprise engineering to your everyday web challenges.</p>
        <div v-for="([title, description], index) in principles" :key="title" class="principle"><span>0{{ index + 1 }}</span><div><h3>{{ title }}</h3><p>{{ description }}</p></div></div>
      </div>
    </div>
  </section>

  <section id="process" class="section process" aria-labelledby="process-title">
    <div class="container">
      <div class="section-heading centered"><p class="eyebrow">FROM FIRST CONVERSATION TO WHAT’S NEXT</p><h2 id="process-title">A clear path. <span>Steady progress.</span></h2><p>You should always know where things stand, what comes next, and why it matters.</p></div>
      <div class="process-grid">
        <article v-for="([title, description], index) in process" :key="title" class="process-step">
          <div class="step-top"><span class="step-number">0{{ index + 1 }}</span><span class="step-line" aria-hidden="true"></span></div><h3>{{ title }}</h3><p>{{ description }}</p>
        </article>
      </div>
    </div>
  </section>

  <section id="contact" class="contact-section" aria-labelledby="contact-title">
    <div class="container contact-panel">
      <div>
        <p class="eyebrow">GOOD WORK STARTS WITH A CONVERSATION</p>
        <h2 id="contact-title">Let’s make your<br />next step <span>a clever one.</span></h2>
        <p>Tell us what you’re working on, what’s getting in the way, or what you’d like to do better. We’ll help you work out where to start.</p>
        <ContactForm />
      </div>
    </div>
  </section>
</template>
