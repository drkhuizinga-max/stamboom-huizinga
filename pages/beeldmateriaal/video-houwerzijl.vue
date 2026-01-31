<template>
  <div class="video-gallery-page">
    <div class="container">
      
      <section class="gallery-header">
        <h1 class="gallery-title">Houwerzijl in Beeld</h1>
        <p class="gallery-subtitle">EEN HISTORISCHE TERUGBLIK</p>
        <div class="header-divider"></div>
        <p class="gallery-description">Bekijk de verzameling video's over het dorp en haar geschiedenis.</p>
      </section>

      <div class="video-grid">
        <div v-for="n in 11" :key="n" class="video-item">
          <div class="video-card">
            <div class="video-container">
              <video controls preload="metadata" class="video-player">
                <source :src="videoDir + 'houwerzijl_deel_' + n + '.mp4'" type="video/mp4">
                Je browser ondersteunt geen video.
              </video>
            </div>
            <div class="video-info">
              <h3 class="video-titel">Houwerzijl — Deel {{ n }}</h3>
              <div class="video-label">Historisch Archief</div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
// De lokale map in jouw Nuxt/Vue project (meestal public/video/)
const videoDir = '/video/'

useHead({
  title: 'Houwerzijl in Beeld | Familie Huizinga',
  meta: [
    { name: 'description', content: 'Historische videobeelden van Houwerzijl. Een visuele terugblik op het dorp en zijn bewoners.' }
  ]
})
</script>

<style scoped>
/* Jouw stijlen zijn 100% behouden */
.video-gallery-page {
  background-color: #fdfdfb;
  min-height: 100vh;
  padding-bottom: 100px;
}
.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 30px;
}
.gallery-header { text-align: center; padding: 80px 0 50px; }
.gallery-title { 
  font-family: 'Playfair Display', serif; 
  font-size: 4rem; 
  color: #1a2a3a; 
  font-style: italic;
  margin-bottom: 10px;
}
.gallery-subtitle { 
  letter-spacing: 4px; 
  color: #d4af37; 
  font-size: 0.85rem; 
  font-weight: 700;
  text-transform: uppercase;
}
.gallery-description { 
  margin-top: 20px; 
  color: #7f8c8d; 
  font-size: 1.1rem; 
  font-style: italic; 
  max-width: 600px;
  margin-left: auto;
  margin-right: auto;
}
.header-divider { 
  width: 80px; 
  height: 2px; 
  background: #d4af37; 
  margin: 25px auto; 
}
.video-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 50px;
}
.video-item { perspective: 1000px; }
.video-card { 
  background: white; 
  padding: 15px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.06);
  border: 1px solid #e2e2d9;
  transform: rotate(-0.5deg);
  transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
}
.video-item:nth-child(even) .video-card { transform: rotate(0.5deg); }
.video-card:hover { 
  transform: rotate(0deg) scale(1.03); 
  box-shadow: 0 20px 45px rgba(0,0,0,0.12);
  z-index: 5;
  border-color: #d4af37;
}
.video-container { 
  width: 100%; 
  aspect-ratio: 16 / 9; 
  background: #000; 
  overflow: hidden; 
  border: 1px solid #f0f0e8;
}
.video-player { width: 100%; height: 100%; display: block; }
.video-info { padding: 25px 10px; text-align: center; }
.video-titel { font-family: 'Playfair Display', serif; font-size: 1.4rem; margin: 0; color: #1a2a3a; font-style: italic; }
.video-label { margin-top: 10px; font-size: 0.75rem; color: #d4af37; text-transform: uppercase; letter-spacing: 2px; font-weight: bold; }

@media (max-width: 950px) { 
  .video-grid { grid-template-columns: 1fr; gap: 40px; }
  .gallery-title { font-size: 3rem; }
  .video-card { transform: none !important; max-width: 650px; margin: 0 auto; }
}
@media (max-width: 600px) {
  .container { padding: 0 20px; }
  .gallery-title { font-size: 2.5rem; }
}
</style>