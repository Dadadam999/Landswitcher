export default class ButtonState {
    constructor(submitBtnId, loadingSpinnerId, messageContainerId) {
        this.submitBtn = document.getElementById(submitBtnId);
        this.loadingSpinner = document.getElementById(loadingSpinnerId);
        this.messageContainer = document.getElementById(messageContainerId);
    }

    showLoading() {
        this.submitBtn.disabled = true;
        this.loadingSpinner.style.display = 'inline-block';
        this.messageContainer.style.display = 'none';
    }

    hideLoading() {
        this.submitBtn.disabled = false;
        this.loadingSpinner.style.display = 'none';
    }

    showMessage(message, isSuccess = true) {
        this.messageContainer.textContent = message;
        this.messageContainer.className = isSuccess ? 'message success' : 'message error';
        this.messageContainer.style.display = 'block';

        setTimeout(() => {
            this.hideMessage();
        }, 3000);
    }

    hideMessage() {
        let opacity = 1;

        const fadeOutInterval = setInterval(() => {
            if (opacity <= 0.1) {
                clearInterval(fadeOutInterval);
                this.messageContainer.style.display = 'none';
            }

            this.messageContainer.style.opacity = opacity.toString();
            opacity -= opacity * 0.1;
        }, 50);
    }
}   