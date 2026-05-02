document.addEventListener('DOMContentLoaded', function() {

	// Delete confirmation modal
	const confirmModal = document.getElementById('confirm-modal');
	if (confirmModal) {
		confirmModal.addEventListener('show.bs.modal', function(e) {
			document.getElementById('confirm-modal-ok').href = e.relatedTarget.dataset.href;
			document.getElementById('confirm-modal-message').textContent = e.relatedTarget.dataset.message || 'Are you sure?';
		});
	}

	// AJAX registration form
	const regForm = document.getElementById('reg-form');
	if (!regForm) return;

	const errBox = document.getElementById('reg-error');

	regForm.addEventListener('submit', async function(e) {
		e.preventDefault();
		const res = await fetch(regForm.action, {
			method: 'POST',
			headers: { 'X-Requested-With': 'XMLHttpRequest' },
			body: new FormData(regForm),
			credentials: 'same-origin'
		});
		const data = await res.json();
		if (data.success) {
			window.location.href = '?c=home&a=index';
		} else {
			errBox.textContent = data.error || 'Registration failed.';
			errBox.classList.remove('d-none');
		}
	});

});
