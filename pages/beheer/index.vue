<script setup>
import { ref, onMounted } from 'vue' // Belangrijk: ref en onMounted moeten hier staan!
import { getApiUrl } from '../../config.js'

// --- AUTH LOGICA ---
const ingelogd = ref(false)
const wachtwoordInvoer = ref('')
const HET_WACHTWOORD = "Smilde"

// --- DYNAMISCHE API CONFIG ---
const API = ref(getApiUrl()) // Slechts één keer declareren
const setupUrl = ref('')

const saveApiUrl = () => {
  if (setupUrl.value.includes('http')) {
    localStorage.setItem('user_stamboom_api', setupUrl.value)
    alert("Instellingen opgeslagen! De app wordt herstart.")
    window.location.reload()
  } else {
    alert("Voer een geldige URL in (inclusief http:// of https://)")
  }
}

const resetConfig = () => {
  if(confirm("Wil je de API instellingen wissen?")) {
    localStorage.removeItem('user_stamboom_api')
    window.location.reload()
  }
}

const controleerWachtwoord = () => {
  if (wachtwoordInvoer.value === HET_WACHTWOORD) {
    ingelogd.value = true
    sessionStorage.setItem('stamboom_auth', 'true')
  } else {
    alert("Onjuist!")
  }
}

onMounted(() => {
  if (sessionStorage.getItem('stamboom_auth') === 'true') ingelogd.value = true
})

// --- DATA & UPLOAD LOGICA ---
const searchQuery = ref('')
const searchResults = ref([])
const selectedPersoon = ref(null)
const saving = ref(false)
const dragActive = ref({ foto: false, scans: false })

// Helper voor previews (gebruikt nu de dynamische API URL)
const getPreviewUrl = (fileName) => {
  if (!fileName || !API.value) return null
  return `${API.value}/uploads/${fileName.trim()}`
}

const doeZoekopdracht = async () => {
  if (!API.value || searchQuery.value.length < 2) return
  try {
    const res = await fetch(`${API.value}/data.php?search=${encodeURIComponent(searchQuery.value)}`)
    searchResults.value = await res.json()
  } catch (e) { console.error(e) }
}

const laadPersoon = async (id) => {
  if (!API.value) return
  try {
    const res = await fetch(`${API.value}/data.php?slug=${id}&t=${Date.now()}`)
    selectedPersoon.value = await res.json()
  } catch (e) { alert("Fout bij laden") }
}

const uploadBestand = async (file, doelVeld) => {
  if (!file || !API.value) return
  const formData = new FormData()
  formData.append('foto', file) 
  
  try {
    const res = await fetch(`${API.value}/upload.php`, { method: 'POST', body: formData })
    const data = await res.json()
    
    if (data.success) {
      if (doelVeld === 'scans') {
        const huidig = selectedPersoon.value.scans
        selectedPersoon.value.scans = huidig && huidig.trim() !== "" 
          ? `${huidig}, ${data.fileName}` 
          : data.fileName
      } else {
        selectedPersoon.value[doelVeld] = data.fileName
      }
      await updateData()
    } else {
      alert("Upload fout: " + data.message)
    }
  } catch (e) { alert("Server upload mislukt") }
}

const verwijderScan = async (index) => {
  const scansLijst = selectedPersoon.value.scans.split(',').map(s => s.trim()).filter(s => s !== "");
  const bestandOmTeWissen = scansLijst[index];

  if (!confirm(`Weet je zeker dat je ${bestandOmTeWissen} permanent wilt verwijderen?`)) return;

  try {
    const res = await fetch(`${API.value}/delete_file.php`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ fileName: bestandOmTeWissen })
    });
    const result = await res.json();

    if (result.success) {
      scansLijst.splice(index, 1);
      selectedPersoon.value.scans = scansLijst.join(', ');
      await updateData();
    } else {
      alert("Fout bij verwijderen: " + result.message);
    }
  } catch (e) { alert("Server fout") }
}

const handleDrop = (e, veld) => {
  dragActive.value.foto = false;
  dragActive.value.scans = false;
  const files = e.dataTransfer.files;
  if (files && files.length > 0) {
    uploadBestand(files[0], veld);
  }
};

const updateData = async () => {
  if (!API.value) return
  saving.value = true
  try {
    const res = await fetch(`${API.value}/update.php`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(selectedPersoon.value)
    })
    const result = await res.json()
    if (result.success) console.log("Opgeslagen")
  } finally { saving.value = false }
}
</script>

