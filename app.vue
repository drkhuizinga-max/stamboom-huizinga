<template>
  <div class="site-wrapper">
    <AppHeader />

    <div class="global-banner">
      <img src="/images/foto/houwerzijl.webp" alt="Panorama Houwerzijl" />
      <div class="banner-overlay"></div>
    </div>
    
    <NuxtPage />
  </div>
</template>

<style>
/* CSS voor de banner */
.global-banner {
  width: 100%;
  height: 250px; /* Je kunt dit aanpassen naar wens */
  position: relative;
  overflow: hidden;
  border-bottom: 2px solid #d4af37;
}

.global-banner img {
  width: 100%;
  height: 100%;
  object-fit: cover; 
  /* Verander 30% naar een hoger getal om het dorp in beeld te brengen */
  object-position: center 70%; 
}

.banner-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(to bottom, rgba(26, 42, 58, 0.2), transparent);
}

/* Zorg dat de wrapper geen witruimte heeft */
.site-wrapper {
  display: flex;
  flex-direction: column;
}

@media (max-width: 768px) {
  .global-banner {
    height: 150px;
  }
}
</style>