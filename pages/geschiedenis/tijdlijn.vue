<template>
  <div class="tijdlijn-page">
    <div class="container">
      
      <section class="hero-section">
        <h1 class="hero-title">Tijdlijn</h1>
        <p class="hero-subtitle">REIS DOOR DE EEUWEN HEEN (1555 - HEDEN)</p>
        <p class="hero-tagline">Belangrijke mijlpalen van de familie Huizinga stap voor stap.</p>
        <div class="hero-divider"></div>
      </section>

      <div class="timeline-container">
        <div class="timeline-line"></div>

        <div v-for="(item, index) in timelineItems" :key="index" 
             class="timeline-row" 
             :class="{ 'row-reverse': index % 2 !== 0 }">
          
          <div class="timeline-col photo-col">
            <div class="polaroid-frame" v-if="item.img">
              <img :src="getImgUrl(item.img)" :alt="item.titel" class="timeline-img" loading="lazy" />
              <div class="polaroid-caption">{{ item.jaar }}</div>
            </div>
          </div>

          <div class="timeline-dot-wrapper">
            <div class="timeline-dot">{{ item.jaar }}</div>
          </div>

          <div class="timeline-col text-col">
            <div class="timeline-content">
              <h3 class="item-title">{{ item.titel }}</h3>
              <div class="item-text" v-html="item.tekst"></div>
              <div class="item-footer" v-if="item.locatie">
                <span class="location-tag">📍 {{ item.locatie }}</span>
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
// De basis-URL wijst nu direct naar de lokale mappen
const baseDir = '/images/'

// Helper functie die kijkt of de foto in 'personen' of 'foto' moet staan
const getImgUrl = (imgName) => {
  const fileName = imgName.split('/').pop()
  // Namen die in de 'personen' map thuishoren:
  const isPersoon = (
    fileName.includes('olfert') || 
    fileName.includes('cornelis') || 
    fileName.includes('ulfert')
  )
  const folder = isPersoon ? 'personen/' : 'foto/'
  return baseDir + folder + fileName
}

