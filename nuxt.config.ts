import { readdirSync } from 'fs'
import { resolve } from 'path'

// Haal de lijst met bestandsnamen op uit je content map
const getPersonenRoutes = () => {
  const contentDir = resolve(__dirname, 'content/personen')
  try {
    const files = readdirSync(contentDir)
    // Maak van '1-albert.json' -> '/personen/1-albert'
    return files
      .filter(file => file.endsWith('.json'))
      .map(file => `/personen/${file.replace('.json', '')}`)
  } catch (e) {
    return []
  }
}

export default defineNuxtConfig({
  devtools: { enabled: false },

  modules: [
    '@nuxt/content',
    '@nuxtjs/sitemap'
  ],

  site: {
    url: 'https://jouwdomein.nl', 
    name: 'Huizinga Genealogie'
  },

  // Dwing de prerenderer om ELK bestand te verwerken
  nitro: {
    prerender: {
      crawlLinks: false, // We geven de lijst zelf wel, scheelt geheugen
      routes: [
        '/',
        '/sitemap.xml',
        ...getPersonenRoutes() // Hier laden we de 2629 routes in
      ],
      concurrency: 1, // Houd het veilig voor je RAM
      failOnError: false
    }
  },

  routeRules: {
    '/personen/**': { prerender: true }
  },

  sitemap: {
    strictNuxtContentAds: true
  },

  compatibilityDate: '2024-04-03'
})