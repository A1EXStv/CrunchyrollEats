
console.log("CHECKOUT SCRIPT CARGADO CORRECTAMENTE");

document.addEventListener('DOMContentLoaded', () => {
    loadCheckoutSummary();

    // Card formatting
    const cardInput = document.getElementById('cardNum');
    if (cardInput) {
        cardInput.addEventListener('input', (e) => {
            let v = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let matches = v.match(/\d{4,16}/g);
            let match = matches && matches[0] || '';
            let parts = [];
            for (let i = 0, len = match.length; i < len; i += 4) {
                parts.push(match.substring(i, i + 4));
            }
            if (parts.length) {
                e.target.value = parts.join(' ');
            } else {
                e.target.value = v;
            }
        });
    }

    // Expiry formatting
    const expiryInput = document.getElementById('cardExpiry');
    if (expiryInput) {
        expiryInput.addEventListener('input', (e) => {
            let v = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            if (v.length >= 2) {
                e.target.value = v.substring(0, 2) + '/' + v.substring(2, 4);
            } else {
                e.target.value = v;
            }
        });
    }

    // CVV formatting
    const cvvInput = document.getElementById('cardCvv');
    if (cvvInput) {
        cvvInput.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
        });
    }
});

function loadCheckoutSummary() {
    // getCart is defined in cart.js which must be loaded before this
    if (typeof getCart !== 'function') return;

    const cart = getCart();
    if (cart.length === 0) {
        window.location.href = 'index.php?controller=carrito&action=ver';
        return;
    }

    const itemsContainer = document.getElementById('checkout-items');
    if (!itemsContainer) return;

    itemsContainer.innerHTML = ''; // Clear existing items to avoid duplicates if called multiple times

    let subtotal = 0;

    cart.forEach(item => {
        const itemPrice = parseFloat(item.precio_final) || parseFloat(item.precio);
        const itemTotal = itemPrice * item.quantity;
        subtotal += itemTotal;

        const div = document.createElement('div');
        div.className = 'checkout-item';
        div.innerHTML = `
            <img src="${item.imagen}" alt="${item.nombre}">
            <div class="checkout-item-details">
                <div class="checkout-item-name">${item.nombre}</div>
                <div class="checkout-item-qty">Cantidad: ${item.quantity}</div>
            </div>
            <div class="checkout-item-price">
                ${itemTotal.toFixed(2).replace('.', ',')}€
            </div>
            <button class="checkout-item-remove" onclick="removeItemCheckout(${item.id_producto})">Eliminar</button>
        `;
        itemsContainer.appendChild(div);
    });

    const iva = subtotal * 0.10;
    const total = subtotal + iva;

    const subtotalEl = document.getElementById('checkout-subtotal');
    if (subtotalEl) subtotalEl.textContent = subtotal.toFixed(2).replace('.', ',') + '€';

    const ivaEl = document.getElementById('checkout-iva');
    if (ivaEl) ivaEl.textContent = iva.toFixed(2).replace('.', ',') + '€';

    const totalEl = document.getElementById('checkout-total');
    if (totalEl) totalEl.textContent = total.toFixed(2).replace('.', ',') + '€';
}

function removeItemCheckout(id) {
    if (confirm('¿Quieres eliminar este producto del pedido?')) {
        removeFromCart(id);
        location.reload();
    }
}

function processPayment(event) {
    event.preventDefault();
    console.log("processPayment INICIADO"); // DEBUG

    // getCart is in cart.js
    const cart = getCart();
    if (cart.length === 0) {
        alert('El carrito está vacío');
        return;
    }

    const btn = document.getElementById('payBtn');
    const btnText = document.getElementById('btnText');
    const spinner = document.getElementById('btnSpinner');

    btn.disabled = true;
    btnText.textContent = 'PROCESANDO...';
    spinner.classList.remove('d-none');

    // Gather Address Data
    const formData = new FormData(document.getElementById('checkoutForm'));
    const addressData = {
        direccion: formData.get('direccion'),
        ciudad: formData.get('ciudad'),
        cp: formData.get('cp'),
        provincia: formData.get('provincia'),
        pais: formData.get('pais')
    };

    // Calculate Total (should ideally be done on server, but sending for now)
    let total = 0;
    cart.forEach(item => {
        const itemPrice = parseFloat(item.precio_final) || parseFloat(item.precio);
        total += itemPrice * item.quantity;
    });

    // Send to server
    fetch('index.php?controller=checkout&action=process', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            address: addressData,
            cart: cart,
            total: total
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Success
                if (typeof clearCart === 'function') {
                    clearCart();
                } else {
                    localStorage.removeItem('crunchyroll_eats_cart');
                    if (typeof updateCartCount === 'function') updateCartCount();
                }

                const successModalEl = document.getElementById('successModal');
                if (successModalEl) {
                    const successModal = new bootstrap.Modal(successModalEl);
                    successModal.show();
                }
            } else {
                alert('Error: ' + data.message);
                resetBtn();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un problema al procesar el pedido.');
            resetBtn();
        });

    function resetBtn() {
        btn.disabled = false;
        btnText.textContent = 'CONFIRMAR PEDIDO';
        spinner.classList.add('d-none');
    }
}
