export default defineNuxtConfig({
  // SPA-mode voor maximale compatibiliteit met je PHP API
  ssr: false,

  compatibilityDate: '2024-11-01',

  modules: [
    '@nuxt/content'
  ],

  app: {
    baseURL: '/', 
    buildAssetsDir: '_nuxt', 
    head: {
      title: 'Huizinga Genealogie',
      htmlAttrs: { lang: 'nl' },
      link: [
        { 
          rel: 'stylesheet', 
          href: 'https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&family=Onest:wght@100..900&display=swap' 
        }
      ]
    }
  },

  content: {
    documentDriven: false,
    markdown: { anchorLinks: false }
  },

  nitro: {
    prerender: {
      crawlLinks: false, 
      routes: [
        '/',
        '/familie/huizinga',
        '/familie/waterbolk',
        '/geschiedenis/houwerzijl',
        '/geschiedenis/hogeland'
      ]
    }
  },

  routeRules: {
    '/personen/**': { ssr: false }
  },

  typescript: { 
    typeCheck: false, 
    strict: false 
  },

  devtools: { 
    enabled: true 
  },
  
  experimental: { 
    appManifest: false,
    payloadExtraction: false, 
    renderJsonPayloads: false  
  }
})