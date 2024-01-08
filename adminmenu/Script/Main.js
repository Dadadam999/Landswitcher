import ButtonState from './ButtonState.js';
import ListManager from './ListManager.js';

window.addEventListener('load', function () {
    const form = document.getElementById('landswitcher_form');
    const countrySelect = document.getElementById('landswitcher_country');
    const redirectUrlInput = document.getElementById('landswitcher_redirect_url');
    const listManager = new ListManager('url_list');
    const buttonState = new ButtonState('submit_button', 'loading_spinner', 'message_container');

    countrySelect.addEventListener('change', function () {
        const prevISO = countrySelect.getAttribute('data-prev-iso');

        if (prevISO) {
            listManager.updateOptionValue(prevISO, redirectUrlInput.value);
        }

        countrySelect.setAttribute('data-prev-iso', this.value);
        const newOption = listManager.findOrCreateOption(this.value);
        redirectUrlInput.value = newOption.value;
    });

    const initialISO = countrySelect.value;

    if (initialISO) {
        countrySelect.setAttribute('data-prev-iso', initialISO);
        const initialOption = listManager.findOrCreateOption(initialISO);
        redirectUrlInput.value = initialOption.value;
    }

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        buttonState.showLoading();
        const currentISO = countrySelect.value;
        listManager.updateOptionValue(currentISO, redirectUrlInput.value);
        const formData = new FormData(form);
        const redirectsData = listManager.getRedirectsData()
        formData.append('redirectsData', JSON.stringify(redirectsData));

        fetch('/landswitcher/settingsave', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            buttonState.hideLoading();
            buttonState.showMessage(data.message || 'Success');
        })
        .catch(error => {
            buttonState.hideLoading();
            buttonState.showMessage(error.message || 'Error', false);
        });
    });
});
