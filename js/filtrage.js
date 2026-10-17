document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('filtre');
    const cartes = document.querySelectorAll('.filtre-carte');

    input.addEventListener('input', () => {
        const valeur = input.value.toLowerCase();

        cartes.forEach((carte) => {
            const texte = carte.innerText.toLowerCase();
            if (texte.includes(valeur)) {
                carte.style.display = 'block';
            } else {
                carte.style.display = 'none';
            }
        });
    });
});
