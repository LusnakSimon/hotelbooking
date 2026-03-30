document.addEventListener('DOMContentLoaded', function(){
	// Delete confirmation modal
	const confirmModal = document.getElementById('confirm-modal');
	if (confirmModal) {
		confirmModal.addEventListener('show.bs.modal', function(e) {
			document.getElementById('confirm-modal-ok').href = e.relatedTarget.dataset.href;
			document.getElementById('confirm-modal-message').textContent = e.relatedTarget.dataset.message || 'Are you sure?';
		});
	}


	const regForm = document.querySelector('form[action*="auth&a=register"]');
	if (!regForm) return;

	const errBox = document.getElementById('reg-error');
	const setErr = (msg) => { errBox.textContent = msg; errBox.classList.remove('d-none'); };

	regForm.addEventListener('submit', async function(e){
		e.preventDefault();

		const pwd = regForm.querySelector('[name="password"]').value || '';
		const confirm = regForm.querySelector('[name="confirm_password"]').value || '';
		const email = regForm.querySelector('[name="email"]').value || '';

		if (!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email)) { setErr('Invalid email address.'); return; }
		if (pwd.length < 8) { setErr('Password must be at least 8 characters.'); return; }
		if (!(/[A-Z]/.test(pwd) && /[a-z]/.test(pwd) && /[0-9]/.test(pwd) && /[^A-Za-z0-9]/.test(pwd))) {
			setErr('Password must include upper, lower, digit and special char.'); return;
		}
		if (pwd !== confirm) { setErr('Passwords do not match.'); return; }

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
			setErr(data.error || 'Registration failed.');
		}
	});
});
