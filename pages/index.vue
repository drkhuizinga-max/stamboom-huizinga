<template>
  <div class="home-container">
    <section class="welcome-section">
      <h2 class="welcome-title">DUIK MEE IN DE FASCINERENDE WERELD VAN FAMILIEGESCHIEDENIS</h2>
      <div class="ornament">❦</div>
      <p class="intro-tagline">Het voor- en nageslacht van Hendrik Olferts Huizinga en Barbara Wrister Brugman</p>
      <p class="intro-tagline">Ontdek de verhalen die generaties overspannen.</p>
    </section>

    <section class="content-block">
      <div class="text-side">
        <h3>Genealogie</h3>
        <p>Ontdek de wortels van de Familie Huizinga. Verken de stambomen en data die generaties overspannen en ons verbinden met het verleden. Deze genealogieën vertellen meer dan namen en data – ze onthullen een rijk erfgoed van tradities en levens die ons hebben gevormd.</p>
        <NuxtLink to="/personen/48-hendrik-olferts-huizinga" class="btn-dark">Bekijk de Stamboom</NuxtLink>
      </div>
      <div class="image-side">
        <div class="photo-frame tilt-right">
          <img :src="imgPersonen + 'olfert.webp'" alt="Portret van Olfert Hendrik Huizinga en Anje Klaasens Lap" loading="lazy" />
          <p class="caption">Olfert Hendrik Huizinga en Anje Klaasens Lap</p>
        </div>
      </div>
    </section>

    <section class="content-block reverse">
      <div class="text-side">
        <h3>Geschiedenis</h3>
        <p>Maak kennis met de fascinerende geschiedenis van het Hogeland, van de vroege bewoning tot de ontwikkeling van de geboortestreek. Belangrijke gebeurtenissen, iconen en historische plekken worden belicht, waardoor u een goed overzicht krijgt van de culturele waarde van deze bijzondere streek.</p>
        <NuxtLink to="/geschiedenis/houwerzijl" class="btn-dark">Ontdek Houwerzijl</NuxtLink>
      </div>
      <div class="image-side">
        <div class="photo-frame tilt-left">
          <img :src="imgFoto + 'grenspaal.webp'" alt="Historische grenspaal Ulrum-Leens bij Houwerzijl" loading="lazy" />
          <p class="caption">Grenspaal Ulrum-Leens</p>
        </div>
      </div>
    </section>

    <section class="content-block">
      <div class="text-side">
        <h3>Beeldmateriaal</h3>
        <p>Herbeleef het verleden met foto's van dierbare familieleden, video's van de familie Huizinga en unieke beelden van het dorp Houwerzijl rond de vorige eeuwwisseling. Deze verzameling toont warme herinneringen en een prachtig tijdsbeeld.</p>
        <NuxtLink to="/beeldmateriaal/foto" class="btn-dark">Naar het Fotoalbum</NuxtLink>
      </div>
      <div class="image-side">
        <div class="photo-frame tilt-right">
          <img :src="imgPersonen + 'ulfert.webp'" alt="Historische foto van Ulfert Hendrik Huizinga" loading="lazy" />
          <p class="caption">Ulfert Hendrik Huizinga</p>
        </div>
      </div>
    </section>

    <section class="content-block reverse">
      <div class="text-side">
        <h3>Stamboek Melkema</h3>
        <p>Ontdek het historisch geslachtsregister van de nakomelingen van Derk Pieters en Katrina Tomas uit Huizinge, landgoed Melkema. Een rijke verzameling gegevens van 1555 tot 1883.</p>
        <NuxtLink to="/stamboek" class="btn-dark">Bekijk het Register</NuxtLink>
      </div>
      <div class="image-side">
        <div class="photo-frame tilt-left">
          <img :src="imgFoto + 'stamboek.webp'" alt="Het originele Stamboek Melkema register" loading="lazy" />
          <p class="caption">Stamboek Melkema</p>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
// Centraal beheer van afbeeldingspaden - wijst nu naar /public/images/
const baseDir = '/images/'
const imgFoto = baseDir + 'foto/'
const imgPersonen = baseDir + 'personen/'

