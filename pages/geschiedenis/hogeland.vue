<template>
  <div class="hogeland-page">
    <div class="content-container">
      
      <section class="hero-section">
        <h1 class="hero-title">Het Hogeland</h1>
        <p class="hero-subtitle">EEUWENOUDE WIERDEN EN DE STRIJD TEGEN DE ZEE</p>
        <p class="hero-tagline">Kwelder, dijken en welvaart in De Marne.</p>
        <div class="hero-divider"></div>
      </section>

      <section class="content-row">
        <h2 class="section-title">De Historie</h2>
        <div class="text-block">
          <p>De voormalige gemeente De Marne, gelegen in het Noordwesten van de provincie Groningen, is een gebied met een rijke geschiedenis die onlosmakelijk verbonden is met de strijd tegen en de ontwikkeling van het water.</p>
          <p>De gemeente ontstond in 1990 uit de fusie van de gemeenten Leens, Eenrum, Ulrum en Kloosterburen. Geografisch wordt het gebied begrensd door het Reitdiep in het zuiden, het Lauwersmeer in het westen, de gemeente Winsum in het oosten, en de Waddenzee in het noorden.</p>
        </div>
      </section>

      <section class="content-row split">
        <div class="text-side">
          <h2 class="section-title">Vroege Bewoning</h2>
          <p>De eerste bewoners, Friezen, vestigden zich omstreeks 500 voor Christus op de natuurlijke hoogten van het kwelderlandschap. Vanaf het begin van de jaartelling werden deze hoogten kunstmatig opgehoogd tot wierden. Tussen 400 en 800 na Christus trad een rustigere periode aan na zware overstromingen.</p>
          <p>De bewoning verschoof over drie ruggen: de vroegste rug (500 v.Chr.) van Warfhuizen naar Panser, de midden-rug (700 n.Chr.) van Wehe naar Vierhuizen, en de noordelijke rug (1100 n.Chr.) langs Hornhuizen en Kloosterburen.</p>
        </div>
        <div class="image-side">
          <div class="polaroid rotate-right">
            <img :src="imgFoto + 'kwelder.webp'" alt="Het vroege kwelderlandschap" loading="lazy" />
            <p class="polaroid-caption">Het vroege kwelderlandschap</p>
          </div>
        </div>
      </section>

      <section class="content-row">
        <h2 class="section-title">Vormen van Wierden</h2>
        <div class="text-block">
          <p>De grotere wierden kenden verschillende vormen: rond met een ossengang (Eenrum), hoekvormig op smalle ruggen (Leens) of langgerekt bij waterlopen. Aan de voet van de wierden lag het bouwland, de Valge. Vanaf 1000 na Christus startte de cyclus van inpolderingen met de eerste bescheiden dijken.</p>
        </div>
      </section>

      <section class="content-row split">
        <div class="text-side">
          <h2 class="section-title">De Eeuwige Strijd Tegen het Water</h2>
          <p>Natuurrampen zoals de Sint Luciavloed (1287) en de verwoestende Kerstvloed (1717) vormden het land. Bij de Kerstvloed bereikte het water zelfs de stad Groningen. Uit deze strijd ontstonden de zijlvesten. Belangrijke mijlpalen waren de Westpolder (1875), de afsluiting van het Reitdiep (1878) en de afsluiting van de Lauwerszee in 1969, wat nu het Nationaal Park Lauwersmeer is.</p>
        </div>
        <div class="image-side">
          <div class="polaroid rotate-left">
            <img :src="imgFoto + 'kerstvloed.jpeg'" alt="De verwoestende Kerstvloed" loading="lazy" />
            <p class="polaroid-caption">De verwoestende Kerstvloed van 1717</p>
          </div>
        </div>
      </section>

      <section class="content-row">
        <h2 class="section-title">Oorlog, Politiek en Reformatie</h2>
        <div class="text-block">
          <p>De Tachtigjarige Oorlog bracht plunderingen door Spaanse soldaten en watergeuzen. In 1582 werd Zoutkamp veroverd door Willem Lodewijk van Nassau. Na de Reductie van Groningen in 1594 werden de meeste kerken protestants, behalve Kloosterburen dat een katholieke enclave bleef. In 1834 vond in Ulrum de historische Afscheiding plaats onder leiding van ds. De Cock.</p>
        </div>
      </section>

      <section class="content-row split reverse">
        <div class="text-side">
          <h2 class="section-title">Sociale Structuur en Welvaart</h2>
          <p>Het Groningse beklemrecht legde de basis voor de enorme welvaart van de boerenstand, wat leidde tot de bouw van imposante boerenhofsteden in de 19e eeuw. In Leens herinnert de grens/strafpaal aan de overgang van strafrecht naar vreedzame gemeentegrenzen.</p>
        </div>
        <div class="image-side">
          <div class="polaroid rotate-right">
            <img :src="imgFoto + 'arbeiders.webp'" alt="Landarbeiders" loading="lazy" />
            <p class="polaroid-caption">Landarbeiders op de klei</p>
          </div>
        </div>
      </section>

      <section class="content-row split">
        <div class="text-side">
          <h2 class="section-title">Vervoer en Infrastructuur</h2>
          <p>Van de paardentram in 1897 tot de tramlijn Winsum-Zoutkamp in 1922; het gebied werd steeds toegankelijker. Hoewel de Duitsers in 1942 de rails opbraken voor de oorlogsindustrie, getuigen de stationsgebouwen van Leens, Ulrum en Zoutkamp nog altijd van dit tijdperk.</p>
        </div>
        <div class="image-side">
          <div class="polaroid rotate-left">
            <img :src="imgFoto + 'gado.webp'" alt="GADO bussen" loading="lazy" />
            <p class="polaroid-caption">De markante GADO bussen</p>
          </div>
        </div>
      </section>

      <div class="page-divider"></div>

      <section class="dorpen-section">
        <h2 class="section-title centered">Dorpen van het Hogeland</h2>
        <p class="centered-subtitle">Karakteristieke kernen in de Marne-regio</p>
        <table class="hogeland-table">
          <thead>
            <tr>
              <th class="th-img">DORP & BEELD</th>
              <th class="th-text">HISTORIE & BETEKENIS</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="dorp in dorpen" :key="dorp.naam">
              <td class="td-img">
                <div class="polaroid table-polaroid">
                  <img :src="imgFoto + dorp.img.split('/').pop()" :alt="dorp.naam" loading="lazy" />
                  <p class="polaroid-caption">{{ dorp.naam }}</p>
                </div>
              </td>
              <td class="td-text">
                <p v-html="dorp.tekst"></p>
              </td>
            </tr>
          </tbody>
        </table>
      </section>
    </div>
  </div>