<template>
  <div class="admin-full">
    <div v-if="!API" class="login-overlay">
      <div class="login-box">
        <h3>Software Installatie</h3>
        <p style="font-size: 0.8rem; margin-bottom: 15px; color: #666;">Voer de URL van uw online API in om te koppelen.</p>
        <input v-model="setupUrl" type="text" placeholder="https://jouw-domein.nl/api" />
        <button @click="saveApiUrl">Koppelen</button>
      </div>
    </div>

    <div v-else-if="!ingelogd" class="login-overlay">
      <div class="login-box">
        <h3>Admin Toegang</h3>
        <input v-model="wachtwoordInvoer" type="password" @keyup.enter="controleerWachtwoord" placeholder="Wachtwoord..." />
        <button @click="controleerWachtwoord">Inloggen</button>
        <button @click="resetConfig" class="btn-reset">API Reset</button>
      </div>
    </div>

    <div v-else class="app-container">
      <aside class="sidebar">
        <div class="logo-area">STAMBOOM <span>Admin</span></div>
        <div class="search-box">
          <input v-model="searchQuery" @input="doeZoekopdracht" placeholder="Zoek op naam..." />
        </div>
        <nav class="person-list">
          <div v-for="p in searchResults" :key="p.id" @click="laadPersoon(p.id)" :class="{active: selectedPersoon?.id === p.id}" class="person-item">
            <small>#{{ p.id }}</small> {{ p.naam }}
          </div>
        </nav>
      </aside>

      <main class="editor-main">
        <div v-if="selectedPersoon" class="flex-wrapper">
          <header class="header-bar">
            <h2>{{ selectedPersoon.naam }} <span class="id-tag">ID: {{ selectedPersoon.id }}</span></h2>
            <div class="header-btns">
              <button @click="updateData" class="btn-primary" :disabled="saving">
                {{ saving ? 'Verwerken...' : 'Wijzigingen Opslaan' }}
              </button>
            </div>
          </header>

          <div class="editor-content">
            <div class="top-panels">
              <section class="panel">
                <div class="panel-h">Basisgegevens</div>
                <div class="panel-b">
                  <div class="input-group"><label>Naam</label><input v-model="selectedPersoon.naam" /></div>
                  <div class="row">
                    <div class="input-group"><label>Geboorte</label><div class="split"><input v-model="selectedPersoon.geboortedatum" placeholder="Datum" /><input v-model="selectedPersoon.geboorteplaats" placeholder="Plaats" /></div></div>
                    <div class="input-group"><label>Overlijden</label><div class="split"><input v-model="selectedPersoon.overlijdensdatum" placeholder="Datum" /><input v-model="selectedPersoon.overlijdensplaats" placeholder="Plaats" /></div></div>
                  </div>
                </div>
              </section>

              <section class="panel">
                <div class="panel-h">Relaties & Media</div>
                <div class="panel-b flex-col">
                  <div class="id-row">
                    <div class="id-field"><label>Vader (ID)</label><input v-model.number="selectedPersoon.vader_id" /></div>
                    <div class="id-field"><label>Moeder (ID)</label><input v-model.number="selectedPersoon.moeder_id" /></div>
                    <div class="id-field"><label>Partner (ID)</label><input v-model.number="selectedPersoon.partner_id" /></div>
                  </div>
                  <div class="media-row">
                    <div class="drop-area" :class="{active: dragActive.foto}" @dragover.prevent="dragActive.foto=true" @dragleave="dragActive.foto=false" @drop.prevent="e => handleDrop(e, 'foto_url')">
                      <label>Hoofdfoto</label>
                      <div class="media-preview" v-if="selectedPersoon.foto_url">
                        <img :src="getPreviewUrl(selectedPersoon.foto_url)" />
                      </div>
                      <input v-model="selectedPersoon.foto_url" placeholder="Sleep foto hier..." />
                    </div>

                    <div class="drop-area" :class="{active: dragActive.scans}" @dragover.prevent="dragActive.scans=true" @dragleave="dragActive.scans=false" @drop.prevent="e => handleDrop(e, 'scans')">
                      <label>Scans & Documenten</label>
                      <div class="scans-admin-list" v-if="selectedPersoon.scans">
                        <div v-for="(scan, idx) in selectedPersoon.scans.split(',').filter(s => s.trim() !== '')" :key="idx" class="scan-item-admin">
                          <img :src="getPreviewUrl(scan)" />
                          <button @click.stop="verwijderScan(idx)" class="mini-del-btn" title="Permanent wissen">&times;</button>
                        </div>
                      </div>
                      <input v-model="selectedPersoon.scans" placeholder="Sleep scans hier..." />
                    </div>
                  </div>
                </div>
              </section>
            </div>

            <section class="panel bio-panel">
              <div class="panel-h">Biografie & Notities</div>
              <div class="panel-b bio-body">
                <textarea v-model="selectedPersoon.notities" placeholder="Schrijf hier de familiegeschiedenis..."></textarea>
              </div>
            </section>
          </div>
        </div>
        <div v-else class="empty-state">Selecteer een persoon om te bewerken</div>
      </main>
    </div>
  </div>
