<template>
  <div class="page-wrapper">
    <div class="content-container">
      <div v-if="pending" class="loading-state">Laden...</div>

      <template v-else-if="persoon">
        <section class="hero-section">
          <h1 class="hero-title">{{ dynamicTitle || persoon.naam }}</h1>
          <p v-if="dynamicSubtitle || persoon.subtitle" class="hero-subtitle">
            {{ dynamicSubtitle || persoon.subtitle }}
          </p>
          <div class="hero-divider"></div>
        </section>

        <section class="content-row">
          <div class="text-block text-box">
            <div @click="handleImageClick" class="markdown-content" v-html="formatNotities(persoon.notities)">
            </div>
          </div>
        </section>
      </template>
      
      <template v-else>
        <section class="hero-section">
          <h1 class="hero-title">Informatie niet beschikbaar</h1>
          <NuxtLink to="/" class="back-link">Terug naar home</NuxtLink>
        </section>
      </template>
    </div>

    <Transition name="fade">
      <div v-if="selectedImage" class="zoom-overlay" @click.self="selectedImage = null">
        <span class="close-btn" @click="selectedImage = null">&times;</span>
        <div class="zoom-content">
          <img :src="selectedImage" class="zoomed-image-large" alt="Vergroting">
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const selectedImage = ref(null);
const dynamicTitle = ref('');
const dynamicSubtitle = ref('');

const { data: persoon, pending, refresh } = await useFetch(`https://familiehuizinga.nl/api/data.php?slug=${route.params.slug}`);

watch(() => route.params.slug, () => refresh());

const handleImageClick = (event) => {
  if (event.target.tagName === 'IMG') { 
    selectedImage.value = event.target.src; 
  }
};

const formatNotities = (txt) => {
  if (!txt) return '';
  
  // 1. FRONTMATTER UITLEZEN (Title & Subtitle)
  const frontmatterMatch = txt.match(/^---[\s\S]*?---/);
  if (frontmatterMatch) {
    const frontmatter = frontmatterMatch[0];
    const titleMatch = frontmatter.match(/title:\s*"(.*?)"/);
    const subtitleMatch = frontmatter.match(/subtitle:\s*"(.*?)"/);
    if (titleMatch) dynamicTitle.value = titleMatch[1];
    if (subtitleMatch) dynamicSubtitle.value = subtitleMatch[1];
  }

  let content = txt.replace(/^---[\s\S]*?---/, '');

  // 2. TABELLEN VERWERKEN (Met fix voor headers)
  const lines = content.split('\n');
  let processedLines = [];
  let tableBuffer = [];

  lines.forEach((line) => {
    if (line.trim().startsWith('|')) {
      tableBuffer.push(line.trim());
    } else {
      if (tableBuffer.length > 0) {
        processedLines.push(renderTable(tableBuffer));
        tableBuffer = [];
      }
      processedLines.push(line);
    }
  });
  if (tableBuffer.length > 0) processedLines.push(renderTable(tableBuffer));
  
  content = processedLines.join('\n');

  // 3. DIV CODES & MARKDOWN NAAR HTML
  content = content.replace(/[:]{2,3}div\{(\.[^\}]+)\}/g, (match, className) => {
    return `<div class="${className.replace('.', '').trim()}">`;
  });
  content = content.replace(/[:]{2,3}/g, '</div>');

  let html = content
    .replace(/^### (.*$)/gim, '<h3>$1</h3>')
    .replace(/^## (.*$)/gim, '<h2>$1</h2>')
    .replace(/\*\*(.*)\*\*/gim, '<strong>$1</strong>')
    .replace(/!\[([^\]]*)\]\(([^\)]*)\)/gim, '<img src="$2" alt="$1">');

  // 4. PARAGRAAF LOGICA
  return html.trim().split(/\n\s*\n/).map(para => {
    if (para.startsWith('<h') || para.startsWith('<div') || para.includes('<table')) return para;
    return `<p>${para.replace(/\n/g, '<br>')}</p>`;
  }).join('');
};