</template>

<script setup>
// Gebruikt nu de lokale map in /public/images/foto/
const imgFoto = '/images/foto/'

const dorpen = [
  { naam: 'EENRUM', img: 'eenrum.webp', tekst: 'In de 11e eeuw werd het dorp Arneron genoemd. "Arnu" duidt op een plaats waar arenden leefden. De wierde waarop Eenrum ligt is ongeveer 2200 jaar oud. De markante toren werd tussen 1646 en 1652 gebouwd. Alleen de uit 1862 daterende pelmolen "De Lelie" staat er nog. De gehele oude dorpskern is geplaatst op de monumentenlijst.' },
  { naam: 'HORNHUIZEN', img: 'hornhuizen.webp', tekst: 'In 1247 werd dit dorp "Howerahusum" genoemd. Een "Horn" is een nederzetting die op een hoek land aan het water ligt. In Hornhuizen stond ook de in 1803 gesloopte Tammingaborg, die achtereenvolgens werd bewoond door de geslachten Tamminga en Van Meckema.' },
  { naam: 'HOUWERZIJL', img: 'kerkhouwerzijl.jpg', tekst: 'Genoemd naar de oudste sluis van Hunsingo (1350). Daarmee werd het water tot 1728 van het Vlakkeriet op het Reitdiep geloosd. Niet ver er vandaan lag de oude nederzetting Vliedorp. Op de kerkwierde (Ol Weem) ligt alleen nog een kerkhof.' },
  { naam: 'KLEINE HUISJES', img: 'kleinehuisjes.webp', tekst: 'Het dorp ontstond in de 19e eeuw. De naamgeving werd ontleend aan de vele kleine huisjes waarin de arbeiders woonden die werkten op de omliggende boerderijen en bij inpolderingen. De omgrachtte boerderij Feddemaheerdt stamt uit 1765.' },
  { naam: 'KLOOSTERBUREN', img: 'kloosterburen.webp', tekst: 'Een katholieke enclave in het overwegend protestantse Noord-Groningen. Tussen 1163 en 1595 stond hier het klooster Sint-Johannes Evangelista. De grote katholieke kerk uit 1868 heeft met 56 meter de hoogste kerktoren van het Ommeland.' },
  { naam: 'KRUISWEG', img: 'kruisweg.jpg', tekst: 'Pas in de 19e eeuw vestigden zich hier de eerste bewoners; arbeiders die werk vonden bij inpolderingen. De nederzetting kreeg pas in de 20ste eeuw haar huidige naam. Tot 1964 stond hier de cichoreifabriek Musschenga.' },
  { naam: 'LAUWERSOOG', img: 'lauwersoog.webp', tekst: 'De naam wordt ontleend aan de grensrivier de Lauwer. Nadat het Lauwersmeer in 1969 was drooggelegd ontstond een nieuwe dorpskern met haven, die zich ontwikkelde tot een centrum van toerisme en visserij.' },
  { naam: 'LEENS', img: 'leens.webp', tekst: 'Lydenze wordt voor het eerst in 1381 genoemd. De romaanse Petruskerk stamt uit de elfde eeuw. Van de vele borgen in het Marnegebied resteert alleen nog de Borg Verhildersum, ingericht in 19e-eeuwse stijl.' },
  { naam: 'MENSINGEWEER', img: 'mensingeweer.webp', tekst: 'De naam betekent wierde van Menze. De koren- en pelmolen Hollands Welvaart (1855) is een markant bouwwerk. Het orgel in de 13e-eeuwse kerk werd in 1696 gebouwd door de beroemde Arp Schnitger.' },
  { naam: 'MOLENRIJ', img: 'molenrij.jpg', tekst: 'Het dorp dankt zijn naam aan de drie molens die hier ooit stonden. Voor het eerst genoemd in 1846. Hier stond ook de mosterdfabriek waar de bekende Marne-mosterd werd geproduceerd.' },
  { naam: 'NIEKERK', img: 'niekerk.webp', tekst: 'Voor het eerst genoemd in 1445. Het tufstenen romaanse kerkje werd in 1629 verbouwd met geld dat de provincie ontving nadat Piet Hein in 1628 de Zilvervloot op de Spanjaarden had veroverd.' },
  { naam: 'PIETERBUREN', img: 'pieterburen.webp', tekst: 'Genoemd naar Sint Pieter. Sinds de jaren 60 wereldberoemd vanwege de Zeehondencrèche en het Wadlopen. In de 15e-eeuwse kerk hangen rouwborden van de bewoners van de afgebroken borg Dijksterhuis.' },
  { naam: 'SCHOUWERZIJL', img: 'schouwerzijl.webp', tekst: 'Ontstaan bij een sluis (zijl) in een zijtak van het Reitdiep. De naam verwijst naar een plek waar vaartuigen met een boom werden voortbewogen. Ten noorden ervan ligt de oude kerkwierde Maarslag.' },
  { naam: 'ULRUM', img: 'ulrum.webp', tekst: 'Uluringhem (11e eeuw) ligt op twee wierden. Het dorp is de bakermat van de Gereformeerde Kerk; in 1834 vond hier onder dominee Hendrik de Cock de Afscheiding plaats. Hier stond ook de Asingaborg.' },
  { naam: 'VIERHUZEN', img: 'vierhuizen.jpg', tekst: 'In 1525 voor het eerst vermeld als gehucht met vier huizen. Gelegen vlak achter de dijk van de vroegere Lauwerszee. Sicco Mansholt bracht zijn jeugd door op de nabijgelegen boerderij Torum.' },
  { naam: 'WARFHUIZEN', img: 'warfhuizen.jpg', tekst: 'De naam komt voor het eerst in 1398 voor en betekent huizengroep op een werf (wierde). Hier stonden de Gaykingaborg en Lulema. Tegenwoordig is het dorp bekend als bedevaartsoord.' },
  { naam: 'WEHE-DEN HOORN', img: 'wehe-den-hoorn.jpg', tekst: 'Wehe betekent "heiligdom". Den Hoorn ligt in een hoek van de oude Marnedijk. In Den Hoorn staat de Sint Bonifatiuskerk (1926). Tussen 1917 en 1960 stond hier een melkfabriek aan het Hoornsediep.' },
  { naam: 'WESTERNIELAND', img: 'westernieland.jpg', tekst: 'Gesticht rond 1350 als Mariaburen. De huidige naam betekent "westelijk gelegen nieuw land". Na de Kerstvloed van 1717 werd het dorp meer oostwaarts herbouwd. Bekend om de historische robbenvangst.' },
  { naam: 'WIERHUIZEN', img: 'wierhuizen.jpg', tekst: 'Een wier is een heuvel waarop huizen staan. Van dit dorp resteert eigenlijk alleen nog een oud kerkhof. Na de Kerstvloed van 1717 werd de kerk afgebroken voor herstel van de zeedijk.' },
  { naam: 'ZOUTKAMP', img: 'zoutkamp.png', tekst: 'Ontstaan uit een schans uit 1575. Voor het eerst genoemd in 1418 als Solte Campe. Van vestingstad ontwikkelde het zich tot een beroemde vissershaven, bekend om de garnalenverwerking.' },
  { naam: 'ZUURDIJK', img: 'zuurdijk.jpg', tekst: 'Bestond al in 1287 (Sutherdieke). De naam verwijst naar de zuidelijke dijk van het voormalige Marne-eiland. Op de wierde staat windmolen De Zwaluw en bij Ewer staan karakteristieke landarbeiderswoningen.' }
]