</template>

<style scoped>
/* Behoud je bestaande styles en voeg deze toe voor de reset knop */
.btn-reset { 
  margin-top: 10px; 
  background: none; 
  border: none; 
  color: #999; 
  font-size: 0.7rem; 
  text-decoration: underline; 
  cursor: pointer; 
}

/* ... (rest van je CSS) ... */
.admin-full { height: 100vh; background: #f5f7f9; overflow: hidden; font-family: sans-serif; }
.app-container { display: flex; height: 100vh; }
.sidebar { width: 260px; background: #1a2a3a; color: white; display: flex; flex-direction: column; }
.logo-area { padding: 20px; font-weight: bold; border-bottom: 1px solid rgba(255,255,255,0.1); color: #cfa84e; text-transform: uppercase; letter-spacing: 1px; }
.logo-area span { color: white; opacity: 0.6; font-weight: normal; font-size: 0.8rem; }
.search-box { padding: 15px; }
.search-box input { width: 100%; padding: 10px; border-radius: 4px; border: none; background: rgba(255,255,255,0.1); color: white; }
.person-list { flex: 1; overflow-y: auto; }
.person-item { padding: 12px 20px; border-bottom: 1px solid rgba(255,255,255,0.05); cursor: pointer; font-size: 0.85rem; }
.person-item.active { background: #cfa84e; color: #1a2a3a; font-weight: bold; }
.editor-main { flex: 1; display: flex; flex-direction: column; background: #f0f2f5; }
.header-bar { padding: 15px 25px; background: white; border-bottom: 1px solid #dcdfe6; display: flex; justify-content: space-between; align-items: center; }
.btn-primary { background: #27ae60; color: white; border: none; padding: 10px 20px; border-radius: 4px; font-weight: bold; cursor: pointer; }
.editor-content { padding: 20px; display: flex; flex-direction: column; gap: 20px; overflow-y: auto; }
.top-panels { display: grid; grid-template-columns: 1fr 480px; gap: 20px; }
.panel { background: white; border: 1px solid #dcdfe6; border-radius: 6px; }
.panel-h { padding: 10px 15px; background: #f8f9fa; border-bottom: 1px solid #dcdfe6; color: #cfa84e; font-size: 0.7rem; font-weight: bold; text-transform: uppercase; }
.panel-b { padding: 20px; }
.drop-area { border: 2px dashed #dcdfe6; padding: 12px; border-radius: 6px; background: #fafafa; position: relative; margin-bottom: 10px; }
.drop-area.active { border-color: #cfa84e; background: #fffdf5; }
.media-preview { width: 100%; height: 80px; background: #eee; margin-bottom: 10px; border-radius: 4px; overflow: hidden; }
.media-preview img { width: 100%; height: 100%; object-fit: cover; }
.scans-admin-list { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 10px; }
.scan-item-admin { width: 70px; height: 70px; position: relative; border: 1px solid #ddd; background: #eee; border-radius: 4px; overflow: hidden; }
.scan-item-admin img { width: 100%; height: 100%; object-fit: cover; }
.mini-del-btn { position: absolute; top: 0; right: 0; background: rgba(220, 53, 69, 0.9); color: white; border: none; width: 22px; height: 22px; cursor: pointer; font-weight: bold; font-size: 16px; display: flex; align-items: center; justify-content: center; }
.mini-del-btn:hover { background: #dc3545; }
textarea { width: 100%; min-height: 250px; padding: 20px; border: none; outline: none; font-size: 1rem; line-height: 1.6; }
.login-overlay { height: 100vh; display: flex; align-items: center; justify-content: center; background: #1a2a3a; }
.login-box { background: white; padding: 40px; border-radius: 8px; text-align: center; }
.login-box input { width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 4px; }
.login-box button { width: 100%; padding: 10px; background: #cfa84e; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
</style>