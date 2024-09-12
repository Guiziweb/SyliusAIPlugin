document.addEventListener('DOMContentLoaded', function() {

// Attacher l'événement à tous les boutons IA
    document.querySelectorAll(
        '.ai-metaKeywords, ' +
        '.ai-metaDescription, ' +
        '.ai-shortDescription'
    ).forEach(div => {
        div.addEventListener('click', handleAiButtonClick);
    });


    function handleAiButtonClick(event) {
        const div = event.target.closest('div');

        const fieldContainer = div.closest('div.field');
        if (fieldContainer) {
            const input = fieldContainer.querySelector('input[type="text"], textarea'); // Cibler input ou textarea

            if (input) {
                const resourceField = div.getAttribute('data-resource-field');
                const resourceId = div.getAttribute('data-translation-id');
                const url = div.getAttribute('data-url');

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: resourceId,  field: resourceField }),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            const errorDiv = document.createElement('div');
                            errorDiv.className = 'ui red label sylius-validation-error';
                            errorDiv.textContent = data.error;

                            div.parentNode.insertBefore(errorDiv, div.nextSibling);
                        }
                        else  {
                            input.value = data.data;
                        }
                    })
            }
        }
    }
});