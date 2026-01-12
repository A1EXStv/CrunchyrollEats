function toggleFilters() {
    const sidebar = document.getElementById('filtersSidebar');
    const productsCol = document.getElementById('productsCol');
    const btnText = document.getElementById('filterBtnText');

    if (window.innerWidth > 991) {
        // Desktop: hide/show sidebar
        if (getComputedStyle(sidebar).display === 'none') {
            sidebar.style.display = 'block';
            productsCol.classList.remove('col-lg-12');
            productsCol.classList.add('col-lg-9');
            btnText.textContent = 'Ocultar Filtros';
        } else {
            sidebar.style.display = 'none';
            productsCol.classList.remove('col-lg-9');
            productsCol.classList.add('col-lg-12');
            btnText.textContent = 'Mostrar Filtros';
        }
    } else {
        // Mobile: toggle modal
        sidebar.classList.toggle('show');
        if (sidebar.classList.contains('show')) {
            btnText.textContent = 'Cerrar Filtros';
        } else {
            btnText.textContent = 'Mostrar Filtros';
        }
    }
}

function toggleCategory(button) {
    const content = button.nextElementSibling;
    const icon = button.querySelector('i');

    if (content.style.display === 'none') {
        content.style.display = 'block';
        icon.classList.remove('fa-plus');
        icon.classList.add('fa-minus');
    } else {
        content.style.display = 'none';
        icon.classList.remove('fa-minus');
        icon.classList.add('fa-plus');
    }
}

// Close filters modal when clicking outside on mobile
document.addEventListener('click', function (event) {
    const sidebar = document.getElementById('filtersSidebar');
    const toggleBtn = document.getElementById('toggleFiltersBtn');

    if (sidebar && sidebar.classList.contains('show')) {
        if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
            sidebar.classList.remove('show');
            document.getElementById('filterBtnText').textContent = 'Mostrar Filtros';
        }
    }
});

function incrementQty() {
    const input = document.getElementById('quantity');
    input.value = parseInt(input.value) + 1;
}

function decrementQty() {
    const input = document.getElementById('quantity');
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}

function addToCartWithQty(producto) {
    const cantidad = parseInt(document.getElementById('quantity').value);
    // AquÃ­ debes implementar tu funciÃ³n addToCart existente
    // pasando el producto y la cantidad
    for (let i = 0; i < cantidad; i++) {
        addToCart(producto);
    }
}

