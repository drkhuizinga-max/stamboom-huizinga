<template>
  <div style="padding: 50px; text-align: center; font-family: sans-serif;">
    <h2>Stamboom Laden...</h2>
    <p>U wordt doorgestuurd naar Hendrik Olferts Huizinga.</p>
  </div>
</template>

<script setup>
// Gebruik een directe redirect die Nuxt begrijpt
onMounted(() => {
  setTimeout(() => {
    window.location.href = '/personen/48-hendrik-olferts-huizinga'
  }, 500)
})
</script>