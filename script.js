document.addEventListener('DOMContentLoaded', function() {
    const cookieCard = document.getElementById('cookie-message');
    const acceptButton = document.getElementById('accept-cookies');
    const declineButton = document.getElementById('decline-cookies');

    // Vérifiez si le cookie a déjà été accepté
    if (!localStorage.getItem('cookiesAccepted')) {
        cookieCard.style.display = 'flex'; // Affiche la carte des cookies
    }

    acceptButton.addEventListener('click', function() {
        localStorage.setItem('cookiesAccepted', 'true'); // Enregistre l'acceptation
        cookieCard.style.display = 'none'; // Masque la carte
    });

    declineButton.addEventListener('click', function() {
        cookieCard.style.display = 'none'; // Masque la carte
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const carousel = document.querySelector('.recipe-carousel');
    let scrollAmount = 0;

    if (carousel) {
        const scrollInterval = 30; // Vitesse du défilement
        const scrollStep = 1; // Distance à chaque pas

        setInterval(() => {
            carousel.scrollLeft += scrollStep;
            if (carousel.scrollLeft >= carousel.scrollWidth - carousel.offsetWidth) {
                carousel.scrollLeft = 0; // Retour au début
            }
        }, scrollInterval);
    }
});

document.addEventListener('DOMContentLoaded', function () {
    // Ouvrir la modale et afficher les détails de la recette
    const openModalButtons = document.querySelectorAll('.open-modal');
    const modal = document.getElementById('recipeModal');
    const closeModalButton = modal.querySelector('.close');
    const recipeIdInput = document.getElementById('recipe_id');
    const userNameInput = document.getElementById('user_name');
    const commentInput = document.getElementById('comment');
    const commentForm = document.getElementById('comment-form');
    const modalTitle = document.getElementById('modal-title');
    const modalDescription = document.getElementById('modal-description');
    const modalIngredients = document.getElementById('modal-ingredients');
    const modalInstructions = document.getElementById('modal-instructions');
    const modalCommentsList = document.getElementById('modal-comments');

    openModalButtons.forEach(button => {
        button.addEventListener('click', function () {
            const recipeId = button.getAttribute('data-recipe-id');

            // Charger les détails de la recette via AJAX
            fetch(`get_recipe_details.php?recipe_id=${recipeId}`)
                .then(response => response.json())
                .then(data => {
                    // Mettre à jour la modale avec les données
                    modalTitle.textContent = data.title;
                    modalDescription.textContent = data.description;
                    modalIngredients.textContent = data.ingredients;
                    modalInstructions.textContent = data.instructions;

                    // Vider la liste des commentaires
                    modalCommentsList.innerHTML = '';
                    data.comments.forEach(comment => {
                        const commentItem = document.createElement('li');
                        commentItem.innerHTML = `<strong>${comment.user_name}</strong>: ${comment.comment} <em>Publié le ${comment.created_at}</em>`;
                        modalCommentsList.appendChild(commentItem);
                    });

                    // Afficher la modale
                    recipeIdInput.value = recipeId;
                    modal.style.display = 'block';
                })
                .catch(error => console.error('Erreur:', error));
        });
    });

    // Fermer la modale
    closeModalButton.addEventListener('click', function () {
        modal.style.display = 'none';
    });

    // Fermer la modale si l'utilisateur clique à l'extérieur de la modale
    window.addEventListener('click', function (event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    commentForm.addEventListener('submit', function (event) {
        event.preventDefault();
    
        const submitButton = commentForm.querySelector('button[type="submit"]');
        submitButton.disabled = true;
    
        const formData = new FormData(commentForm);
        fetch('add_comment.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Vérifiez si les données sont valides avant de les ajouter
            if (data && data.user_name && data.comment && data.created_at) {
                const newComment = document.createElement('li');
                newComment.innerHTML = `<strong>${data.user_name}</strong>: ${data.comment} <em>Publié le ${data.created_at}</em>`;
                modalCommentsList.appendChild(newComment);
            } else {
                console.error('Erreur dans les données de la réponse:', data);
            }
    
            // Réinitialiser le formulaire et réactiver le bouton
            commentForm.reset();
            submitButton.disabled = false;
        })
        .catch(error => {
            console.error('Erreur:', error);
            submitButton.disabled = false;
        });
    });
    
});

