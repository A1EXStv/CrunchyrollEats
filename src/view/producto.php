<?php
// src/view/producto.php
$precioOriginal = (float)$producto['precio'];
$precioFinal = (float)$producto['precio_final'];
$tieneDescuento = $precioFinal < $precioOriginal;

$productoParaCarrito = $producto;
$jsonProducto = htmlspecialchars(json_encode($productoParaCarrito), ENT_QUOTES, 'UTF-8');
?>

<section class="product-detail-section py-4">
    <div class="container">
        <!-- Breadcrumb minimalista -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb-custom">
                <li><a href="index.php">Tienda</a></li>
                <li>/</li>
                <li><a href="index.php?controller=carta&action=index">Carta</a></li>
                <li>/</li>
                <li class="active"><?= htmlspecialchars($producto['nombre']) ?></li>
            </ol>
        </nav>

        <div class="row g-5 align-items-start">
            <!-- Product Images -->
            <div class="col-lg-5">
                <div class="product-image-main mb-3">
                    <img src="<?= $producto['imagen'] ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>" class="img-fluid rounded">
                </div>
                <!-- Thumbnail -->
                <div class="product-thumbnail">
                    <img src="<?= $producto['imagen'] ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>" class="img-fluid rounded">
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-7">
                <div class="product-info-content">
                    <h1 class="product-title mb-4"><?= htmlspecialchars($producto['nombre']) ?></h1>
                    
                    <!-- Precio -->
                    <div class="price-section mb-4">
                        <?php if ($tieneDescuento): ?>
                            <span class="price-final"><?= number_format($precioFinal, 2) ?>€</span>
                            <span class="price-original"><?= number_format($precioOriginal, 2) ?>€</span>
                        <?php else: ?>
                            <span class="price-final"><?= number_format($precioOriginal, 2) ?>€</span>
                        <?php endif; ?>
                    </div>

                    <!-- Cantidad y botón -->
                    <div class="action-section mb-5">
                        <div class="quantity-selector">
                            <button class="qty-btn" onclick="decrementQty()">−</button>
                            <input type="number" id="quantity" value="1" min="1" readonly>
                            <button class="qty-btn" onclick="incrementQty()">+</button>
                        </div>
                        <?php 
                        $onclickAction = isset($_SESSION['usuario']) 
                            ? "addToCartWithQty($jsonProducto)" 
                            : "window.location.href='index.php?controller=login&action=index'"; 
                        ?>
                        <button class="btn-add-cart" onclick="<?= $onclickAction ?>">
                            Añadir al carrito
                        </button>
                    </div>

                    <!-- Descripción -->
                    <div class="description-section">
                        <h2 class="section-title">Descripción</h2>
                        <div class="description-content">
                            <?= nl2br(htmlspecialchars($producto['descripcion'])) ?>
                        </div>
                    </div>

                    <!-- Características -->
                    <?php if (!empty($producto['caracteristicas'])): ?>
                    <div class="features-section mt-4">
                        <h3 class="section-subtitle">Características del plato:</h3>
                        <div class="features-content">
                            <?= nl2br(htmlspecialchars($producto['caracteristicas'])) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

</section>