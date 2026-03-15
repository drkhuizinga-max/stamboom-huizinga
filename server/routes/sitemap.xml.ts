export default defineEventHandler(async (event) => {
  // Haal de XML data op van je PHP server
  const response = await $fetch('https://familiehuizinga.nl/api/sitemap.php')
  
  // Vertel de browser dat dit een XML bestand is
  setResponseHeader(event, 'Content-Type', 'application/xml')
  
  return response
})