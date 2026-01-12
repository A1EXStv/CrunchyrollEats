<?php
// src/view/checkout.php
?>
<style>
/* Checkout specific styles */
.checkout-section {
    background: #f8f9fa;
    padding-bottom: 3rem;
}

.section-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 1.5rem;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.subsection-title {
    font-size: 1rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 1.25rem;
    margin-top: 1rem;
}

.form-container {
    background: white;
    border-radius: 8px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}

.form-label {
    font-size: 0.9rem;
    color: #333;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.form-control {
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 0.75rem;
    font-size: 0.95rem;
}

.form-control:focus {
    border-color: #ff6b35;
    box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.15);
}

.back-link {
    display: inline-flex;
    align-items: center;
    text-decoration: none;
    color: #666;
    margin-bottom: 1.5rem;
    font-size: 0.95rem;
    transition: color 0.2s;
}

.back-link:hover {
    color: #ff6b35;
}

.info-box {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    padding: 1rem;
    margin-bottom: 1.5rem;
}

.info-label {
    font-size: 0.85rem;
    color: #888;
    margin-bottom: 0.25rem;
}

.info-value {
    font-weight: 500;
    color: #333;
}

/* Summary Column Styles */
.summary-container {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 2rem;
    position: sticky;
    top: 2rem;
}

.summary-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 1.5rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1rem;
    font-size: 0.95rem;
    color: #555;
}

.summary-value {
    font-weight: 600;
    color: #333;
}

.summary-row.total-row {
    border-top: 1px solid #dee2e6;
    margin-top: 1rem;
    padding-top: 1rem;
    color: #000;
    font-size: 1.1rem;
}

.summary-row.total-row .summary-value {
    font-size: 1.25rem;
    font-weight: 700;
}

.text-success {
    color: #198754 !important;
}

.btn-confirm {
    background-color: #ff640b;
    color: white;
    border: none;
    width: 100%;
    padding: 12px;
    border-radius: 25px;
    font-weight: 700;
    font-size: 1rem;
    margin-top: 1.5rem;
    transition: background-color 0.2s;
}

.btn-confirm:hover {
    background-color: #e55a0a;
    color: white;
}

.summary-note {
    font-size: 0.8rem;
    color: #999;
    text-align: right;
    margin-top: 0.5rem;
}

/* Mini Cart Items in Checkout */
.cart-title {
    font-size: 1rem;
    font-weight: 600;
    margin: 2rem 0 1rem;
    padding-top: 1rem;
    border-top: 1px solid #eee;
}

.checkout-item {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #f8f9fa;
}

.checkout-item:last-child {
    border-bottom: none;
}

.checkout-item img {
    width: 60px;
    height: 60px;
    object-fit: contain;
    border-radius: 6px;
    margin-right: 1rem;
    border: 1px solid #eee;
}

.checkout-item-details {
    flex-grow: 1;
}

.checkout-item-name {
    font-size: 0.9rem;
    font-weight: 600;
    color: #333;
    line-height: 1.2;
    margin-bottom: 0.25rem;
}

.checkout-item-qty {
    font-size: 0.85rem;
    color: #666;
}

.checkout-item-price {
    font-weight: 600;
    color: #ff640b;
    font-size: 0.95rem;
}

.checkout-item-remove {
    margin-left: 1rem;
    color: #999;
    background: none;
    border: none;
    font-size: 0.8rem;
    text-decoration: underline;
    cursor: pointer;
}
.checkout-item-remove:hover {
    color: #dc3545;
}

