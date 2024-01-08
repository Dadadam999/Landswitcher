export default class ListManager {
    constructor(datalistId) {
        this.urlListDatalist = document.getElementById(datalistId);
    }

    findOrCreateOption(iso) {
        let option = Array.from(this.urlListDatalist.options).find(opt => opt.getAttribute('data-iso') === iso);

        if (!option) {
            option = document.createElement('option');
            option.setAttribute('data-iso', iso);
            option.value = '/';
            this.urlListDatalist.appendChild(option);
        }

        return option;
    }

    updateOptionValue(iso, value) {
        const option = this.findOrCreateOption(iso);
        option.value = value;
    }

    getRedirectsData() {
        const dataListOptions = this.urlListDatalist.querySelectorAll('option');
        return Array.from(dataListOptions).map(option => ({
            cISO: option.getAttribute('data-iso'),
            url: option.value
        }));
    }
}
