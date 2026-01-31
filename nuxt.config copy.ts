export default defineNuxtConfig({
  modules: [
    '@nuxt/content',
    '@nuxtjs/sitemap' // Voeg deze toe
  ],

  site: {
    url: 'https://jouwdomein.nl', // Vervang door je echte domein
    name: 'Huizinga Genealogie'
  },

  sitemap: {
    // Dit zorgt ervoor dat alle 2629 content-pagina's worden meegenomen
    strictNuxtContentAds: true,
    exclude: [
      '/api/**',
      '/admin/**'
    ],
    defaults: {
      changefreq: 'monthly',
      priority: 0.8,
      lastmod: new Date()
    }
  }
})