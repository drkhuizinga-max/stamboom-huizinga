<script setup>
// Haal de lijst op. Nuxt 3 Content vindt alles in 'content/personen'
const { data: personen } = await useAsyncData('stamboom-overzicht', () => {
  return queryContent('personen').find()
})

const searchQuery = ref('')
const zoekResultaten = computed(() => {
  const q = searchQuery.value.toLowerCase().trim()
  if (q.length < 2) return []
  return (personen.value || []).filter(p => p.naam?.toLowerCase().includes(q)).slice(0, 15)
})
</script>

<template>
  <div style="max-width: 800px; margin: 0 auto; padding: 20px; font-family: sans-serif;">
    <h1>Stamboom Overzicht</h1>
    
    <input v-model="searchQuery" placeholder="Zoek op naam..." 
           style="width: 100%; padding: 12px; margin-bottom: 20px; border: 2px solid #ccc; border-radius: 8px; width: 100%;">

    <div v-if="searchQuery.length >= 2">
      <div v-for="p in zoekResultaten" :key="p._path" style="padding: 10px; border-bottom: 1px solid #eee;">
        <NuxtLink :to="p._path" style="color: #4338ca; text-decoration: none; font-weight: bold;">
          {{ p.naam }}
        </NuxtLink>
      </div>
    </div>

    <div v-else style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
      <NuxtLink v-for="p in personen?.slice(0, 50)" :key="p._path" :to="p._path" 
                style="padding: 10px; background: #f1f5f9; border-radius: 4px; text-decoration: none; color: #333; font-size: 0.9em;">
        {{ p.naam }}
      </NuxtLink>
    </div>
  </div>
</template>