import $ from 'jquery';
window.$ = window.jQuery = $;

await import('bootstrap/js/dropdown');
await import('bootstrap/js/alert');

const alertContainer = document.querySelector('#alert-container');
function bsAlertSuccess(text) {
    const div = document.createElement('div');
    div.className = 'alert alert-success alert-dismissable';
    div.role = 'alert';
    div.innerHTML = `
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
        </button>

        <span class="text"></span>
    `;

    div.querySelector('.text').textContent = text;
    alertContainer.appendChild(div);
    setTimeout(() => {
        div.remove();
    }, 5000);
}

for (const button of document.querySelectorAll('.copy-to-clipboard')) {
    button.addEventListener('click', async (e) => {
        e.preventDefault();
        await navigator.clipboard.writeText(button.dataset.text);
        bsAlertSuccess('Copied to clipboard');
    });
}