useHead({
  title: 'Het Hogeland | Historie van De Marne',
  meta: [
    { name: 'description', content: 'De rijke geschiedenis van het Groningse Hogeland, wierden en dorpen in de gemeente De Marne.' }
  ]
})
</script>

<style scoped>
/* CSS exact behouden zoals aangeleverd */
.hogeland-page {
  background-color: #fdfdfb;
  color: #1a2a3a;
  padding-bottom: 100px;
  width: 100%;
  overflow-x: hidden;
}

.content-container {
  max-width: 1100px;
  margin: 0 auto;
  padding: 0 20px;
}

.hero-section { text-align: center; padding: 60px 0 40px; }
.hero-title { font-family: 'Playfair Display', serif; font-size: 4rem; color: #1a2a3a; font-style: italic; font-weight: 400; margin-bottom: 10px; }
.hero-subtitle { text-transform: uppercase; letter-spacing: 3px; font-size: 0.85rem; color: #d4af37; font-weight: 700; }
.hero-tagline { font-size: 1.1rem; color: #7f8c8d; font-style: italic; margin-top: 10px; }
.hero-divider { width: 80px; height: 2px; background-color: #d4af37; margin: 25px auto; }

.section-title { font-family: 'Playfair Display', serif; font-size: 2.2rem; color: #1a2a3a; margin-bottom: 25px; border-bottom: 1px solid #f4f1ea; display: inline-block; }
.section-title.centered { display: block; text-align: center; border-bottom: none; margin-bottom: 10px; }
.centered-subtitle { text-align: center; font-style: italic; color: #7f8c8d; margin-bottom: 50px; }

.text-block p, .text-side p, .td-text p { font-size: 1.05rem; line-height: 1.8; margin-bottom: 20px; color: #4a5568; }

.polaroid { background: #ffffff; padding: 12px 12px 40px 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.08); border: 1px solid #e2e2d9; width: 100%; max-width: 480px; }
.polaroid img { width: 100%; height: auto; display: block; }
.polaroid-caption { font-family: 'Playfair Display', serif; font-style: italic; font-size: 0.9rem; color: #1a2a3a; text-align: center; margin-top: 15px; }

.rotate-right { transform: rotate(1.5deg); }
.rotate-left { transform: rotate(-1.5deg); }

.content-row { margin-bottom: 100px; }
.content-row.split { display: flex; align-items: center; gap: 60px; }
.content-row.reverse { flex-direction: row-reverse; }
.text-side { flex: 1.2; }
.image-side { flex: 1; display: flex; justify-content: center; }

.page-divider { width: 100%; height: 1px; background-color: #f4f1ea; margin: 60px 0; }

.hogeland-table { width: 100%; border-collapse: collapse; }
.hogeland-table th { text-align: left; padding: 15px; color: #d4af37; border-bottom: 2px solid #f4f1ea; font-size: 0.9rem; }
.td-img { width: 320px; vertical-align: top; padding: 30px 0; }
.td-text { padding: 30px 0 30px 40px; vertical-align: top; }
.table-polaroid { max-width: 280px; padding-bottom: 20px; }

@media (max-width: 900px) {
  .hero-title { font-size: 2.8rem; }
  .content-row.split { flex-direction: column; text-align: center; gap: 40px; }
  .polaroid { transform: rotate(0deg); margin: 0 auto; }
  .hogeland-table thead { display: none; }
  .hogeland-table tr, .td-img, .td-text { display: block; width: 100%; }
  .td-text { padding: 20px 0 60px 0; text-align: center; }
  .td-img { padding-bottom: 0; display: flex; justify-content: center; }
}
</style>