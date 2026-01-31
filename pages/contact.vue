<template>
  <div class="contact-page">
    <div class="container">
      
      <section class="contact-header">
        <h1 class="contact-title">Contact</h1>
        <p class="contact-subtitle">FAMILIEGESCHIEDENIS DELEN</p>
        <div class="header-divider"></div>
        <p class="contact-intro">
          Heb je vragen of aanvullingen voor de familiegeschiedenis?
        </p>
      </section>

      <div class="contact-grid">
        <div class="contact-info">
          <div class="info-block">
            <h2 class="info-title">Stel uw vraag</h2>
            <p>Gebruik het formulier om contact op te nemen. We horen graag van u over nieuwe ontdekkingen, verhalen of correcties op de stamboom.</p>
          </div>

          <div class="info-block">
            <h2 class="info-title">Gegevens & Foto's</h2>
            <p>Beschikt u over oude foto's, documenten of informatie over een specifieke familietak? Uw bijdrage helpt om het archief van de familie Huizinga compleet te maken.</p>
          </div>
        </div>

        <div class="contact-form-container">
          <div v-if="verzonden" class="success-box">
            <div class="success-icon">✓</div>
            <h2>Bericht verzonden</h2>
            <p>Bedankt voor uw bericht. We nemen zo snel mogelijk contact met u op.</p>
            <button @click="verzonden = false" class="submit-btn outline">NIEUW BERICHT</button>
          </div>

          <form v-else @submit.prevent="submitForm" class="contact-form">
            <div class="hp-field">
              <input type="text" v-model="form.website" tabindex="-1" autocomplete="off">
            </div>

            <div class="form-group">
              <label for="naam">UW NAAM</label>
              <input type="text" id="naam" v-model="form.naam" required placeholder="Bijv. Hendrik Huizinga">
            </div>

            <div class="form-group">
              <label for="email">EMAILADRES</label>
              <input type="email" id="email" v-model="form.email" required placeholder="uw@emailadres.nl">
            </div>

            <div class="form-group">
              <label for="bijlage">BESTAND MEE STUREN (FOTO OF FILMPJE)</label>
              <input type="file" id="bijlage" @change="handleFileUpload" accept="image/*,video/*,.pdf" class="file-input">
              <small class="file-help">Maximale grootte: 10MB.</small>
            </div>

            <div class="form-group">
              <label for="onderwerp">ONDERWERP</label>
              <input type="text" id="onderwerp" v-model="form.onderwerp" required placeholder="Waar gaat uw bericht over?">
            </div>

            <div class="form-group">
              <label for="bericht">BERICHT</label>
              <textarea id="bericht" v-model="form.bericht" rows="6" required placeholder="Uw bericht of aanvulling..."></textarea>
            </div>

            <button type="submit" class="submit-btn" :disabled="loading">
              {{ loading ? 'BEZIG MET VERZENDEN...' : 'VERSTUUR BERICHT' }}
            </button>
          </form>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const form = ref({
  naam: '',
  email: '',
  onderwerp: '',
  bericht: '',
  website: ''
})

const geselecteerdBestand = ref(null)
const loading = ref(false)
const verzonden = ref(false)

const handleFileUpload = (event) => {
  geselecteerdBestand.value = event.target.files[0]
}

const submitForm = async () => {
  if (form.value.website) return;
  loading.value = true

  try {
    const formData = new FormData()
    formData.append('naam', form.value.naam)
    formData.append('email', form.value.email)
    formData.append('onderwerp', form.value.onderwerp)
    formData.append('bericht', form.value.bericht)
    
    if (geselecteerdBestand.value) {
      formData.append('bijlage', geselecteerdBestand.value)
    }

    const response = await $fetch('/verstuur-mail.php', {
      method: 'POST',
      body: formData
    })

    if (response.status === 'success') {
      verzonden.value = true
      form.value = { naam: '', email: '', onderwerp: '', bericht: '', website: '' }
      geselecteerdBestand.value = null
    }
  } catch (error) {
    console.error('Fout:', error)
    alert('Fout bij verzenden. Is het bestand niet te groot?')
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.contact-page { background-color: #fdfdfb; min-height: 100vh; padding-bottom: 100px; }
.container { max-width: 1100px; margin: 0 auto; padding: 0 30px; }
.hp-field { display: none !important; }
.contact-header { text-align: center; padding: 80px 0 50px; }
.contact-title { font-family: 'Playfair Display', serif; font-size: 4rem; color: #1a2a3a; font-style: italic; }
.contact-subtitle { letter-spacing: 4px; color: #d4af37; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; }
.header-divider { width: 80px; height: 2px; background: #d4af37; margin: 25px auto; }
.contact-intro { font-size: 1.2rem; color: #7f8c8d; font-style: italic; }
.contact-grid { display: grid; grid-template-columns: 1fr 1.5fr; gap: 80px; margin-top: 20px; }
.info-block { margin-bottom: 50px; }
.info-title { font-family: 'Playfair Display', serif; font-size: 1.8rem; color: #1a2a3a; margin-bottom: 15px; font-style: italic; }
.info-block p { line-height: 1.8; color: #4a5568; font-size: 1.05rem; }
.contact-form-container { background: white; padding: 50px; box-shadow: 0 15px 40px rgba(0,0,0,0.06); border: 1px solid #e2e2d9; min-height: 500px; }
.form-group { margin-bottom: 30px; }
label { display: block; font-size: 0.7rem; font-weight: 700; letter-spacing: 2px; color: #d4af37; margin-bottom: 10px; }
input, textarea { width: 100%; padding: 15px; border: 1px solid #e2e2d9; background: #fdfdfb; font-size: 1rem; transition: all 0.3s ease; }
input:focus, textarea:focus { outline: none; border-color: #d4af37; background: white; box-shadow: 0 5px 15px rgba(212, 175, 55, 0.1); }
.file-input { padding: 10px; background: #fdfdfb; border: 1px dashed #d4af37; cursor: pointer; }
.file-help { display: block; margin-top: 5px; font-size: 0.75rem; color: #7f8c8d; }
.submit-btn { width: 100%; padding: 18px; background-color: #1a2a3a; color: #d4af37; border: none; font-weight: 700; letter-spacing: 3px; cursor: pointer; transition: 0.3s; text-transform: uppercase; }
.submit-btn:hover:not(:disabled) { background-color: #d4af37; color: white; }
.submit-btn:disabled { background-color: #e5e7eb; color: #9ca3af; cursor: not-allowed; }
.success-box { text-align: center; padding: 60px 0; display: flex; flex-direction: column; align-items: center; }
.success-icon { font-size: 5rem; color: #d4af37; margin-bottom: 25px; line-height: 1; }
.submit-btn.outline { background: transparent; color: #1a2a3a; border: 2px solid #1a2a3a; margin-top: 30px; width: auto; padding: 12px 40px; }

@media (max-width: 900px) {
  .contact-grid { grid-template-columns: 1fr; gap: 50px; }
  .contact-title { font-size: 3rem; }
  .contact-form-container { padding: 30px; }
}
</style>