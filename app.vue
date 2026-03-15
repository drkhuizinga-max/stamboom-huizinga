<script setup>
import { watch } from 'vue';

// Gebruik useHead om de Statcounter scripts in de <head> te laden
useHead({
  script: [
    {
      // Je persoonlijke Statcounter variabelen
      innerHTML: `
        var sc_project=11208417; 
        var sc_invisible=1; 
        var sc_security="74b92945";
      `,
      type: 'text/javascript'
    },
    {
      // Het hoofdbestand van Statcounter
      src: 'https://www.statcounter.com/counter/counter.js',
      async: true,
      type: 'text/javascript'
    }
  ],
  noscript: [
    {
      // De backup voor als JavaScript uit staat
      innerHTML: `<div class="statcounter"><a title="Web Analytics" href="https://statcounter.com/" target="_blank"><img class="statcounter" src="https://c.statcounter.com/11208417/0/74b92945/1/" alt="Web Analytics" referrerPolicy="no-referrer-when-downgrade"></a></div>`
    }
  ]
})

// --- NUXT PAGINA-WISSELS METEN ---
// Omdat Nuxt een Single Page App is, moeten we Statcounter vertellen wanneer je van pagina wisselt
const route = useRoute();

watch(() => route.fullPath, () => {
  // We wachten heel even tot de nieuwe pagina-titel is geladen
  setTimeout(() => {
    if (window._statcounter) {
      window._statcounter.record_pageview();
    }
  }, 300);
}, { immediate: false });

</script>

<template>
  <div>
    <nav style="background: #333; padding: 10px; display: flex; gap: 20px;">
      <NuxtLink to="/" style="color: white;">Home</NuxtLink>
      <NuxtLink to="/beheer" style="color: white; font-weight: bold;">GA NAAR BEHEER</NuxtLink>
    </nav>

    <NuxtPage />
  </div>
</template>

<style>
/* CSS blijft ongewijzigd */
html, body, #__nuxt {
  height: 100%;
  margin: 0;
  padding: 0;
}

.site-wrapper {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  background-color: #fdfcf9;
}

.main-content {
  flex: 1;
}

.global-banner {
  width: 100%;
  height: 250px;
  position: relative;
  overflow: hidden;
  border-bottom: 2px solid #d4af37;
}

.global-banner img {
  width: 100%;
  height: 100%;
  object-fit: cover; 
  object-position: center 70%;
}

.banner-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(to bottom, rgba(26, 42, 58, 0.2), transparent);
}

.site-footer {
  padding: 50px 20px;
  background-color: #fdfcf9;
  text-align: center;
  border-top: 1px solid #e0ddd5;
  color: #555;
  font-family: 'Georgia', serif;
}

.footer-line {
  width: 40px;
  height: 2px;
  background-color: #d4af37;
  margin: 0 auto 20px;
}

.site-footer strong {
  color: #333;
}

.footer-sub {
  font-size: 0.85rem;
  font-style: italic;
  margin-top: 10px;
  color: #888;
}

@media (max-width: 768px) {
  .global-banner {
    height: 150px;
  }
  .site-footer {
    padding: 30px 15px;
  }
}
</style>