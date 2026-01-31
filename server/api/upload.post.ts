import { writeFile } from 'fs/promises'
import path from 'path'

export default defineEventHandler(async (event) => {
  const files = await readMultipartFormData(event)
  if (!files || files.length === 0) return { error: 'Geen bestand ontvangen' }

  const file = files[0] // De geüploade foto
  const filename = file.filename || 'upload-' + Date.now() + '.webp'
  
  // Sla op in de public/images map
  const filePath = path.join(process.cwd(), 'public/images', filename)
  await writeFile(filePath, file.data)

  // Geef het schone pad terug voor de database/markdown
  return { 
    url: `images/${filename}` 
  }
})