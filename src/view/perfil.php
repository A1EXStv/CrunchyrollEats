<div class="profile-container py-5">
    <div class="container">
        <div class="row g-4">
            <!-- User Info Section -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-dark text-white p-4 border-0">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-circle">
                                <?= strtoupper(substr($_SESSION['usuario']['nombre'], 0, 1)) ?>
                            </div>
                            <div>
                                <h4 class="mb-0"><?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></h4>
                                <span class="badge bg-primary"><?= ucfirst(htmlspecialchars($_SESSION['usuario']['rol'])) ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <?php if (isset($_SESSION['success_perfil'])): ?>
                            <div class="alert alert-success border-0 rounded-3 small">
                                <?= $_SESSION['success_perfil']; unset($_SESSION['success_perfil']); ?>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['error_perfil'])): ?>
                            <div class="alert alert-danger border-0 rounded-3 small">
                                <?= $_SESSION['error_perfil']; unset($_SESSION['error_perfil']); ?>
                            </div>
                        <?php endif; ?>

                        <form action="index.php?controller=perfil&action=actualizar" method="POST">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Nombre Completo</label>
                                <input type="text" name="nombre" class="form-control rounded-3" value="<?= htmlspecialchars($_SESSION['usuario']['nombre']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Correo Electrónico</label>
                                <input type="email" name="email" class="form-control rounded-3 bg-light" value="<?= htmlspecialchars($_SESSION['usuario']['email']) ?>" readonly title="El correo electrónico no se puede modificar">
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted">Teléfono</label>
                                <input type="text" name="telefono" class="form-control rounded-3" value="<?= htmlspecialchars($_SESSION['usuario']['telefono'] ?? '') ?>">
                            </div>
                            <button type="submit" class="btn btn-dark w-100 rounded-pill py-2 fw-bold">
                                Guardar Cambios
                            </button>
                        </form>

                        <hr class="my-4">

                        <h5 class="fw-bold mb-3">Cambiar Contraseña</h5>
                        <form action="index.php?controller=perfil&action=cambiarPassword" method="POST">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Contraseña Actual</label>
                                <input type="password" name="pass_actual" class="form-control rounded-3" placeholder="Contraseña actual" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Nueva Contraseña</label>
                                <input type="password" name="pass_nueva" class="form-control rounded-3" placeholder="Nueva contraseña" required>
                            </div>
                            <button type="submit" class="btn btn-outline-dark w-100 rounded-pill py-2 fw-bold small">
                                Actualizar Contraseña
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Orders History Section -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white p-4 border-0">
                        <h4 class="mb-0 fw-bold">Mis Pedidos</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if (empty($historial)): ?>
                            <div class="text-center py-5">
                                <i class="fas fa-shopping-bag fa-3x text-light mb-3"></i>
                                <p class="text-muted">Aún no has realizado ningún pedido.</p>
                                <a href="index.php?controller=carta&action=index" class="btn btn-outline-dark rounded-pill px-4">Ir a la carta</a>
                            </div>
                        <?php else: ?>
                            <div class="accordion accordion-flush" id="ordersAccordion">
                                <?php foreach ($historial as $index => $item): 
                                    $pedido = $item['pedido'];
                                    $detalles = $item['detalles'];
                                    $collapseId = "collapseOrder" . $pedido->getId();
                                ?>
                                    <div class="accordion-item border-0 mb-3 rounded-4 overflow-hidden shadow-sm">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed p-4" type="button" data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>">
                                                <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                                    <div>
                                                        <span class="d-block small text-muted">Pedido #<?= $pedido->getId() ?></span>
                                                        <span class="fw-bold fs-5"><?= number_format($pedido->getTotal(), 2) ?> €</span>
                                                    </div>
                                                    <div class="text-end">
                                                        <span class="d-block small text-muted"><?= date('d/m/Y', strtotime($pedido->getFecha())) ?></span>
                                                        <span class="badge rounded-pill bg-<?= $pedido->getEstado() === 'pendiente' ? 'warning' : 'success' ?>-subtle text-<?= $pedido->getEstado() === 'pendiente' ? 'warning' : 'success' ?> px-3">
                                                            <?= ucfirst($pedido->getEstado()) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="<?= $collapseId ?>" class="accordion-collapse collapse" data-bs-parent="#ordersAccordion">
                                            <div class="accordion-body p-4 bg-light-subtle">
                                                <div class="list-group list-group-flush rounded-3 overflow-hidden border">
                                                    <?php foreach ($detalles as $detalle): ?>
                                                        <div class="list-group-item d-flex align-items-center gap-3 p-3">
                                                            <div class="order-item-img">
                                                                <img src="<?= $detalle['producto_imagen'] ?>" alt="<?= htmlspecialchars($detalle['producto_nombre']) ?>" width="50">
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-0 fw-bold"><?= htmlspecialchars($detalle['producto_nombre']) ?></h6>
                                                                <span class="small text-muted"><?= $detalle['cantidad'] ?> unidad<?= $detalle['cantidad'] > 1 ? 'es' : '' ?> x <?= number_format($detalle['precio_unitario'], 2) ?> €</span>
                                                            </div>
                                                            <div class="text-end fw-bold">
                                                                <?= number_format($detalle['cantidad'] * $detalle['precio_unitario'], 2) ?> €
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                                <div class="mt-3 text-end">
                                                    <span class="text-muted small">Total del Pedido:</span>
                                                    <span class="h5 mb-0 fw-bold ms-2"><?= number_format($pedido->getTotal(), 2) ?> €</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-container {
    background-color: #f8f9fa;
    min-height: calc(100vh - 200px);
}

.avatar-circle {
    width: 60px;
    height: 60px;
    background-color: #ff640b;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: bold;
    box-shadow: 0 4px 8px rgba(255,100,11,0.2);
}

.accordion-button:not(.collapsed) {
    background-color: #fff;
    color: inherit;
    box-shadow: none;
}

.accordion-button:focus {
    box-shadow: none;
    border-color: rgba(0,0,0,.125);
}

.order-item-img {
    width: 50px;
    height: 50px;
    background: #f0f0f0;
    border-radius: 8px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}

.order-item-img img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.bg-warning-subtle { background-color: #fff3cd !important; }
.bg-success-subtle { background-color: #d1e7dd !important; }
.text-warning { color: #856404 !important; }
.text-success { color: #0f5132 !important; }
</style>
