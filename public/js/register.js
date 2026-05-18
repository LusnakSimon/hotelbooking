const regForm = document.getElementById('reg-form');
const errBox = document.getElementById('reg-error');

regForm.addEventListener('submit', async function(e) {
	e.preventDefault();
	const res = await fetch(regForm.action, {method: 'POST', body: new FormData(regForm)});
	const data = await res.json();
	if (data.success) {
		window.location.href = '?c=home&a=index';
	} else {
		errBox.textContent = data.error;
		errBox.classList.remove('d-none');
	}
});

