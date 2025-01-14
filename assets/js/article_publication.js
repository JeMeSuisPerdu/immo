document.addEventListener('DOMContentLoaded', function() {
    // Vérifie si l'URL actuelle correspond à la route souhaitée
    if (window.location.pathname === '/publish_an_article') {

        const maxSize = 6 * 1024 * 1024; // 6 MB

        document.querySelectorAll('.add-photo-button').forEach(button => {
            button.addEventListener('click', function() {
                let index = this.dataset.index;
                document.querySelector(`.photo-input[data-index="${index}"]`).click();
            });
        });

        document.querySelectorAll('.photo-input').forEach(input => {
            input.addEventListener('change', function(event) {
                let index = this.dataset.index;
                let file = event.target.files[0];

                // Vérifier si l'index est -1
                if (index === '-1') {
                    alert('L\'index est invalide.');
                    event.target.value = '';
                    return;
                }

                // Check file type
                const allowedTypes = ['image/jpeg', 'image/png', 'image/svg+xml'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Seuls les fichiers JPEG, PNG et SVG sont autorisés.');
                    event.target.value = '';
                    return;
                }

                // Check file size
                if (file.size > maxSize) {
                    alert('La taille du fichier ne doit pas dépasser 8 Mo.');
                    event.target.value = '';
                    return;
                }

                let reader = new FileReader();

                reader.onload = function(e) {
                    let preview = document.querySelector(`.photo-preview[data-index="${index}"]`);
                    preview.src = e.target.result;
                    preview.style.display = 'block';

                    let addButton = document.querySelector(`.add-photo-button[data-index="${index}"]`);
                    addButton.style.display = 'none';

                    let removeButton = document.querySelector(`.remove-photo-button[data-index="${index}"]`);
                    removeButton.style.display = 'block';
                };
                reader.readAsDataURL(file);
            });
        });

        // Vérifier avant la soumission du formulaire
        document.querySelector('.form-article').addEventListener('submit', function(event) {
            let validPhotos = false;
            document.querySelectorAll('.photo-input').forEach(input => {
                if (input.files.length > 0) {
                    validPhotos = true;
                }
            });

            if (!validPhotos) {
                alert('Veuillez ajouter au moins une photo avant de publier.');
                event.preventDefault(); // Empêche la soumission du formulaire
            }
        });

        document.querySelectorAll('.remove-photo-button').forEach(button => {
            button.addEventListener('click', function() {
                let index = this.dataset.index;
                
                let preview = document.querySelector(`.photo-preview[data-index="${index}"]`);
                preview.src = '#';
                preview.style.display = 'none';

                let input = document.querySelector(`.photo-input[data-index="${index}"]`);
                input.value = '';

                let addButton = document.querySelector(`.add-photo-button[data-index="${index}"]`);
                addButton.style.display = 'block';

                this.style.display = 'none';
            });
        });
    }
});
