<script setup>
const route = useRoute()
const persoon = ref(null)
const vader = ref(null)
const moeder = ref(null)
const grootouders = ref({ vv: null, vm: null, mv: null, mm: null })
const partner = ref(null)
const kinderen = ref([])
const loading = ref(true)
const error = ref(null)

const activeScan = ref(null)
const isZoomed = ref(false)
const searchQuery = ref('')
const searchResults = ref([])
const API = 'https://familiehuizinga.nl/api'

// --- HELPERS ---
const getImageUrl = (path) => {
  if (!path || path === '0' || path === '' || typeof path !== 'string' || path.includes('null')) return null;
  
  // Verwijder eventuele schuine strepen aan het begin
  const cleanPath = path.trim().replace(/^\//, '');
  
  // Als het al een volledige URL is, gebruik die. 
  // Anders: plak het pad naar de nieuwe upload-map er tussen.
  return path.startsWith('http') ? path : `https://familiehuizinga.nl/api/uploads/${cleanPath}`;
}

const createSlug = (name) => {
  if (!name) return 'persoon';
  return name.toLowerCase().trim().normalize("NFD").replace(/[\u0300-\u036f]/g, "").replace(/[^\w\s-]/g, '').replace(/[\s_-]+/g, '-').replace(/^-+|-+$/g, '');
}

const getFullSlug = (p) => {
  if (!p || !p.id || !p.naam) return '#';
  return `${p.id}-${createSlug(p.naam)}`;
}

const getYearsString = (p) => {
  if (!p) return '';
  const birth = p.geboortedatum || p.geb_datum || p.geboortejaar || '';
  const death = p.overlijdensdatum || p.ovl_datum || '';
  const bMatch = birth.toString().match(/\d{4}/);
  const dMatch = death.toString().match(/\d{4}/);
  const bYear = bMatch ? bMatch[0] : '';
  const dYear = dMatch ? dMatch[0] : '';
  if (bYear && dYear) return `(${bYear}-${dYear})`;
  if (bYear) return `(*${bYear})`;
  return '';
}

const formatNotities = (txt) => {
  if (!txt) return '';
  if (txt.includes('<p>') || txt.includes('<br>')) return txt;
  return txt.trim().split(/\n\s*\n/).map(para => `<p>${para.replace(/\n/g, `<br>`)}</p>`).join('');
}

const fetchSafe = async (url) => {
  try {
    const r = await fetch(url, { cache: 'no-cache' });
    if (!r.ok) return null;
    const text = await r.text();
    // Als de response geen JSON is, behandelen we het als platte tekst (Markdown)
    if (!text.trim().startsWith('{') && !text.trim().startsWith('[')) {
      return { isContent: true, content: text };
    }
    return JSON.parse(text);
  } catch (err) { return null; }
}

const handleSearch = async () => {
  if (searchQuery.value.length < 2) { searchResults.value = []; return; }
  const res = await fetchSafe(`${API}/data.php?search=${encodeURIComponent(searchQuery.value)}`);
  if (res && Array.isArray(res)) searchResults.value = res;
}

const laadData = async () => {
  const s = route.params.slug;
  if (!s) return;
  const lastPart = Array.isArray(s) ? s[s.length - 1] : s;

  loading.value = true;
  error.value = null;
  persoon.value = null; 
  vader.value = moeder.value = partner.value = null;
  kinderen.value = [];
  grootouders.value = { vv: null, vm: null, mv: null, mm: null };

  // Check of het een thema-pagina is (geen ID aan het begin)
  if (!/^\d/.test(lastPart)) {
    const res = await fetchSafe(`${API}/data.php?slug=${lastPart}`);
    if (res) {
      persoon.value = { 
        // Geef voorkeur aan de naam/titel uit de API (Markdown frontmatter)
        naam: res.naam || res.title || lastPart.replace('.md', '').toUpperCase().replace(/-/g, ' '), 
        subtitle: res.subtitle || '',
        notities: res.isContent ? res.content : res.notities,
        isThema: true 
      };
    }
    loading.value = false;
    return;
  }

  // Database persoon inladen
  const id = parseInt(lastPart.split('-')[0]);
  try {
    const data = await fetchSafe(`${API}/data.php?slug=${id}`);
    if (!data) { error.value = "Persoon niet gevonden."; loading.value = false; return; }
    
    // Zorg dat plaatsen expliciet in het object zitten
    persoon.value = data;

    const familiePromises = [];
    
    // Partner ophalen
    if (data.partner_id && data.partner_id !== "0") {
      familiePromises.push(fetchSafe(`${API}/data.php?slug=${data.partner_id}`).then(res => partner.value = res));
    }
    
    // Kinderen ophalen
    familiePromises.push(fetchSafe(`${API}/data.php?children_of=${id}`).then(res => { if (res) kinderen.value = res; }));
    
    // Voorouders ophalen
    if (data.vader_id && data.vader_id !== "0") {
      familiePromises.push(fetchSafe(`${API}/data.php?slug=${data.vader_id}`).then(async v => {
        vader.value = v;
        if (v?.vader_id > 0) grootouders.value.vv = await fetchSafe(`${API}/data.php?slug=${v.vader_id}`);
        if (v?.moeder_id > 0) grootouders.value.vm = await fetchSafe(`${API}/data.php?slug=${v.moeder_id}`);
      }));
    }
    if (data.moeder_id && data.moeder_id !== "0") {
      familiePromises.push(fetchSafe(`${API}/data.php?slug=${data.moeder_id}`).then(async m => {
        moeder.value = m;
        if (m?.vader_id > 0) grootouders.value.mv = await fetchSafe(`${API}/data.php?slug=${m.vader_id}`);
        if (m?.moeder_id > 0) grootouders.value.mm = await fetchSafe(`${API}/data.php?slug=${m.moeder_id}`);
      }));
    }
    await Promise.all(familiePromises);
  } catch (e) { console.error(e); }
  loading.value = false;
}

onMounted(laadData);
watch(() => route.params.slug, laadData);
</script>

<template>
  <div class="layout-container">
    <aside class="sidebar">
      <div class="sticky-nav">
        <h3 class="side-title">Zoeken</h3>
        <div class="search-box" style="position: relative;">
          <input v-model="searchQuery" @input="handleSearch" type="text" placeholder="Naam..." class="search-input" />
          <div v-if="searchResults.length" class="search-results">
            <NuxtLink v-for="s in searchResults" :key="s.id" :to="`/personen/${getFullSlug(s)}`" class="search-item" @click="searchQuery = ''">
              <span class="search-name">{{ s.naam }}</span>
              <span class="search-years" v-if="getYearsString(s)">{{ getYearsString(s) }}</span>
            </NuxtLink>
          </div>
        </div>
      </div>
    </aside>

    <main class="main-content">
      <div v-if="loading" class="loading-state">Laden...</div>
      <div v-else-if="error" class="error-state">{{ error }}</div>
      <div v-else-if="persoon" class="profile-view">
        
        <div v-if="!persoon.isThema" class="tree-section">
          <div class="tree-row">
            <NuxtLink v-for="g in [grootouders.vv, grootouders.vm, grootouders.mv, grootouders.mm]" :key="g?.id" :to="`/personen/${getFullSlug(g)}`" class="node-link small" :class="{ empty: !g }">
              {{ g?.naam || '...' }} <span v-if="getYearsString(g)" class="node-year">{{ getYearsString(g) }}</span>
            </NuxtLink>
          </div>
          <div class="tree-row">
            <NuxtLink :to="`/personen/${getFullSlug(vader)}`" class="node-link gold" :class="{ empty: !vader }">
              <span class="label">Vader</span> {{ vader?.naam || 'Onbekend' }} <span v-if="getYearsString(vader)" class="node-year">{{ getYearsString(vader) }}</span>
            </NuxtLink>
            <NuxtLink :to="`/personen/${getFullSlug(moeder)}`" class="node-link gold" :class="{ empty: !moeder }">
              <span class="label">Moeder</span> {{ moeder?.naam || 'Onbekend' }} <span v-if="getYearsString(moeder)" class="node-year">{{ getYearsString(moeder) }}</span>
            </NuxtLink>
          </div>
        </div>

        <div v-if="getImageUrl(persoon.foto_url)" class="portrait-frame">
          <img :src="getImageUrl(persoon.foto_url)" class="portrait-img" alt="Portret" />
        </div>

        <h1 class="p-name">{{ persoon.naam }}</h1>
        <p v-if="persoon.subtitle" class="p-subtitle">{{ persoon.subtitle }}</p>

        <div v-if="!persoon.isThema && (persoon.geboortedatum || persoon.overlijdensdatum)" class="vital-details">
          <div v-if="persoon.geboortedatum" class="vital-item">
            <span class="label">GEBOORTE</span>
            <span class="date">{{ persoon.geboortedatum }}</span>
            <span class="place">{{ persoon.geboorteplaats }}</span>
          </div>
          <div v-if="persoon.overlijdensdatum" class="vital-item">
            <span class="label">OVERLIJDEN</span>
            <span class="date">{{ persoon.overlijdensdatum }}</span>
            <span class="place">{{ persoon.overlijdensplaats }}</span>
          </div>
        </div>

        <div v-if="!persoon.isThema" class="family-section">
          <div v-if="partner" class="partner-container">
            <span class="section-label">ECHTGENOOT / PARTNER</span>
            <div class="partner-wrapper">
              <NuxtLink :to="`/personen/${getFullSlug(partner)}`" class="fam-card partner-card">
                <span class="fam-name">{{ partner.naam }}</span>
                <span class="card-year">{{ getYearsString(partner) }}</span>
              </NuxtLink>
            </div>
          </div>

          <div v-if="kinderen.length" class="kids-section">
            <span class="section-label">KINDEREN</span>
            <div class="kids-grid">
              <NuxtLink v-for="k in kinderen" :key="k.id" :to="`/personen/${getFullSlug(k)}`" class="fam-card">
                <span class="fam-name">{{ k.naam }}</span>
                <span class="card-year">{{ getYearsString(k) }}</span>
              </NuxtLink>
            </div>
          </div>
        </div>

        <hr v-if="!persoon.isThema" class="divider" />

        <div class="text-block">
          <h3 v-if="!persoon.isThema" class="section-title">Informatie</h3>
          <div class="bio-content" v-html="formatNotities(persoon.notities)"></div>
        </div>

        <div v-if="persoon.scans" class="text-block">
          <h3 class="section-title">Documenten</h3>
          <div class="scans-grid">
            <div v-for="(scan, index) in persoon.scans.split(',')" :key="index" @click="activeScan = getImageUrl(scan.trim())" class="scan-wrapper">
              <img :src="getImageUrl(scan.trim())" />
            </div>
          </div>
        </div>
      </div>
    </main>

    <div v-if="activeScan" class="lightbox-overlay" @click="activeScan = null">
      <button class="close-x" @click="activeScan = null">&times;</button>
      <div class="lightbox-canvas">
        <img 
          :src="activeScan" 
          :class="isZoomed ? 'img-full' : 'img-fit'" 
          @click.stop="isZoomed = !isZoomed" 
        />
      </div>
      <div class="lightbox-footer">
        <button @click.stop="isZoomed = !isZoomed">
          {{ isZoomed ? 'Uitzoomen' : 'Inzoomen' }}
        </button>
        <button @click="activeScan = null">Sluiten</button>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* --- Zoekresultaten Fix --- */
.search-results {
  background: #fff;
  border: 1px solid #dcd7ca;
  position: absolute;
  width: 100%; 
  max-height: 400px;
  overflow-y: auto;
  z-index: 1000;
  box-shadow: 0 10px 25px rgba(0,0,0,0.1);
  margin-top: 4px;
  border-radius: 4px;
  display: flex;
  flex-direction: column;
  text-align: left;
}

.search-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 15px;
  text-decoration: none;
  color: #1a2a3a;
  border-bottom: 1px solid #f0ede6;
  font-size: 0.9rem;
}

