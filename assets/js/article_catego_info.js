document.addEventListener('DOMContentLoaded', () => {
    const categorySelect = document.querySelector('select[name$="[category]"]');
    const subcategorySelect = document.querySelector('select[name$="[subcategory]"]');
    const attributesContainer = document.getElementById('attributes-container');
    const attributContainer = document.querySelector('.attributes-section');

    // Assurez-vous que categorySelect et subcategorySelect existent avant d'accéder à leurs propriétés
    if (categorySelect && subcategorySelect) {
        if (!categorySelect.value) {
            subcategorySelect.disabled = true;
        }

        categorySelect.addEventListener('change', function () {
            const categoryId = this.value;

            if (categoryId) {
                fetch(`/get-subcategories/${categoryId}`)
                    .then(response => response.ok ? response.json() : Promise.reject(response))
                    .then(data => {
                        subcategorySelect.innerHTML = data.length ?
                            '<option value="">Choisir une sous-catégorie</option>' :
                            '<option value="">Aucune sous-catégorie disponible</option>';

                        data.forEach(subcategory => {
                            const option = document.createElement('option');
                            option.value = subcategory.id;
                            option.textContent = subcategory.name;
                            subcategorySelect.appendChild(option);
                        });
                        subcategorySelect.disabled = data.length === 0;
                    })
                    .catch(error => {
                        console.error('Erreur lors de la récupération des sous-catégories :', error);
                        subcategorySelect.innerHTML = '<option value="">Erreur lors de la récupération des sous-catégories</option>';
                        subcategorySelect.disabled = true;
                    });
            } else {
                subcategorySelect.innerHTML = '<option value="">Choisir une sous-catégorie</option>';
                subcategorySelect.disabled = true;
            }
        });

        subcategorySelect.addEventListener('change', function () {
            const subcategoryId = this.value;
            if (subcategoryId) {
                fetch(`/get-attributes/${subcategoryId}`)
                    .then(response => response.ok ? response.json() : Promise.reject(response))
                    .then(data => {
                        attributesContainer.innerHTML = '';

                        if (data.length) {
                            attributContainer.style.display = 'block';

                            // Ajouter les attributs dynamiquement
                            data.forEach(attribute => {
                                let inputElement;

                                switch (attribute.type) {
                                    case 'string':
                                        inputElement = `<div class="flex-column">
                                            <label>${attribute.name} :</label>
                                            </div>
                                            <div class="inputCategory">
                                            <input class="listboxCategory" type="text" name="attributes[${attribute.id}]" placeholder="${attribute.name}">
                                        </div>`;
                                        break;
                                    case 'integer':
                                        inputElement = `<div class="flex-column">
                                            <label>${attribute.name} :</label>
                                            </div>
                                            <div class="inputCategory">
                                            <input class="listboxCategory" type="number" name="attributes[${attribute.id}]" placeholder="${attribute.name}">
                                        </div>`;
                                        break;
                                    case 'date':
                                        inputElement = `<div class="flex-column">
                                            <label>${attribute.name} :</label>
                                            </div>
                                            <div class="inputCategory">
                                            <input class="listboxCategory" type="date" name="attributes[${attribute.id}]" placeholder="${attribute.name}">
                                        </div>`;
                                        break;
                                    case 'boolean':
                                        inputElement = `<div class="flex-column">
                                            <label>${attribute.name} :</label>
                                            </div>
                                            <div>
                                            <input type="checkbox" name="attributes[${attribute.id}]" value="1">
                                        </div>`;
                                        break;
                                    case 'choice':
                                        inputElement = `<div class="flex-column">
                                            <label>${attribute.name} :</label>
                                            </div>
                                            <div class="inputArticleForm">
                                            <select class="listborderCategory" name="attributes[${attribute.id}]">
                                                ${attribute.choices.map(choice => `<option value="${choice}">${choice}</option>`).join('')}
                                            </select>
                                        </div>`;
                                        break;
                                    default:
                                        inputElement = `<div class="flex-column">
                                            <label>${attribute.name} :</label>
                                            </div>
                                            <div class="inputCategory">
                                            <input type="text" name="attributes[${attribute.id}]" placeholder="${attribute.name}">
                                        </div>`;
                                }

                                attributesContainer.insertAdjacentHTML('beforeend', inputElement);
                            });
                        } else {
                            attributContainer.style.display = 'none'; // Masquer la section si aucun attribut n'est disponible
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors de la récupération des attributs :', error);
                        attributesContainer.innerHTML = '';
                        attributContainer.style.display = 'none'; // Masquer la section en cas d'erreur
                    });
            } else {
                attributesContainer.innerHTML = '';
                attributContainer.style.display = 'none'; // Masquer la section si aucune sous-catégorie n'est sélectionnée
            }
        });
    } else {
        console.error('Éléments du formulaire non trouvés.');
    }
});