// Tabel Render Functie (Lost de blauwe-rij-fout op)
const renderTable = (rows) => {
  let html = '<div class="table-container"><table>';
  let hasHeader = false;

  rows.forEach((row) => {
    if (row.includes('---')) { hasHeader = true; return; }
    const cells = row.split('|').filter((c, i, a) => i > 0 && i < a.length - 1);
    const tag = (!hasHeader) ? 'th' : 'td';
    
    html += `<tr>${cells.map(c => {
      const cleanContent = c.trim().replace(/\*\*(.*)\*\*/g, '$1');
      return `<${tag}>${cleanContent}</${tag}>`;
    }).join('')}</tr>`;
  });
  return html + '</table></div>';
};
</script>

<style scoped>
/* BASIS */
.page-wrapper { background-color: #fdfcf8; min-height: 100vh; padding: 60px 20px; }
.content-container { max-width: 1000px; margin: 0 auto; }
.text-box { background: white; padding: 60px; border-radius: 8px; border: 1px solid #f0ede4; }

/* TITELS (JOST) */
.hero-section { text-align: center; margin-bottom: 60px; }
.hero-title { font-family: 'Jost', sans-serif; font-size: 3.5rem; color: #1a2a3a; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
.hero-subtitle { font-family: 'Onest', sans-serif; font-size: 1.4rem; color: #555; margin-top: 15px; font-weight: 300; }
.hero-divider { width: 80px; height: 3px; background: #d4af37; margin: 35px auto; }

/* BODY (ONEST) */
.markdown-content :deep(p) { font-family: 'Onest', sans-serif; line-height: 1.9; color: #333; font-size: 1.2rem; margin-bottom: 1.8rem; }
.markdown-content :deep(h2), .markdown-content :deep(h3) { font-family: 'Jost', sans-serif; color: #1a2a3a; margin-top: 2.5rem; margin-bottom: 1rem; clear: both; }

/* TABEL STYLING */
.markdown-content :deep(.table-container) { margin: 40px 0; border-radius: 8px; overflow: hidden; border: 1px solid #e0ddd5; clear: both; }
.markdown-content :deep(table) { width: 100%; border-collapse: collapse; background: white; }
.markdown-content :deep(th) { background: #1a2a3a; color: white; text-align: left; padding: 18px; font-family: 'Jost', sans-serif; font-size: 1.1rem; text-transform: uppercase; }
.markdown-content :deep(td) { padding: 15px 18px; border-bottom: 1px solid #eee; font-family: 'Onest', sans-serif; font-size: 1.1rem; color: #444; vertical-align: top; }
.markdown-content :deep(tr:nth-child(even)) { background: #fcfbf8; }

/* FOTO'S EN OMVLOEIING */
.markdown-content :deep(.afbeelding-links-omvloeid) { float: left; margin: 10px 40px 30px 0; max-width: 45%; clear: left; }
.markdown-content :deep(.afbeelding-rechts-omvloeid) { float: right; margin: 10px 0 30px 40px; max-width: 45%; clear: right; }
.markdown-content :deep(img) { width: 100%; height: auto; border-radius: 4px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); display: block; }

/* MOBIEL */
@media (max-width: 768px) {
  .markdown-content :deep(.afbeelding-links-omvloeid), .markdown-content :deep(.afbeelding-rechts-omvloeid) { float: none; max-width: 100%; margin: 20px 0; }
  .hero-title { font-size: 2.5rem; }
  .text-box { padding: 30px; }
}

.markdown-content::after { content: ""; display: table; clear: both; }

/* ZOOM OVERLAY */
.zoom-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.98); z-index: 1000; overflow: auto; padding: 40px; }
.zoom-content { display: flex; justify-content: center; min-height: 100%; }
.zoomed-image-large { max-width: none; height: auto; border: 8px solid white; align-self: flex-start; }
.close-btn { position: fixed; top: 20px; right: 40px; color: white; font-size: 60px; cursor: pointer; z-index: 1001; }
</style>