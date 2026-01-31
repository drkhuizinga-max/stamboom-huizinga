<template>
  <div class="book-container">
    <div class="minimal-nav">
      <h1 class="title">Stamboek Melkema</h1>
      <p class="subtitle">ARCHIEF 1555 — 1883</p>
      
      <div class="page-jump">
        <span>Pagina</span>
        <input 
          type="number" 
          v-model.lazy="pageInput" 
          @change="goToPage"
          class="simple-input"
        />
        <span>van {{ totalPages }}</span>
      </div>
    </div>

    <div class="book-stage">
      <button 
        @click="prevPage" 
        :disabled="currentPage <= 1" 
        class="float-nav prev"
        title="Vorige pagina"
      >
        &lsaquo;
      </button>

      <div class="book-spread" :class="{ 'is-flipping': isFlipping }">
        <div class="book-page left" @mousemove="handleZoom" @mouseleave="resetZoom">
          <img :src="stamboekDir + currentPage + '.jpg'" class="stamboek-img zoom-target" loading="lazy" />
          <div class="spine-shadow-left"></div>
        </div>

        <div class="book-page right" @mousemove="handleZoom" @mouseleave="resetZoom">
          <img v-if="currentPage + 1 <= totalPages" :src="stamboekDir + (currentPage + 1) + '.jpg'" class="stamboek-img zoom-target" loading="lazy" />
          <div class="spine-shadow-right"></div>
        </div>
        <div class="book-center-crease"></div>
      </div>

      <button 
        @click="nextPage" 
        :disabled="currentPage >= totalPages - 1" 
        class="float-nav next"
        title="Volgende pagina"
      >
        &rsaquo;
      </button>
    </div>

    <div class="zoom-hint">
      🔍 Beweeg over de pagina om in te zoomen
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

// Verwijst nu naar de lokale map in /public/images/stamboek/
const stamboekDir = '/images/stamboek/'

const currentPage = ref(1)
const totalPages = 321
const pageInput = ref(1)
const isFlipping = ref(false)

// Meta informatie voor browser/SEO
useHead({
  title: 'Stamboek Melkema (1555-1883) | Familie Huizinga',
  meta: [
    { name: 'description', content: 'Blader door het historische Stamboek Melkema met archiefstukken van de familie Huizinga uit de periode 1555 tot 1883.' }
  ]
})

watch(currentPage, (newVal) => { pageInput.value = newVal })

function nextPage() { if (currentPage.value < totalPages - 1) animate(() => currentPage.value += 2) }
function prevPage() { if (currentPage.value > 1) animate(() => currentPage.value -= 2) }

function goToPage() {
  let target = parseInt(pageInput.value)
  if (isNaN(target) || target < 1) target = 1
  if (target > totalPages) target = totalPages
  if (target % 2 === 0) target--
  animate(() => currentPage.value = target)
}

function animate(fn) {
  isFlipping.value = true
  setTimeout(() => { fn(); isFlipping.value = false }, 250)
}

function handleZoom(e) {
  const container = e.currentTarget
  const img = container.querySelector('.zoom-target')
  if (!img) return
  const x = e.offsetX / container.offsetWidth * 100
  const y = e.offsetY / container.offsetHeight * 100
  img.style.transformOrigin = `${x}% ${y}%`
  img.style.transform = "scale(2.2)"
}

function resetZoom(e) {
  const img = e.currentTarget.querySelector('.zoom-target')
  if (img) img.style.transform = "scale(1)"
}
</script>

<style scoped>
/* CSS blijft volledig gelijk */
.book-container {
  background-color: #fdfdfb;
  min-height: 90vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 30px 0;
}

.minimal-nav {
  text-align: center;
  margin-bottom: 25px;
}

.title {
  font-family: 'Georgia', serif;
  font-size: 2rem;
  color: #1a2a3a;
  margin: 0;
  font-weight: normal;
}

.subtitle {
  font-size: 0.8rem;
  letter-spacing: 4px;
  color: #d4af37;
  margin: 5px 0 20px 0;
  font-weight: bold;
}

.page-jump {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  font-size: 0.9rem;
  color: #7f8c8d;
}

.simple-input {
  width: 50px;
  border: none;
  border-bottom: 1px solid #d4af37;
  background: transparent;
  text-align: center;
  font-size: 1rem;
  color: #1a2a3a;
  font-family: serif;
}

.book-stage {
  position: relative;
  width: 95%;
  max-width: 1200px;
  display: flex;
  align-items: center;
  gap: 30px;
}

.float-nav {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  border: 1px solid #d4af37;
  background: white;
  color: #d4af37;
  font-size: 2rem;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s;
  box-shadow: 0 4px 10px rgba(0,0,0,0.05);
  flex-shrink: 0;
  z-index: 100;
}

.float-nav:hover:not(:disabled) {
  background: #d4af37;
  color: white;
  transform: scale(1.1);
}

.float-nav:disabled {
  opacity: 0.2;
  cursor: not-allowed;
}

.book-spread {
  flex-grow: 1;
  display: grid;
  grid-template-columns: 1fr 1fr;
  background: white;
  box-shadow: 0 15px 45px rgba(0,0,0,0.1);
  border: 1px solid #eee;
}

.book-page {
  position: relative;
  height: 78vh;
  overflow: hidden;
  cursor: zoom-in;
}

.stamboek-img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  object-position: top;
  padding: 15px;
  transition: transform 0.1s ease-out;
}

.book-center-crease {
  position: absolute;
  left: 50%; width: 1px; top: 0; bottom: 0;
  background: rgba(0,0,0,0.05);
  z-index: 5;
}

.zoom-hint {
  margin-top: 15px;
  color: #aaa;
  font-size: 0.8rem;
}

@media (max-width: 850px) {
  .book-spread { grid-template-columns: 1fr; }
  .right { display: none; }
  .title { font-size: 1.5rem; }
}
</style>