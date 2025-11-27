/* -----------------------------------------------------------
   Autocomplétion Adresse (API Gouv)
----------------------------------------------------------- */
document.addEventListener('DOMContentLoaded', function() {
    
    const input = document.getElementById('addressInput');
    const suggestions = document.getElementById('suggestions');

    // On vérifie si les éléments existent sur la page avant de lancer le script
    if (input && suggestions) {
        
        input.addEventListener('input', async () => {
            const query = input.value.trim();

            // Pas de recherche si moins de 3 caractères
            if (query.length < 3) {
                suggestions.innerHTML = '';
                return;
            }

            try {
                // Appel à l'API Data Gouv
                const response = await fetch(`https://api-adresse.data.gouv.fr/search/?q=${encodeURIComponent(query)}&limit=5`);
                const data = await response.json();

                suggestions.innerHTML = ''; // On vide la liste précédente

                data.features.forEach(feature => {
                    const li = document.createElement('li');
                    li.textContent = feature.properties.label;
                    
                    // Au clic, on remplit le champ et on vide la liste
                    li.addEventListener('click', () => {
                        input.value = feature.properties.label;
                        suggestions.innerHTML = '';
                    });
                    
                    suggestions.appendChild(li);
                });
            } catch (error) {
                console.error('Erreur API BAN:', error);
            }
        });

        // Fermer la liste si on clique ailleurs sur la page
        document.addEventListener('click', (e) => {
            if (e.target !== input && e.target !== suggestions) {
                suggestions.innerHTML = '';
            }
        });
    }
});