const timelineItems = [
  {
    jaar: '1500',
    titel: 'De herkomst: Melkema',
    img: 'melkema.webp',
    tekst: 'Melkema, een imponerende boerderij met stenen fundamenten, ligt verscholen in het Groningse landschap nabij Huizinge. De architectuur, gekenmerkt door zware balkenlagen, ruime kamers en kloostermoppen — grote middeleeuwse bakstenen — duidt op een eigendom van invloedrijke Burgtheren. De eerste bekende bewoners waren Derk Pieters en Katrina Tomas in de zestiende eeuw. Melkema staat daarmee symbool voor een maatschappelijke verschuiving, van feodaal bezit naar hardwerkend boerenbedrijf.',
    locatie: 'Huizinge'
  },
  {
    jaar: '1600',
    titel: 'Vestiging van de naam Huizinga',
    img: 'huizinge.webp',
    tekst: 'Jacob Derks Huizinga (1659-1736) was een boerenzoon die op 21-jarige leeftijd naar Groningen trok. In 1679 begon hij als koopmansleerling bij Jan Arents in de Steentilstraat. Jacob hield een aantekenboek bij met persoonlijke en gemeentelijke gebeurtenissen. In brieven aan zijn vader verwees hij naar Derks Syrts tot Huysinga. Bij de geboorte van zijn zoon in 1682 gaf hij hem direct de naam Derk Jacobs Huysinga, waarmee hij de stamnaam Huizinga vestigde.',
    locatie: 'Groningen Stad'
  },
  {
    jaar: '1700',
    titel: 'Doopsgezindte',
    img: 'vermaning.webp',
    tekst: 'Rond het jaar 1705 dicteerde de zware klei van Huizinge het ritme van het leven op boerderij Melkema, het thuis van Derk Tammes Huizinga. Als lid van de diepgewortelde Doopsgezinde familie was Derk niet alleen boer; hij was ook een spil in zijn geloofsgemeenschap als \'vermaner\' (predikant). Hier, te midden van vee en graan, werden de strenge waarden van het geloof doorgegeven aan de volgende generatie.',
    locatie: 'Huizinge'
  },
  {
    jaar: '1750',
    titel: 'Saaxumhuizen',
    img: 'schilderij.webp',
    tekst: 'In de achttiende eeuw verplaatste een tak van de familie haar activiteiten naar het noorden. Vanaf 1740 bewoonden Jan Hendriks Huizinga en zijn vrouw Hiltje Reijntjes de boerderij aan de Saaxumhuizerweg 21. Het bedrijf besloeg zeventien bunder en werd bewerkt onder het zeldzame beklemrecht. Met deze vestiging begon een nieuw en belangrijk hoofdstuk voor de familie in het Hogeland.',
    locatie: 'Saaxumhuizen'
  },
  {
    jaar: '1800',
    titel: 'Saaxumhuizen',
    img: 'saaxumhuizen.webp',
    tekst: 'Jan Hendriks en Hiltje Reijntjes huren een boerderij van 17 bunder onder beklemrecht. In 1786 namen zoon Pieter Jans en Stijntje Freeks Scherphuis het over en breidden uit met een tweede boerderij. Na hun overlijden erfde Hendrik Pieters het bezit. Toen Hendrik in 1817 stierf, bleven vier minderjarige kinderen achter en werd het indrukwekkende bedrijf van inmiddels 72 jukken openbaar verkocht.',
    locatie: 'Saaxumhuizen'
  },
  {
    jaar: '1825',
    titel: 'Houwerzijl',
    img: 'olfert.webp',
    tekst: 'De boerderij in Saaxumhuizen is de geboorteplek van Olfert Hindriks Huizinga. Hij verloor zijn moeder op jonge leeftijd en zijn vader toen hij 13 jaar oud was. In 1818 verkochten zijn voogden de boerderij, die vier generaties Huizinga\'s huisvestte, voor het aanzienlijke bedrag van 15.500 gulden. Waar Olfert daarna opgroeide, blijft onbekend. In 1826 vertrok hij als vrijgezel naar Houwerzijl, waar hij trouwde met Anje Klaassen Lap. Acht jaar later kocht Olfert een huis aan de Zwarteweg, waar hij en Anje bijna 40 jaar woonden. Het huis, iets ruimer dan een gemiddeld arbeidershuisje, getuigde van een zekere welstand. Zijn bezit van een dorskleed voor koolzaad bevestigt dat hij financieel enigszins comfortabel leefde. Olferts verhaal biedt een blik op het boerenleven in Groningen en de uitdagingen waarmee families in die tijd te maken kregen.',
    locatie: 'Houwerzijl'
  },
  {
    jaar: '1900',
    titel: 'Tragisch ongeval',
    img: 'ulfert.webp',
    tekst: 'Ulfert Hendriks Huizinga overleed op 23 november 1928 door een tragisch jachtongeluk. Tijdens een mislukte jacht schoot de jonge Jan Klunder, zoon van de boer waarvoor Ulfert werkte, per ongeluk zijn jachtgeweer leeg. Het schot trof Ulfert in de borst, wat leidde tot zijn fatale overwondingen. Ulfert liet zijn vrouw, Grietje Klaas van Straten, en maar liefst 17 kinderen achter. De boer Klunder, voor wie Ulfert werkte, zorgde na het ongeluk voor Grietje en ondersteunde haar tot het einde van haar leven. Het drama liet diepe sporen achter in de gemeenschap en het gezin.',
    locatie: 'Houwerzijl'
  },
  {
    jaar: '1950',
    titel: 'Cornelis in Houwerzijl',
    img: 'cornelis.webp',
    tekst: 'Cornelis Huizinga, opgegroeid in een gezin van 16 kinderen, begon zijn werkzame leven jong. Vanaf de vierde klas werkte hij bij boeren, waar hij leerde dat de moderne boeren volgens hem de besten waren. Op 17-jarige leeftijd koos hij een ander pad en werd verpleger op Dennenoord in Zuidlaren. Twee jaar later vertrok hij naar Chicago, waar hij het vak van tuinieren leerde. Zijn verblijf in de Verenigde Staten werd echter afgebroken door het overlijden van zijn broer Wrister. Op verzoek van zijn moeder keerde Cornelis terug naar Nederland. De Eerste Wereldoorlog dwarsboomde die plannen, en hij moest in militaire dienst. Tijdens deze periode ontmoette hij Grietje Johanna Waterbolk, met wie hij later trouwde. Met de invoering van de Landarbeiderswet kocht hij een stuk grond dat onteigend was van boer Jacob Rietema en begon een groentekwekerij. Hoewel hij nooit rijk werd, genoot hij van zijn onafhankelijkheid en had hij een beter bestaan dan als boerenarbeider. Zijn doorzettingsvermogen maakten hem tot een eigen baas met een beter leven voor zijn gezin.',
    locatie: 'Houwerzijl'
  },
  {
    jaar: 'Nu',
    titel: 'Moderne tijd',
    img: 'theefabriek.webp',
    tekst: 'De Huizinga familie betreedt de 21e eeuw. De nadruk verschuift van traditionele landbouw naar diverse beroepen en het gebruik van nieuwe technologieën. De focus ligt op het behoud van de Groningse roots, terwijl de familie zich verspreidt over Nederland en daarbuiten.',
    locatie: 'Wereldwijd'
  }
]

