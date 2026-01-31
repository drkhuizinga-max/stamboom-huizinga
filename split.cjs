const fs = require('fs');
const path = require('path');

// Pad naar je huidige grote bestand
const inputFile = './assets/data/stamboom.json';
const outputDir = './content/personen';

if (!fs.existsSync(outputDir)){
    fs.mkdirSync(outputDir, { recursive: true });
}

try {
    const rawData = fs.readFileSync(inputFile, 'utf8');
    const data = JSON.parse(rawData);
    const leden = data.leden || data;

    leden.forEach(persoon => {
        // Maak een veilige bestandsnaam: ID-naam.json
        const safeName = `${persoon.id}-${persoon.naam.toLowerCase().replace(/\s+/g, '-')}`.replace(/[^\w-]/g, '');
        const filePath = path.join(outputDir, `${safeName}.json`);
        
        fs.writeFileSync(filePath, JSON.stringify(persoon, null, 2));
        console.log(`Gemaakt: ${filePath}`);
    });

    console.log('\nKlaar! Alle personen zijn nu losse bestanden.');
} catch (err) {
    console.error('Oeps, er ging iets mis:', err.message);
}