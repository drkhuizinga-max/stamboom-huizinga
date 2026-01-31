<template>
  <div class="kaart-page">
    <div class="container content-container">
      
      <section class="hero-section">
        <h1 class="hero-title">Het Landschap in Kaart</h1>
        <p class="hero-subtitle">VERKEN DE BAKERMAT VAN DE FAMILIE</p>
        <div class="hero-divider"></div>
      </section>

      <section class="cards-grid">
        <div v-for="(kaart, index) in kaarten" :key="index" class="card-item">
          <div class="polaroid clickable" @click="openModal(kaart)">
            <img :src="imgFoto + kaart.src.split('/').pop()" :alt="kaart.titel" loading="lazy" />
            <p class="polaroid-caption">{{ kaart.titel }}</p>
            <div class="zoom-hint">Klik voor details 🔍</div>
          </div>
        </div>
      </section>

      <Transition name="fade">
        <div v-if="selectedKaart" class="modal-overlay" @click="closeModal">
          
          <div class="zoom-toolbar" @click.stop>
            <button @click="zoomOut" class="tool-btn">−</button>
            <span class="zoom-label">{{ Math.round(zoomLevel * 100) }}%</span>
            <button @click="zoomIn" class="tool-btn">+</button>
            <button @click="resetZoom" class="tool-btn reset">Reset</button>
            <button @click="closeModal" class="tool-btn close">Sluiten ✖</button>
          </div>

          <div class="modal-viewport" @click.stop>
            <div 
              class="scroll-wrapper"
              :style="{ 
                cursor: zoomLevel > 1 ? 'grab' : 'default',
                width: '100%',
                height: '100%',
                display: 'flex',
                justifyContent: 'center'
              }"
            >
              <img 
                :src="imgFoto + selectedKaart.src.split('/').pop()" 
                class="modal-img" 
                :style="{ 
                  transform: `scale(${zoomLevel})`,
                  transformOrigin: 'top center' 
                }"
              />
            </div>
          </div>
          
          <div class="modal-footer" v-if="zoomLevel > 1">
            <p>Scroll of sleep om de kaart te verkennen</p>
          </div>
        </div>
      </Transition>

    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

// Gecentraliseerde URL logica - nu lokaal
const imgFoto = '/images/foto/'

const selectedKaart = ref(null)
const zoomLevel = ref(1)

const kaarten = [
  { src: 'kaart1.webp', titel: 'Gemeente Ulrum (Historisch)' },
  { src: 'kaart2.webp', titel: 'Gemeente Leens (Historisch)' }
]

const openModal = (kaart) => {
  selectedKaart.value = kaart
  zoomLevel.value = 1
  document.body.style.overflow = 'hidden'
}

const closeModal = () => {
  selectedKaart.value = null
  document.body.style.overflow = 'auto'
}

const zoomIn = () => { if (zoomLevel.value < 4) zoomLevel.value += 0.5 }
const zoomOut = () => { if (zoomLevel.value > 0.5) zoomLevel.value -= 0.5 }
const resetZoom = () => { zoomLevel.value = 1 }

useHead({
  title: 'Historische Kaarten | Familie Huizinga',
  meta: [
    { name: 'description', content: 'Verken de historische kaarten van Ulrum, Leens en de bakermat van de familie Huizinga.' }
  ]
})
</script>

<style scoped>
/* Jouw CSS blijft ongewijzigd */
.kaart-page { 
  background: #fdfdfb; 
  min-height: 100vh; 
  padding-bottom: 80px; 
  width: 100%;
  overflow-x: hidden;
}

.content-container {
  max-width: 1100px;
  margin: 0 auto;
  padding: 0 20px;
}

.hero-section { text-align: center; padding: 60px 0 40px; }
.hero-title { font-family: 'Playfair Display', serif; font-size: 3.5rem; color: #1a2a3a; font-style: italic; }
.hero-subtitle { text-transform: uppercase; letter-spacing: 3px; font-size: 0.85rem; color: #d4af37; font-weight: 700; margin-top: 10px; }
.hero-divider { width: 80px; height: 2px; background: #d4af37; margin: 25px auto; }

.cards-grid { 
  display: grid; 
  grid-template-columns: repeat(auto-fit, minmax(320px, 450px)); 
  gap: 40px; 
  justify-content: center;
}

.polaroid { 
  background: white; 
  padding: 12px 12px 35px; 
  box-shadow: 0 10px 25px rgba(0,0,0,0.08); 
  border: 1px solid #e2e2d9; 
  transition: all 0.4s ease; 
  cursor: pointer; 
  position: relative; 
}
.polaroid:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 35px rgba(0,0,0,0.12);
}
.polaroid img { width: 100%; height: auto; display: block; }
.polaroid-caption { font-family: 'Playfair Display', serif; font-style: italic; text-align: center; margin-top: 15px; color: #1a2a3a; font-size: 1.1rem; }
.zoom-hint { text-align: center; font-size: 0.8rem; color: #9ca3af; margin-top: 5px; }

.modal-overlay { 
  position: fixed; 
  top: 0; 
  left: 0; 
  width: 100%; 
  height: 100%; 
  background: rgba(26, 42, 58, 0.98); 
  z-index: 9999; 
  display: flex; 
  flex-direction: column; 
  align-items: center; 
}

.modal-viewport { 
  width: 95vw; 
  height: 75vh; 
  overflow: auto; 
  background: #000; 
  border: 1px solid #333; 
  position: relative; 
}

.modal-img { 
  max-width: 90%; 
  height: auto; 
  transition: transform 0.2s ease-out; 
  margin-top: 20px;
}

.zoom-toolbar { 
  margin: 20px 0; 
  background: #fff; 
  padding: 10px 25px; 
  border-radius: 50px; 
  display: flex; 
  align-items: center; 
  gap: 15px; 
  border-top: 3px solid #d4af37; 
}

.tool-btn { 
  background: #f4f1ea; 
  border: none; 
  padding: 8px 18px; 
  border-radius: 20px; 
  cursor: pointer; 
  font-weight: bold; 
  display: flex;
  align-items: center;
  justify-content: center;
}
.tool-btn:hover { background: #d4af37; color: white; }

.modal-footer {
  color: #fff;
  margin-top: 15px;
  font-size: 0.9rem;
  font-style: italic;
}

.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

@media (max-width: 600px) {
  .hero-title { font-size: 2.5rem; }
  .cards-grid { grid-template-columns: 1fr; }
  .zoom-toolbar { padding: 8px 15px; gap: 8px; flex-wrap: wrap; justify-content: center; }
}
</style>