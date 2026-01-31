import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const contentDir = path.resolve(__dirname, 'content/personen');
const outputFile = path.join(__dirname, 'stamboom_totaal.json');

const exportData = () => {
  console.log(`🔎 Map: ${contentDir}`);

  if (!fs.existsSync(contentDir)) {
    console.log("❌ Map niet gevonden.");
    return;
  }

  const files = fs.readdirSync(contentDir);
  console.log(`📂 Totaal aantal bestanden gevonden: ${files.length}`);

  const allPersonen = files
    .filter(file => file.endsWith('.json') && file !== 'stamboom_totaal.json')
    .map(file => {
      try {
        const filePath = path.join(contentDir, file);
        const fileContent = fs.readFileSync(filePath, 'utf8');
        
        // We parsen de JSON direct
        const data = JSON.parse(fileContent);
        
        return {
          ...data,
          bestandsnaam: file
        };
      } catch (e) {
        console.error(`⚠️ Kon bestand ${file} niet lezen:`, e.message);
        return null;
      }
    })
    .filter(p => p !== null);

  if (allPersonen.length > 0) {
    fs.writeFileSync(outputFile, JSON.stringify(allPersonen, null, 2));
    console.log(`✅ Succes! ${allPersonen.length} personen zijn samengevoegd in ${outputFile}`);
  } else {
    console.log("⚠️ Geen geldige JSON data gevonden.");
  }
};

exportData();