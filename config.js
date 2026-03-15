// config.js
export const getApiUrl = () => {
  // We kijken in het geheugen van de app of er een URL is opgeslagen
  const savedUrl = typeof window !== 'undefined' ? localStorage.getItem('user_stamboom_api') : null;
  
  // Als er een URL is, gebruik die. Zo niet, blijf leeg (of gebruik je eigen voor testwerk)
  return savedUrl || ''; 
};