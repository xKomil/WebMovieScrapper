document.addEventListener('DOMContentLoaded', function() {
    const id_filmu = new URLSearchParams(window.location.search).get('id');
    fetch(`getReviews.php?id=${id_filmu}`)
        .then(response => response.json())
        .then(data => {
            const opinieDiv = document.getElementById('opinie');
            if (data.length > 0) {
                data.forEach(opinia => {
                    const opiniaDiv = document.createElement('div');
                    opiniaDiv.classList.add('opinia');
                    opiniaDiv.innerHTML = `<p><strong>${opinia.author}</strong>: ${opinia.text}</p>`;
                    opinieDiv.appendChild(opiniaDiv);
                });
            } else {
                opinieDiv.innerHTML = '<p>Brak opinii dla tego filmu.</p>';
            }
        })
        .catch(error => {
            console.error('Błąd podczas pobierania opinii:', error);
        });
});
