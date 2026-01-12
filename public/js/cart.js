/**
 * Cart Logic using LocalStorage
 */

const CartKey = 'crunchyroll_eats_cart';

function getCart() {
    const cart = localStorage.getItem(CartKey);
    return cart ? JSON.parse(cart) : [];
}

function saveCart(cart) {
    localStorage.setItem(CartKey, JSON.stringify(cart));
    updateCartCount();
}

/**
 * Add a product to the cart
 * @param {Object} product - Product object with id_producto, nombre, precio, imagen
 */
function addToCart(product) {
    const cart = getCart();
    const existingItem = cart.find(item => item.id_producto == product.id_producto);
    const finalPrice = product.hasOwnProperty('precio_final') ? parseFloat(product.precio_final) : parseFloat(product.precio);

    if (existingItem) {
        existingItem.quantity += 1;
        // Keep updated price info just in case
        existingItem.precio_final = finalPrice;
        console.log('Increased quantity:', product.nombre);
    } else {
        cart.push({
            id_producto: product.id_producto,
            nombre: product.nombre,
            precio: parseFloat(product.precio), // Original price
            precio_final: finalPrice,          // Final price after discount
            imagen: product.imagen,
            quantity: 1
        });
        console.log('Added new item:', product.nombre);
    }

    saveCart(cart);

    // Show feedback (simple alert for now, can be improved)
    alert('¡Producto añadido al carrito!');
}

/**
 * Remove a product from the cart
 */
function removeFromCart(productId) {
    let cart = getCart();
    cart = cart.filter(item => item.id_producto != productId);
    saveCart(cart);
}

/**
 * Update quantity of a product
 */
function updateQuantity(productId, quantity) {
    const cart = getCart();
    const item = cart.find(item => item.id_producto == productId);

    if (item) {
        item.quantity = parseInt(quantity);
        if (item.quantity <= 0) {
            removeFromCart(productId);
            return;
        }
        saveCart(cart);
    }
}

/**
 * Clear the cart completely
 */
function clearCart() {
    localStorage.removeItem(CartKey);
    updateCartCount();
}

/**
 * Update the cart count badge in the navbar
 */
function updateCartCount() {
    const cart = getCart();
    const count = cart.reduce((total, item) => total + item.quantity, 0);
    const badge = document.getElementById('cart-count');

    if (badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'block' : 'none';

        // Add animation class
        badge.classList.add('animate__animated', 'animate__bounceIn');
        setTimeout(() => {
            badge.classList.remove('animate__animated', 'animate__bounceIn');
        }, 1000);
    }
}

// Initialize on load
document.addEventListener('DOMContentLoaded', () => {
    updateCartCount();
});
