<template>
  <div class="photo-gallery-page">
    <div class="container">
      
      <section class="gallery-header">
        <h1 class="gallery-title">Fotoarchief</h1>
        <p class="gallery-subtitle">FAMILIE HUIZINGA — EEN VISUELE REIS</p>
        <div class="header-divider"></div>
      </section>

      <div class="gallery-grid">
        <div v-for="(naam, index) in fotoNamen" :key="index" class="gallery-item">
          <div class="photo-card">
            <div class="photo-box">
              <img 
                :src="localDir + 'rwb_' + (index + 1) + '.webp'" 
                :alt="naam" 
                class="gallery-img"
                loading="lazy"
                @error="handleImageError"
              />
            </div>
            <div class="info-box">
              <p class="photo-name">{{ naam }}</p>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { onMounted, nextTick } from 'vue'

// Verwijst nu naar de lokale map in /public/images/personen/
const localDir = '/images/personen/'
// Fallback voor het geval een specifieke foto in de algemene fotomap staat
const fallbackDir = '/images/foto/'

const handleImageError = (event) => {
  const imgElement = event.target
  const fileName = imgElement.src.split('/').pop()
  const fallbackUrl = fallbackDir + fileName
  
  if (imgElement.src !== window.location.origin + fallbackUrl) {
    imgElement.src = fallbackUrl
  }
}

onMounted(async () => {
  await nextTick()
  window.scrollTo(0, 0)
})

const fotoNamen = [
  "Hendrik Olferts Huizinga en Barbara Wrister Brugman", 
  "Hendrik Olferts Huizinga en Barbara Wrister Brugman", 
  "Olfert Hendrik Huizinga en Anje Klaasens Lap",       
  "Kees van Straten en Zwaantje Lap",                  
  "Grietje van Straten",                               
  "Grietje van Straten",                               
  "Grietje Sleuver",                                   
  "Grietje Sleuver",                                   
  "Ulfert Huizinga",                                   
  "Ulfert Huizinga",                                   
  "Familie Ulfert Huizinga",                           
  "Luwina Reitsema",                                   
  "Luwina Reitsema en Grietje Waterbolk",              
  "Abel Waterbolk",                                    
  "Abel Waterbolk",                                    
  "Cornelis Huizinga",                                 
  "Cornelis Huizinga",                                 
  "Cornelis Huizinga en Grietje Waterbolk",            
  "Grietje Waterbolk",                                 
  "Fam Cornelis Huizinga",                             
  "Hendrik Huizinga",                                  
  "Hendrik Huizinga en Sietske Kooij",                 
  "Sietske Kooij",                                     
  "Sietske Klunder",                                   
  "Dirk Bakker",                                       
  "Jo Kooij (1905 - 1985)",                            
  "Jo Kooij en Grietje Bakker",                        
  "Kinderen Fam Kooij: Sietske, Dirk, Gerrit, Froukje, Berendina en Frits", 
  "Hendrik Huizinga en Sietske Kooij met Cees, Jos, Ulfert en Dirk",        
  "Cees Huizinga",                                     
  "Hendrik Huizinga en Sietske Kooij met Cees en Jos"  
]

useHead({
  title: 'Fotoarchief | Familie Huizinga',
  meta: [
    { name: 'description', content: 'Bekijk historische familiefoto\'s van de familie Huizinga en aanverwante families.' }
  ]
})
</script>

<style scoped>
/* CSS exact behouden */
.photo-gallery-page { background-color: #fdfdfb; min-height: 100vh; padding-bottom: 100px; }
.container { max-width: 1300px; margin: 0 auto; padding: 0 30px; }
.gallery-header { text-align: center; padding: 20px 0 40px; }
.gallery-title { font-family: 'Playfair Display', serif; font-size: 4rem; color: #1a2a3a; font-style: italic; margin-bottom: 10px; }
.gallery-subtitle { letter-spacing: 4px; color: #d4af37; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; }
.header-divider { width: 80px; height: 2px; background: #d4af37; margin: 25px auto; }
.gallery-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 40px; }
.photo-card { background: white; padding: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.06); border: 1px solid #e2e2d9; position: relative; transition: all 0.4s ease; transform: rotate(var(--rotation, -1deg)); }
.gallery-item:nth-child(even) .photo-card { --rotation: 1deg; }
.gallery-item:nth-child(3n) .photo-card { --rotation: -0.5deg; }
.photo-card:hover { transform: rotate(0deg) scale(1.04); box-shadow: 0 15px 40px rgba(0,0,0,0.12); z-index: 10; border-color: #d4af37; }
.photo-box { width: 100%; aspect-ratio: 4 / 3; background-color: #f8f8f5; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 1px solid #f0f0e8; }
.gallery-img { max-width: 100%; max-height: 100%; object-fit: contain; display: block; filter: sepia(10%); transition: filter 0.4s ease; padding: 5px; }
.photo-card:hover .gallery-img { filter: sepia(0%); }
.info-box { padding: 15px 5px; text-align: center; min-height: 80px; display: flex; align-items: center; justify-content: center; }
.photo-name { font-family: 'Playfair Display', serif; font-style: italic; font-size: 1.05rem; color: #1a2a3a; margin: 0; line-height: 1.4; }

@media (max-width: 1024px) { .gallery-grid { grid-template-columns: repeat(2, 1fr); gap: 30px; } }
@media (max-width: 650px) { .gallery-grid { grid-template-columns: 1fr; } }
</style>