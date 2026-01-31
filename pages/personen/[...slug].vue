<script setup>
const route = useRoute()

// 1. Data fetching
const { data: allePersonen } = await useAsyncData('stamboom-cache', () => queryContent().find())
const { data: persoon } = await useAsyncData(`p-${route.path}`, () => queryContent().where({ _path: route.path }).findOne())

// 2. SEO
useHead({
  title: () => persoon.value?.naam ? `${persoon.value.naam} - Huizinga Genealogie` : 'Laden...',
  meta: [{ name: 'description', content: () => persoon.value?.naam ? `Stamboom van ${persoon.value.naam}` : 'Huizinga' }]
})

// 3. Zoeklogica
const searchQuery = ref('')
const zoekResultaten = computed(() => {
  const q = searchQuery.value.toLowerCase().trim()
  if (q.length < 2 || !allePersonen.value) return []
  return allePersonen.value.filter(p => p.naam?.toLowerCase().includes(q)).slice(0, 10)
})

// 4. Helpers & Relaties
const getYear = (d) => d ? (d.match(/\d{4}/) || [''])[0] : ''
const getP = (id) => id && allePersonen.value ? allePersonen.value.find(p => String(p.id) === String(id)) : null
const getImageUrl = (path) => path ? `/${path.replace(/^\//, '')}` : null

const vader = computed(() => getP(persoon.value?.vader_id))
const moeder = computed(() => getP(persoon.value?.moeder_id))
const partner = computed(() => getP(persoon.value?.partner_id))
const opaV = computed(() => getP(vader.value?.vader_id))
const omaV = computed(() => getP(vader.value?.moeder_id))
const opaM = computed(() => getP(moeder.value?.vader_id))
const omaM = computed(() => getP(moeder.value?.moeder_id))

const kinderen = computed(() => {
  if (!persoon.value || !allePersonen.value) return []
  const myId = String(persoon.value.id)
  return allePersonen.value.filter(p => String(p.vader_id) === myId || String(p.moeder_id) === myId)
})

const scanLijst = computed(() => persoon.value?.scans ? persoon.value.scans.split(',').map(s => s.trim()) : [])
</script>

<template>
  <div class="stamboom-app">
    <aside class="sidebar">
      <div class="sidebar-top">
        <h1 class="sidebar-logo">Huizinga<span>STAMBOOM</span></h1>
        <div class="search-container">
          <p class="search-title">ZOEK EEN VOOROUDER...</p>
          <div class="search-wrapper">
            <input v-model="searchQuery" type="text" placeholder="Typ naam..." class="sidebar-input" />
            <span class="search-icon">🔍</span>
            <div v-if="zoekResultaten.length" class="search-results">
              <NuxtLink v-for="res in zoekResultaten" :key="res._path" :to="res._path" class="result-link" @click="searchQuery = ''">
                <div class="result-name">{{ res.naam }}</div>
                <div class="result-meta">{{ getYear(res.geboortedatum) }}</div>
              </NuxtLink>
            </div>
          </div>
        </div>
        <NuxtLink to="/stamboom" class="back-link">← Terug naar lijst</NuxtLink>
      </div>
    </aside>

    <main class="main-content" v-if="persoon">
      <div class="tree-container">
        <div class="tree-level">
          <div class="nodes-row">
            <NuxtLink v-if="opaV" :to="opaV._path" class="node small">
              <span class="node-label">GROOTVADER</span>
              <span class="node-name">{{ opaV.naam }}</span>
              <span class="node-year">{{ getYear(opaV.geboortedatum) }}</span>
            </NuxtLink>
            <NuxtLink v-if="omaV" :to="omaV._path" class="node small">
              <span class="node-label">GROOTMOEDER</span>
              <span class="node-name">{{ omaV.naam }}</span>
              <span class="node-year">{{ getYear(omaV.geboortedatum) }}</span>
            </NuxtLink>
            <NuxtLink v-if="opaM" :to="opaM._path" class="node small">
              <span class="node-label">GROOTVADER</span>
              <span class="node-name">{{ opaM.naam }}</span>
              <span class="node-year">{{ getYear(opaM.geboortedatum) }}</span>
            </NuxtLink>
            <NuxtLink v-if="omaM" :to="omaM._path" class="node small">
              <span class="node-label">GROOTMOEDER</span>
              <span class="node-name">{{ omaM.naam }}</span>
              <span class="node-year">{{ getYear(omaM.geboortedatum) }}</span>
            </NuxtLink>
          </div>
        </div>

        <div class="tree-level">
          <div class="nodes-row">
            <NuxtLink v-if="vader" :to="vader._path" class="node">
              <span class="node-label">VADER</span>
              <span class="node-name">{{ vader.naam }}</span>
              <span class="node-year">{{ getYear(vader.geboortedatum) }}</span>
            </NuxtLink>
            <NuxtLink v-if="moeder" :to="moeder._path" class="node">
              <span class="node-label">MOEDER</span>
              <span class="node-name">{{ moeder.naam }}</span>
              <span class="node-year">{{ getYear(moeder.geboortedatum) }}</span>
            </NuxtLink>
          </div>
        </div>

        <section class="focus-box">
          <img v-if="persoon.foto_url" :src="getImageUrl(persoon.foto_url)" class="focus-portrait" />
          <h2 class="focus-name">{{ persoon.naam }}</h2>
          <div class="focus-details">
            <p class="focus-dates">{{ persoon.geboortedatum }} — {{ persoon.overlijdensdatum || 'heden' }}</p>
            <p class="focus-location" v-if="persoon.geboorteplaats || persoon.overlijdensplaats">
              <span v-if="persoon.geboorteplaats">Geboren te {{ persoon.geboorteplaats }}</span>
              <span v-if="persoon.overlijdensplaats"> — Overleden te {{ persoon.overlijdensplaats }}</span>
            </p>
          </div>
        </section>

        <div class="tree-level bottom-info">
          <div v-if="partner" class="relation-group">
            <p class="group-title">ECHTGENOOT / PARTNER</p>
            <NuxtLink :to="partner._path" class="node partner">
              {{ partner.naam }} ({{ getYear(partner.geboortedatum) }})
            </NuxtLink>
          </div>
          <div v-if="kinderen.length" class="relation-group">
            <p class="group-title">KINDEREN</p>
            <div class="children-grid">
              <NuxtLink v-for="k in kinderen" :key="k.id" :to="k._path" class="node child">
                {{ k.naam }} <span class="y">{{ getYear(k.geboortedatum) }}</span>
              </NuxtLink>
            </div>
          </div>
        </div>
      </div>

      <section class="content-body biography-section" v-if="persoon.notities">
        <h3 class="section-title">Biografie</h3>
        <div class="biography-text">{{ persoon.notities }}</div>
      </section>

      <section class="content-body documents-section" v-if="scanLijst.length">
        <h3 class="section-title">Documenten & Scans</h3>
        <div class="scans-grid">
          <a v-for="s in scanLijst" :key="s" :href="'/' + s" target="_blank" class="scan-thumb">
            <img :src="'/' + s" />
          </a>
        </div>
      </section>
    </main>
  </div>
