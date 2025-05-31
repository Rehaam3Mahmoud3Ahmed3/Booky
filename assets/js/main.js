// PDF Modal Loading
document.addEventListener('DOMContentLoaded', function() {
    const pdfModal = document.getElementById('pdfModal');
    if (pdfModal) {
        pdfModal.addEventListener('show.bs.modal', function() {
            const iframe = this.querySelector('iframe');
            const loadingHTML = `
                <div class="pdf-loading">
                    <div class="spinner-border text-pink" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>`;
            
            // Add loading
            iframe.insertAdjacentHTML('afterend', loadingHTML);
            
            // remove spinner
            iframe.onload = function() {
                const loadingElement = iframe.nextElementSibling;
                if (loadingElement && loadingElement.classList.contains('pdf-loading')) {
                    loadingElement.remove();
                }
            };
        });
    }
});