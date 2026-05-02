document.addEventListener('DOMContentLoaded', function() {

	const form = document.getElementById('filter-form');
	if (!form) return;

	const filterUrl = form.dataset.filterUrl;
	const detailUrl = form.dataset.detailUrl;
	const list = document.getElementById('hotel-list');
	const assetBase = list.dataset.assetBase;

	form.addEventListener('submit', async function(e) {
		e.preventDefault();
		
		const params = new URLSearchParams();
		const loc = document.getElementById('location').value;
		const min = document.getElementById('min_price').value;
		const max = document.getElementById('max_price').value;
		if (loc) params.append('location', loc);
		if (min) params.append('min_price', min);
		if (max) params.append('max_price', max);

		const res = await fetch(filterUrl + '&' + params.toString(), { credentials: 'same-origin' });
		if (!res.ok) return;
		const hotels = await res.json();

		list.innerHTML = '';
		for (const h of hotels) {
			const col = document.createElement('div');
			col.className = 'col-md-6 mb-4';
			col.innerHTML = `
				<div class="card h-100">
					${h.image_path ? `<img src="${assetBase}${h.image_path}" class="card-img-top" alt="${h.name}">` : ''}
					<div class="card-body">
						<h5 class="card-title">${h.name}</h5>
						<p class="card-text">${h.location} — ${Number(h.price).toFixed(2)} €</p>
						<a class="btn btn-sm btn-outline-primary" href="${detailUrl}&id=${encodeURIComponent(h.id)}">View</a>
					</div>
				</div>`;
			list.appendChild(col);
		}
	});

});