/* Animations */
.checkmark__circle {
    stroke-dasharray: 166;
    stroke-dashoffset: 166;
    stroke-width: 2;
    stroke-miterlimit: 10;
    stroke: #7ac142;
    fill: none;
    animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
}
.checkmark {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: block;
    stroke-width: 2;
    stroke: #fff;
    stroke-miterlimit: 10;
    margin: 10% auto;
    box-shadow: inset 0px 0px 0px #7ac142;
    animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
}
.checkmark__check {
    transform-origin: 50% 50%;
    stroke-dasharray: 48;
    stroke-dashoffset: 48;
    animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
}
@keyframes stroke { 100% { stroke-dashoffset: 0; } }
@keyframes scale { 0%, 100% { transform: none; } 50% { transform: scale3d(1.1, 1.1, 1); } }
@keyframes fill { 100% { box-shadow: inset 0px 0px 0px 40px #7ac142; } }
</style>

<!-- Forced Layout Styles to override potential conflicts -->
<style>
@media (min-width: 768px) {
    .checkout-row-force {
        display: flex !important;
        flex-direction: row !important;
        flex-wrap: wrap !important;
    }
    .checkout-col-left {
        width: 58.333333% !important;
        flex: 0 0 auto !important;
    }
    .checkout-col-right {
        width: 41.666667% !important;
        flex: 0 0 auto !important;
    }
}
</style>

<div class="checkout-section">
    <div class="container py-4">
        <!-- Added checkout-row-force class -->
        <div class="row g-5 checkout-row-force">
            <!-- Left Column: Forms -->
            <div class="col-md-7 col-lg-7 checkout-col-left">
                <a href="index.php?controller=carrito&action=ver" class="back-link">
                    <i class="fas fa-arrow-left me-2"></i>Volver al Carrito
                </a>

                <div class="info-box">
                    <div class="info-label">Información del Cliente</div>
                    <div class="info-value">
                        <?php echo isset($_SESSION['usuario']['email']) ? htmlspecialchars($_SESSION['usuario']['email']) : 'Invitado'; ?>
                    </div>
                </div>

                <div class="form-container">
                    <h2 class="section-title">INFORMACIÓN DE ENVÍO</h2>
                    <h3 class="subsection-title">Dirección de envío</h3>
                    
                    <form id="checkoutForm">
                        <div class="mb-3">
                            <label class="form-label">Dirección *</label>
                            <input type="text" class="form-control" name="direccion" required placeholder="Calle, número, piso...">
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Pueblo/Ciudad *</label>
                                <input type="text" class="form-control" name="ciudad" required placeholder="Madrid">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Código Postal *</label>
                                <input type="text" class="form-control" name="cp" required placeholder="28001">
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Provincia *</label>
                                <input type="text" class="form-control" name="provincia" required placeholder="Madrid">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">País *</label>
                                <input type="text" class="form-control" name="pais" required placeholder="España" value="España">
                            </div>
                        </div>

                        <h2 class="section-title mt-5">MÉTODO DE PAGO</h2>
                        <h3 class="subsection-title">Tarjeta de Crédito</h3>

                        <div class="mb-3">
                            <label class="form-label">Número de tarjeta *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-credit-card text-muted"></i></span>
                                <input type="text" class="form-control border-start-0" id="cardNum" maxlength="19" required placeholder="0000 0000 0000 0000">
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Fecha caducidad *</label>
                                <input type="text" class="form-control" id="cardExpiry" maxlength="5" required placeholder="MM/YY">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">CVV *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control border-end-0" id="cardCvv" maxlength="3" required placeholder="123">
                                    <span class="input-group-text bg-light border-start-0"><i class="fas fa-lock text-muted"></i></span>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn-confirm" id="payBtn" onclick="console.log('BOTON CLICKEADO'); processPayment(event)">
                            <span id="btnText">CONFIRMAR PEDIDO</span>
                            <div id="btnSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status"></div>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column: Summary -->
            <div class="col-md-5 col-lg-5 checkout-col-right">
                <div class="summary-container">
                    <h2 class="summary-title">RESUMEN DEL PEDIDO</h2>
                    
                    <div class="summary-row">
                        <span>Subtotal del pedido</span>
                        <span class="summary-value" id="checkout-subtotal">0,00€</span>
                    </div>
                    
                    <div class="summary-row">
                        <span>Gastos de envío</span>
                        <span class="summary-value text-success">GRATIS</span>
                    </div>
                    
                    <div class="summary-row">
                        <span>IVA (10%)</span>
                        <span class="summary-value" id="checkout-iva">0,00€</span>
                    </div>
                    
                    <div class="summary-row total-row">
                        <span>TOTAL</span>
                        <span class="summary-value" id="checkout-total">0,00€</span>
                    </div>
                    <div class="summary-note">(IVA incluido)</div>

                    <h3 class="cart-title">Tu Carrito</h3>
                    <div id="checkout-items">
                        <!-- JS will populate items here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-body text-center p-5">
                <div class="success-animation mb-4">
                    <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                        <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                        <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                    </svg>
                </div>
                <h3 class="fw-bold mb-3">¡Pedido Confirmado!</h3>
                <p class="text-muted mb-4">Estamos preparando tus fideos favoritos. Recibirás un email con los detalles de tu pedido.</p>
                <button type="button" class="btn btn-primary px-5 rounded-pill" onclick="window.location.href='index.php'">
                    Volver al Inicio
                </button>
            </div>
        </div>
    </div>
</div>

<script src="public/js/checkout.js?v=<?php echo time(); ?>" 
        onerror="alert('ERROR CRÍTICO: No se pudo cargar public/js/checkout.js. Verifica que el archivo exista.')"
        onload="console.log('Script checkout.js cargado vía onload')"></script>