.search-item:hover { background: #fdfcf9; color: #cfa84e; }
.search-name { font-weight: 500; }
.search-years { color: #8b7355; font-size: 0.8rem; }

/* --- Algemene Layout --- */
.layout-container { 
  display: flex; background: #fdfcf9; min-height: 100vh; max-width: 100%; overflow-x: hidden; 
  font-family: 'Onest', sans-serif; color: #1a2a3a; 
}

.sidebar { width: 280px; padding: 40px 20px; background: #fff; border-right: 1px solid #e0ddd5; flex-shrink: 0; }
.search-input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }

.main-content { flex: 1; padding: 40px; min-width: 0; width: 100%; box-sizing: border-box; }
.profile-view { max-width: 950px; margin: 0 auto; text-align: center; }

/* --- Stamboom Boomstructuur --- */
.tree-row { display: flex; justify-content: center; gap: 8px; margin-bottom: 12px; flex-wrap: wrap; width: 100%; }
.node-link { 
  background: #fff; border: 1px solid #e0ddd5; padding: 10px; text-decoration: none; color: inherit; 
  min-width: 140px; font-size: 0.8rem; box-sizing: border-box; border-radius: 4px;
  transition: all 0.2s ease;
}
.node-link:hover { border-color: #cfa84e; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
.node-link.gold { border-color: #cfa84e; border-width: 2px; font-weight: bold; background: #fffcf5; }
.node-year { color: #8b7355; font-size: 0.75rem; display: block; margin-top: 2px; }

/* --- Portret & Namen --- */
.portrait-frame { width: 220px; height: 280px; margin: 20px auto; border: 1px solid #eee; background: #fff; display: flex; align-items: center; justify-content: center; }
.portrait-img { max-width: 100%; max-height: 100%; object-fit: contain; }
.p-name { font-size: 2.2rem; font-family: 'Playfair Display', serif; margin: 15px 0; }
.p-subtitle { color: #8b7355; font-style: italic; margin-top: -10px; margin-bottom: 30px; font-size: 1.1rem; }

/* --- Vital Details (Geboorte/Overlijden) --- */
.vital-details { 
  display: flex; 
  justify-content: center; 
  gap: 60px; 
  margin: 25px 0; 
  border-top: 1px solid #eee; 
  border-bottom: 1px solid #eee; 
  padding: 20px 0; 
}

.vital-item { 
  display: flex; 
  flex-direction: column; 
  align-items: center; 
  min-width: 180px; 
}

.vital-item .label { 
  font-size: 0.7rem; 
  color: #888; 
  margin-bottom: 4px; 
  text-transform: uppercase; 
  letter-spacing: 1px; 
}

.vital-item .date { 
  font-weight: 600; 
  font-size: 1.05rem; 
  color: #1a2a3a; 
}

/* Toegevoegde styling voor de plaatsnaam */
.vital-item .place { 
  font-size: 0.95rem; 
  color: #1a2a3a; 
  margin-top: 2px; 
  font-weight: 400; 
}

/* --- Familie Sectie (Partner & Kinderen) --- */
.family-section { margin: 50px auto; max-width: 850px; display: flex; flex-direction: column; gap: 40px; }
.section-label { font-size: 0.75rem; font-weight: 700; letter-spacing: 1.5px; color: #8b7355; margin-bottom: 20px; text-transform: uppercase; display: block; }

.fam-card { 
  background: #fff; border: 1px solid #e0ddd5; padding: 16px; text-decoration: none; color: inherit; 
  display: flex; flex-direction: column; align-items: center; border-radius: 8px;
  transition: all 0.3s ease; box-shadow: 0 2px 5px rgba(0,0,0,0.02);
}
.fam-card:hover { border-color: #cfa84e; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(207, 168, 78, 0.12); }

.partner-wrapper { display: flex; justify-content: center; }
.partner-card { border: 2px solid #cfa84e; background: #fffcf5; width: 280px; padding: 20px; }
.partner-card .fam-name { font-size: 1.1rem; color: #1a2a3a; }

.kids-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px; }
.fam-name { font-weight: 600; margin-bottom: 4px; }
.card-year { color: #8b7355; font-size: 0.85rem; font-style: italic; }

/* --- Tekst & Documenten --- */
.bio-content { line-height: 1.8; background: #fff; padding: 30px; border: 1px solid #f0ede6; text-align: left; border-radius: 4px; }
/* VERBETERDE SCANS LAYOUT */
.scans-grid { 
  display: grid; 
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); 
  gap: 25px; 
  margin-top: 30px;
  justify-items: center;
}

.scan-wrapper { 
  width: 100%;
  max-width: 240px;
  aspect-ratio: 3/4; 
  background: white;
  padding: 10px; /* De witte rand */
  border: 1px solid #e0ddd5; 
  border-radius: 2px; 
  cursor: pointer; 
  position: relative; 
  box-shadow: 0 4px 15px rgba(0,0,0,0.08); /* Zachte schaduw */
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.scan-wrapper:hover {
  transform: translateY(-5px) rotate(1deg); /* Speels effect bij hover */
  box-shadow: 0 12px 25px rgba(0,0,0,0.15);
  z-index: 10;
}

.scan-wrapper img { 
  width: 100%; 
  height: 100%; 
  object-fit: cover; 
  display: block;
}

.scan-overlay { 
  position: absolute; 
  inset: 10px; /* Blijft binnen de witte rand */
  background: rgba(26,42,58,0.4); 
  display: flex; 
  align-items: center; 
  justify-content: center; 
  opacity: 0; 
  transition: 0.3s; 
}

.scan-wrapper:hover .scan-overlay { 
  opacity: 1; 
}

.scan-overlay span { 
  color: white; 
  border: 1px solid white; 
  padding: 8px 16px; 
  font-size: 0.8rem; 
  border-radius: 4px; 
  background: rgba(0,0,0,0.2);
  backdrop-filter: blur(2px);
}
/* --- Lightbox --- */
.lightbox-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.95); z-index: 1000; display: flex; flex-direction: column; }
.close-x { 
  position: absolute; top: 25px; right: 35px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); 
  color: white; font-size: 35px; width: 50px; height: 50px; border-radius: 50%; cursor: pointer; z-index: 1100;
  display: flex; align-items: center; justify-content: center; transition: 0.2s;
}
.close-x:hover { background: rgba(255,255,255,0.2); transform: rotate(90deg); }

.lightbox-canvas { flex: 1; overflow: auto; padding: 40px; text-align: center; }
.img-fit { max-width: 98%; max-height: 85vh; object-fit: contain; cursor: zoom-in; display: inline-block; border-radius: 2px; }
.img-full { width: 150%; max-width: none; max-height: none; cursor: zoom-out; display: inline-block; }
.lightbox-footer { padding: 20px; display: flex; justify-content: center; gap: 15px; background: rgba(0,0,0,0.8); }
.lightbox-footer button { padding: 10px 20px; border-radius: 4px; border: none; cursor: pointer; background: #fff; font-weight: 600; }

/* --- Mobiel --- */
@media (max-width: 768px) {
  .layout-container { flex-direction: column; }
  .sidebar { width: 100%; order: -1; padding: 20px; border-right: none; border-bottom: 1px solid #eee; }
  .main-content { padding: 20px 15px; }
  .vital-details { gap: 20px; flex-direction: column; align-items: center; }
  .vital-item { min-width: auto; }
}
/* Zorg dat de datum en plaats onder elkaar komen te staan */
.vital-item .date {
  display: block; /* Forceert de datum op een eigen regel */
  font-weight: 600;
  font-size: 1.05rem;
  color: #1a2a3a;
}

.vital-item .place {
  display: block; /* Forceert de plaats op een eigen regel onder de datum */
  font-size: 0.95rem;
  color: #1a2a3a;
  margin-top: 2px; /* Geeft een klein beetje witruimte tussen datum en plaats */
  font-weight: 400;
}

/* Zorg dat de hele kolom netjes gecentreerd blijft */
.vital-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  min-width: 180px;
}
</style>