// SEO Instellingen
useHead({
  title: 'Familie Huizinga | Stamboom & Historie uit Houwerzijl',
  meta: [
    { 
      name: 'description', 
      content: 'Ontdek de rijke geschiedenis van de familie Huizinga uit Houwerzijl. Bekijk de stamboom (1555-heden), historische foto\'s en het Stamboek Melkema.' 
    },
    { property: 'og:title', content: 'Familie Huizinga Stamboom' },
    { property: 'og:description', content: 'Reis door de eeuwen heen (1555 - heden).' },
    // Volledige URL voor sociale media (vervang dirkhuizinga.nl door je echte domein indien nodig)
    { property: 'og:image', content: 'https://www.familiehuizinga.nl/images/foto/houwerzijl.webp' },
    { property: 'og:type', content: 'website' }
  ]
})
</script>

<style scoped>
.home-container { 
  max-width: 1100px; 
  margin: 0 auto; 
  padding: 40px 20px; 
}

.welcome-section { 
  text-align: center; 
  margin-bottom: 80px; 
}

.welcome-title { 
  font-family: 'Playfair Display', serif; 
  font-style: italic; 
  font-size: 1.8rem; 
  color: #1a2a3a; 
  line-height: 1.4; 
  text-transform: uppercase; 
  letter-spacing: 1px; 
}

.ornament { 
  color: #d4af37; 
  font-size: 3rem; 
  margin: 10px 0; 
  line-height: 1; 
}

.intro-tagline { 
  font-family: 'Rubik', sans-serif; 
  font-size: 1.1rem; 
  color: #7f8c8d; 
  font-weight: 300; 
  margin-bottom: 5px;
}

.content-block { 
  display: flex; 
  align-items: center; 
  gap: 80px; 
  margin-bottom: 120px; 
}

.content-block.reverse { 
  flex-direction: row-reverse; 
}

.text-side { 
  flex: 1.2; 
}

.text-side h3 { 
  font-family: 'Playfair Display', serif; 
  font-size: 2.5rem; 
  color: #1a2a3a; 
  margin-bottom: 20px; 
  border-bottom: 2px solid #f4f1ea; 
  display: inline-block; 
  padding-bottom: 10px; 
}

.text-side p { 
  font-family: 'Rubik', sans-serif; 
  line-height: 1.8; 
  color: #4a5568; 
  font-size: 1.05rem; 
  margin-bottom: 30px; 
}

.btn-dark { 
  display: inline-block; 
  padding: 14px 32px; 
  background: #1a2a3a; 
  color: white; 
  text-decoration: none; 
  font-weight: bold; 
  letter-spacing: 1px; 
  text-transform: uppercase; 
  font-size: 0.85rem; 
  transition: all 0.3s; 
  border-bottom: 3px solid #d4af37; 
}

.btn-dark:hover { 
  background: #d4af37; 
  border-bottom-color: #1a2a3a; 
  color: white; 
  transform: translateY(-2px); 
}

.image-side { 
  flex: 1; 
  display: flex; 
  justify-content: center; 
}

.photo-frame { 
  background: white; 
  padding: 12px 12px 45px 12px; 
  box-shadow: 0 10px 30px rgba(0,0,0,0.1); 
  border: 1px solid #e2e2d9; 
  width: 100%; 
  max-width: 360px; 
  transition: all 0.4s ease; 
}

.photo-frame img { 
  width: 100%; 
  height: auto; 
  display: block; 
  filter: sepia(10%); 
}

.caption { 
  font-family: 'Playfair Display', serif; 
  font-style: italic; 
  text-align: center; 
  margin-top: 20px; 
  color: #8e8e84; 
  font-size: 0.95rem; 
}

.tilt-right { transform: rotate(2deg); }
.tilt-left { transform: rotate(-2deg); }

.photo-frame:hover { 
  transform: rotate(0deg) scale(1.05); 
  box-shadow: 0 20px 45px rgba(0,0,0,0.15); 
}

@media (max-width: 850px) {
  .content-block, .content-block.reverse { 
    flex-direction: column; 
    text-align: center; 
    gap: 40px; 
    margin-bottom: 80px; 
  }
  .photo-frame { 
    transform: rotate(0); 
  }
  .text-side h3 { 
    font-size: 2rem; 
  }
}
</style>