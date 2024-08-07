document.addEventListener('DOMContentLoaded', function() {
    const loadingSpinner = document.getElementById('loading-spinner');
    const keywordsContent = document.getElementById('keywords-content');
    const copyKeywordsButton = document.getElementById('copy-keywords');

    document.querySelector('.ajax-link').addEventListener('click', function(event) {
        event.preventDefault();
        const resourceId = this.getAttribute('data-resource-id');

        // Afficher le spinner de chargement
        showLoadingSpinner(true);

        // Créer et envoyer la requête AJAX
        sendAjaxRequest(resourceId)
            .then(response => {
                keywordsContent.innerText = response.data;
                copyKeywordsButton.style.display = 'inline-block';
            })
            .catch(errorMessage => {
                keywordsContent.innerText = errorMessage;
            })
            .finally(() => {
                // Cacher le spinner de chargement
                showLoadingSpinner(false);
            });
    });

    copyKeywordsButton.addEventListener('click', function(event) {
        event.preventDefault();
        copyKeywordsToInput();
    });

    function sendAjaxRequest(resourceId) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', `/admin/ajax/resource/${resourceId}/get-ai-meta-keywords`, true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            resolve(response);
                        } catch (e) {
                            reject('Erreur lors de l\'analyse de la réponse.');
                        }
                    } else {
                        reject(handleError(xhr.responseText));
                    }
                }
            };

            xhr.send(`id=${encodeURIComponent(resourceId)}`);
        });
    }

    function handleError(responseText) {
        try {
            const errorResponse = JSON.parse(responseText);
            return errorResponse.error || 'Une erreur s\'est produite. Veuillez réessayer.';
        } catch {
            return 'Une erreur s\'est produite. Veuillez réessayer.';
        }
    }

    function showLoadingSpinner(show) {
        loadingSpinner.style.display = show ? 'block' : 'none';
    }

    function copyKeywordsToInput() {
        const keywords = keywordsContent.innerText;
        const input = document.querySelector('input[name*="metaKeywords"]');
        if (input) {
            input.value = keywords;
        }
    }
});