</template>

<style scoped>
.stamboom-app { display: flex; min-height: 100vh; background: #fdfcf9; font-family: 'Libre Baskerville', serif; }

/* Sidebar */
.sidebar { width: 260px; background: #fff; padding: 40px 20px; border-right: 1px solid #eee; position: sticky; top: 0; height: 100vh; }
.sidebar-logo { font-size: 1.2rem; margin-bottom: 30px; font-weight: bold; }
.sidebar-logo span { display: block; color: #cfa84e; font-size: 0.7rem; letter-spacing: 2px; }
.search-wrapper { position: relative; }
.sidebar-input { width: 100%; padding: 10px; border: 1px solid #333; border-radius: 4px; font-size: 0.85rem; }
.search-icon { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: #999; }
.search-results { position: absolute; top: 100%; left: 0; right: -40px; background: white; border: 1px solid #cfa84e; z-index: 1000; box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
.result-link { display: block; padding: 12px; border-bottom: 1px solid #eee; text-decoration: none; color: #333; }

/* Boom Layout */
.main-content { flex: 1; padding: 60px; display: flex; flex-direction: column; align-items: center; }
.tree-container { width: 100%; max-width: 1000px; display: flex; flex-direction: column; gap: 40px; }
.nodes-row { display: flex; justify-content: center; gap: 15px; flex-wrap: wrap; }
.node { background: #fff; border: 1px solid #e2e2d9; padding: 10px 15px; text-decoration: none; color: #1a2a3a; min-width: 170px; text-align: center; border-radius: 4px; display: flex; flex-direction: column; }
.node-label { font-size: 0.6rem; color: #cfa84e; font-weight: bold; letter-spacing: 1px; margin-bottom: 4px; }

/* Focus sectie styling */
.focus-box { text-align: center; padding: 10px 0; }
.focus-portrait { width: 190px; border: 6px solid #fff; box-shadow: 0 10px 25px rgba(0,0,0,0.08); margin-bottom: 15px; }
.focus-name { font-size: 2.3rem; color: #1a2a3a; margin-bottom: 5px; }
.focus-dates { color: #cfa84e; font-weight: bold; font-size: 1.1rem; margin-bottom: 5px; }
.focus-location { font-size: 0.9rem; color: #666; font-style: italic; }

/* Biografie & Scans */
.content-body { width: 100%; max-width: 900px; margin-top: 50px; border-top: 1px solid #eee; padding-top: 30px; }
.section-title { font-size: 1.1rem; color: #1a2a3a; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 25px; text-align: center; font-weight: bold; }
.biography-text { column-count: 2; column-gap: 40px; text-align: justify; line-height: 1.8; color: #444; white-space: pre-line; font-size: 0.95rem; }
.scans-grid { display: flex; gap: 15px; flex-wrap: wrap; justify-content: center; }
.scan-thumb img { width: 140px; height: 190px; object-fit: cover; border: 1px solid #ddd; }

.group-title { font-size: 0.7rem; color: #cfa84e; font-weight: bold; letter-spacing: 2px; margin-bottom: 15px; text-align: center; }
.children-grid { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; }
.back-link { font-size: 0.85rem; color: #8a6d3b; text-decoration: underline; margin-top: 20px; display: inline-block; }

@media (max-width: 1100px) { .biography-text { column-count: 1; } }
</style>