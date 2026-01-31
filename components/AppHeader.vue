<template>
  <nav class="main-nav">
    <div class="nav-container">
      <NuxtLink to="/" class="logo">
        <span class="logo-text">HUIZINGA</span>
        <span class="logo-sub">GENEALOGIE</span>
      </NuxtLink>
      
      <div class="nav-links">
        <NuxtLink to="/" class="nav-item">Home</NuxtLink>
        <NuxtLink to="/personen/48-hendrik-olferts-huizinga" class="nav-item">Stamboom</NuxtLink>
        
        <div class="nav-group">
          <span class="nav-label">Geschiedenis</span>
          <div class="dropdown">
            <NuxtLink to="/geschiedenis/houwerzijl">Houwerzijl</NuxtLink>
            <NuxtLink to="/geschiedenis/hogeland">Het Hogeland</NuxtLink>
            <NuxtLink to="/geschiedenis/kaart">Interactieve Kaart</NuxtLink>
            <NuxtLink to="/geschiedenis/tijdlijn">Tijdlijn</NuxtLink>
          </div>
        </div>

        <div class="nav-group">
          <span class="nav-label">Beeldmateriaal</span>
          <div class="dropdown">
            <NuxtLink to="/beeldmateriaal/foto">Fotoalbum</NuxtLink>
            <NuxtLink to="/beeldmateriaal/video-huizinga">Video Huizinga</NuxtLink>
            <NuxtLink to="/beeldmateriaal/video-houwerzijl">Video Houwerzijl</NuxtLink>
          </div>
        </div>

        <NuxtLink to="/stamboek" class="nav-item">Stamboek</NuxtLink>
        <NuxtLink to="/contact" class="nav-item">Contact</NuxtLink>
      </div>
    </div>
  </nav>
</template>

<style scoped>
.main-nav {
  background-color: #1a2a3a;
  padding: 10px 0;
  border-bottom: 3px solid #d4af37;
  position: sticky;
  top: 0;
  z-index: 1000;
}

.nav-container {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 20px;
}

/* Logo */
.logo { text-decoration: none; display: flex; flex-direction: column; }
.logo-text { color: white; font-family: 'Playfair Display', serif; font-size: 1.4rem; letter-spacing: 3px; line-height: 1; }
.logo-sub { color: #d4af37; font-size: 0.7rem; letter-spacing: 2px; margin-top: 4px; }

/* Links & Dropdowns */
.nav-links { display: flex; gap: 20px; align-items: center; }

.nav-item, .nav-label {
  color: white;
  text-decoration: none;
  text-transform: uppercase;
  font-size: 0.8rem;
  font-weight: bold;
  letter-spacing: 1px;
  cursor: pointer;
  transition: color 0.3s;
  padding: 10px 0;
}

.nav-item:hover, .nav-label:hover, .router-link-active {
  color: #d4af37;
}

/* Dropdown Logica */
.nav-group { position: relative; }

.dropdown {
  position: absolute;
  top: 100%;
  left: 0;
  background: white;
  min-width: 200px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.1);
  display: none; /* Standaard verborgen */
  flex-direction: column;
  border-top: 2px solid #d4af37;
}

.nav-group:hover .dropdown {
  display: flex; /* Tonen bij hover */
}

.dropdown a {
  padding: 12px 20px;
  color: #1a2a3a;
  text-decoration: none;
  font-size: 0.85rem;
  border-bottom: 1px solid #f4f1ea;
}

.dropdown a:hover {
  background-color: #fdfcf9;
  color: #d4af37;
}

@media (max-width: 950px) {
  .nav-container { flex-direction: column; }
  .nav-links { gap: 10px; flex-wrap: wrap; justify-content: center; }
}
</style>