useHead({
  title: 'Tijdlijn Familie Huizinga | 1555 - Heden',
  meta: [
    { name: 'description', content: 'De historische mijlpalen van de familie Huizinga, van Melkema in 1555 tot de moderne tijd.' }
  ]
})
</script>

<style scoped>
/* CSS volledig ongewijzigd gelaten */
.tijdlijn-page { background-color: #fdfdfb; min-height: 100vh; padding-bottom: 120px; overflow-x: hidden; }
.container { max-width: 1400px; margin: 0 auto; padding: 0 20px; }

.hero-section { text-align: center; padding: 60px 0 40px; }
.hero-title { font-family: 'Playfair Display', serif; font-size: 3rem; color: #1a2a3a; font-style: italic; }
.hero-subtitle { font-size: 0.8rem; letter-spacing: 2px; color: #d4af37; font-weight: bold; }
.hero-tagline { font-size: 1rem; color: #718096; margin-top: 10px; }
.hero-divider { width: 60px; height: 3px; background: #d4af37; margin: 25px auto; }

.timeline-container { 
  position: relative; 
  padding: 40px 0; 
  max-width: 1100px; 
  margin: 0 auto; 
}

.timeline-line { 
  position: absolute; 
  left: 30px; 
  width: 1px; 
  height: 100%; 
  background: #e2e2d9; 
}

.timeline-row { 
  display: flex; 
  flex-direction: column; 
  margin-bottom: 80px; 
  position: relative; 
  padding-left: 60px; 
}

.timeline-dot-wrapper { 
  position: absolute; 
  left: 0; 
  top: 0; 
  width: 60px; 
  display: flex; 
  justify-content: center; 
  z-index: 10; 
}

.timeline-dot { 
  background: #1a2a3a; 
  color: #d4af37; 
  width: 50px; 
  height: 50px; 
  border-radius: 50%; 
  display: flex; 
  align-items: center; 
  justify-content: center; 
  font-family: 'Playfair Display', serif; 
  font-weight: bold; 
  border: 1px solid #d4af37; 
  font-size: 0.8rem;
}

.timeline-col { width: 100%; }

.polaroid-frame { 
  background: white; 
  padding: 10px 10px 25px 10px; 
  box-shadow: 0 10px 30px rgba(0,0,0,0.08); 
  border: 1px solid #e2e2d9; 
  transform: rotate(-1.5deg); 
  margin-bottom: 20px;
}

.timeline-img { width: 100%; height: auto; display: block; filter: sepia(10%); }
.polaroid-caption { text-align: center; margin-top: 10px; font-family: 'Playfair Display', serif; color: #d4af37; font-weight: bold; }

.timeline-content { 
  background: white; 
  padding: 25px; 
  box-shadow: 0 15px 40px rgba(0,0,0,0.04); 
  border: 1px solid #e2e2d9; 
}

.item-title { font-family: 'Playfair Display', serif; color: #1a2a3a; font-size: 1.5rem; margin-bottom: 15px; font-style: italic; }
.item-text { font-size: 1rem; line-height: 1.7; color: #4a5568; text-align: left; }
.location-tag { display: inline-block; margin-top: 20px; font-size: 0.75rem; color: #d4af37; font-weight: 700; text-transform: uppercase; }

@media (min-width: 900px) {
  .hero-title { font-size: 4.5rem; }
  .timeline-line { left: 50%; transform: translateX(-50%); }
  
  .timeline-row { 
    flex-direction: row; 
    padding-left: 0; 
    align-items: center; 
    margin-bottom: 120px;
  }
  
  .timeline-row.row-reverse { flex-direction: row-reverse; }
  
  .timeline-col { width: 44%; }
  .timeline-dot-wrapper { position: relative; left: auto; width: 12%; }
  .timeline-dot { width: 70px; height: 70px; font-size: 1rem; }
  
  .timeline-content { padding: 30px; }
  .item-title { font-size: 1.6rem; }
  .item-text { text-align: left; }
  .polaroid-frame { padding: 12px 12px 30px 12px; max-width: 400px; margin: 0 auto; }
}
</style>