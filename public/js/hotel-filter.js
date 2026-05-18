const form = document.getElementById('filter-form');
const list = document.getElementById('hotel-list');

form.addEventListener('submit', async function(e) {
	e.preventDefault();
		
	const loc = document.getElementById('location').value;
	const min = document.getElementById('min_price').value;
	const max = document.getElementById('max_price').value;

    const params = new URLSearchParams();
	if (loc) params.append('location', loc);
	if (min) params.append('min_price', min);
	if (max) params.append('max_price', max);

	const res = await fetch(form.action + '&' + params.toString());
	if (!res.ok) return;
	const hotels = await res.json();

	list.innerHTML = '';
    for (const h of hotels) {

        const col = document.createElement('div');
        col.className = 'col-md-6 mb-4';
            
        const card = document.createElement('div');
        card.className = 'card h-100';
            
        if (h.image_path) {
            const img = document.createElement('img');
            img.src = h.image_path;
            img.alt = h.name;
            img.className = 'card-img-top';
            card.appendChild(img);
        }
            
        const body = document.createElement('div');
        body.className = 'card-body';
            
        const title = document.createElement('h5');
        title.className = 'card-title';
        title.textContent = h.name;
        body.appendChild(title);
            
        const text = document.createElement('p');
        text.className = 'card-text';
        text.textContent = h.location + ' — ' + Number(h.price).toFixed(2) + ' €';
        body.appendChild(text);
            
        const link = document.createElement('a');
        link.href = '?c=hotel&a=detail' + '&id=' + h.id;
        link.className = 'btn btn-sm btn-outline-primary';
        link.textContent = 'View';
        body.appendChild(link);
            
        card.appendChild(body);
        col.appendChild(card);
        list.appendChild(col);
    }
});