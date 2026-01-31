<script setup>
// 1. Haal alle personen op om te bewerken
const { data: allePersonen, refresh } = await useAsyncData('admin-personen', () => {
  return queryContent().only(['id', 'naam', 'foto_url', '_path']).find()
})

const geselecteerdePersoon = ref(null)
const uploadStatus = ref('')

// 2. Selecteer een persoon om aan te passen
const kiesPersoon = (p) => {
  geselecteerdePersoon.value = { ...p }
  uploadStatus.value = ''
}

// 3. Foto Upload Logica
const handleFileUpload = async (event) => {
  const file = event.target.files[0]
  if (!file) return

  uploadStatus.value = 'Uploaden...'
  const formData = new FormData()
  formData.append('file', file)

  try {
    const response = await $fetch('/api/upload', {
      method: 'POST',
      body: formData
    })

    if (response.url) {
      // Update het pad in het formulier
      geselecteerdePersoon.value.foto_url = response.url
      uploadStatus.value = 'Foto succesvol geüpload!'
    }
  } catch (err) {
    uploadStatus.value = 'Fout bij uploaden: ' + err.message
  }
}

// 4. Opslaan (Opmerking: hiervoor is een server-side save script nodig)
const opslaan = async () => {
  // Hier zou je een fetch naar een 'save' API doen om de .md file bij te werken
  console.log('Opslaan naar Markdown:', geselecteerdePersoon.value)
  alert('Gegevens bijgewerkt in geheugen. Zorg dat je server-side opslaan hebt geconfigureerd.')
}
</script>

<template>
  <div class="admin-container">
    <aside class="admin-sidebar">
      <h2>Beheer</h2>
      <div class="search-mini">
        <input type="text" placeholder="Filter naam..." class="admin-input" />
      </div>
      <ul class="person-list">
        <li v-for="p in allePersonen" :key="p.id" @click="kiesPersoon(p)" :class="{ active: geselecteerdePersoon?.id === p.id }">
          {{ p.naam }}
        </li>
      </ul>
    </aside>

    <main class="admin-main">
      <div v-if="geselecteerdePersoon" class="edit-form">
        <h1>Bewerken: {{ geselecteerdePersoon.naam }}</h1>
        
        <div class="form-group">
          <label>Naam</label>
          <input v-model="geselecteerdePersoon.naam" type="text" class="admin-input" />
        </div>

        <div class="form-group">
          <label>Huidige Foto Pad</label>
          <input v-model="geselecteerdePersoon.foto_url" type="text" class="admin-input" readonly />
        </div>

        <div class="form-group">
          <label>Nieuwe Foto Uploaden</label>
          <input type="file" @change="handleFileUpload" accept="image/*" class="file-input" />
          <p v-if="uploadStatus" class="status-msg">{{ uploadStatus }}</p>
        </div>

        <div class="photo-preview" v-if="geselecteerdePersoon.foto_url">
          <label>Voorbeeld:</label>
          <img :src="'/' + geselecteerdePersoon.foto_url" alt="Preview" class="preview-img" />
        </div>

        <button @click="opslaan" class="save-btn">Wijzigingen Opslaan</button>
      </div>
      <div v-else class="empty-state">
        <p>Selecteer een persoon uit de lijst om te bewerken.</p>
      </div>
    </main>
  </div>
</template>

<style scoped>
.admin-container { display: flex; min-height: 100vh; background: #f4f7f6; font-family: sans-serif; }

.admin-sidebar { width: 300px; background: #fff; border-right: 1px solid #ddd; padding: 20px; overflow-y: auto; }
.person-list { list-style: none; padding: 0; margin-top: 20px; }
.person-list li { padding: 10px; border-bottom: 1px solid #eee; cursor: pointer; transition: 0.2s; }
.person-list li:hover { background: #f9f4e8; }
.person-list li.active { background: #24313f; color: white; }

.admin-main { flex: 1; padding: 40px; background: #fff; margin: 20px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }

.form-group { margin-bottom: 20px; }
.admin-input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; margin-top: 5px; }
.save-btn { background: #cfa84e; color: white; border: none; padding: 15px 30px; border-radius: 4px; cursor: pointer; font-weight: bold; }

.preview-img { max-width: 200px; border-radius: 8px; display: block; margin-top: 10px; border: 4px solid #f4f7f6; }
.status-msg { font-size: 0.85rem; color: #cfa84e; margin-top: 5px; }

.empty-state { display: flex; align-items: center; justify-content: center; height: 100%; color: #999; }
</style>