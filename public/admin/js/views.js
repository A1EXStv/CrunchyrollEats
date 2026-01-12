const Views = {


    Shell: () => `
        <div class="layout-container fade-in">
            <aside class="sidebar glass-panel" style="border-radius: 0; padding-top: 2rem; margin: 0;">
                <div class="logo" style="font-size: 1.5rem;">Admin</div>
                <nav id="sidebarNav">
                    <a href="../../index.php" class="nav-link" style="border-left-color: #4cd137;">Volver a la Home</a>
                    <div style="border-bottom: 1px solid rgba(255,255,255,0.05); margin: 0.5rem 0;"></div>
                    <a href="#/" class="nav-link" data-link="dashboard">Dashboard</a>
                    <a href="#/products" class="nav-link" data-link="products">Productos</a>
                    <a href="#/series" class="nav-link" data-link="series">Series</a>
                    <a href="#/discounts" class="nav-link" data-link="discounts">Descuentos</a>
                    <a href="#/orders" class="nav-link" data-link="orders">Pedidos</a>
                    <a href="#/users" class="nav-link" data-link="users">Usuarios</a>
                    <a href="#/logs" class="nav-link" data-link="logs">Logs</a>
                    <a href="#" id="logoutBtn" class="nav-link" style="color: #ff6b6b; margin-top: 2rem;">Cerrar Sesión</a>
                </nav>
            </aside>
            <main id="main-content" class="main-content">
                <div id="view-dashboard" class="view-section active">
                    ${Views.DashboardHome()}
                </div>
                <div id="view-products-list" class="view-section"></div>
                <div id="view-products-form" class="view-section"></div>
                <div id="view-users-list" class="view-section"></div>
                <div id="view-users-form" class="view-section"></div>
                <div id="view-series-list" class="view-section"></div>
                <div id="view-series-form" class="view-section"></div>
                <div id="view-discounts-list" class="view-section"></div>
                <div id="view-discounts-form" class="view-section"></div>
                <div id="view-orders-list" class="view-section"></div>
                <div id="view-orders-detail" class="view-section"></div>
                <div id="view-logs-list" class="view-section"></div>
            </main>
        </div>
    `,

    DashboardHome: () => `
        <div class="glass-panel">
            <h1>Bienvenido al Panel de Administración</h1>
            <p>Selecciona una opción del menú para comenzar.</p>
        </div>
    `,

    ProductList: (products) => `
        <div class="glass-panel">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2>Gestión de Productos</h2>
                <button class="btn btn-primary" onclick="window.location.hash='#/products/new'">+ Nuevo Producto</button>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Categoría</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${products.map(p => `
                            <tr>
                                <td>${p.id_producto}</td>
                                <td>${p.nombre}</td>
                                <td>$${parseFloat(p.precio).toFixed(2)}</td>
                                <td>${p.id_categoria}</td>
                                <td>
                                    <button class="btn" style="background: rgba(255,255,255,0.1)" onclick="window.location.hash='#/products/edit/${p.id_producto}'">Editar</button>
                                    <button class="btn btn-danger" onclick="App.deleteProduct(${p.id_producto})">Eliminar</button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        </div>
    `,

    ProductForm: (product = {}) => `
        <div class="glass-panel" style="max-width: 800px; margin: 0 auto;">
            <h2 style="margin-bottom: 2rem;">${product.id_producto ? 'Editar' : 'Nuevo'} Producto</h2>
            <form id="productForm">
                <input type="hidden" name="id_producto" value="${product.id_producto || ''}">
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label>Nombre</label>
                        <input type="text" name="nombre" value="${product.nombre || ''}" required>
                    </div>
                    <div>
                        <label>Precio</label>
                        <input type="number" step="0.01" name="precio" value="${product.precio || ''}" required>
                    </div>
                </div>

                <label>Descripción</label>
                <textarea name="descripcion" rows="4">${product.descripcion || ''}</textarea>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label>ID Categoría</label>
                        <input type="number" name="id_categoria" value="${product.id_categoria || ''}" required>
                    </div>
                    <div>
                        <label>ID Serie (Opcional)</label>
                        <input type="number" name="id_serie" value="${product.id_serie || ''}">
                    </div>
                </div>

                <label>URL Imagen</label>
                <input type="text" name="imagen" value="${product.imagen || ''}">

                <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1rem;">
                    <button type="button" class="btn" onclick="window.location.hash='#/products'">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    `,

    UserList: (users) => `
        <div class="glass-panel">
            <h2>Gestión de Usuarios</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Rol</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${users.map(u => `
                            <tr>
                                <td>${u.id}</td>
                                <td>${u.nombre}</td>
                                <td>${u.email}</td>
                                <td>${u.telefono || '-'}</td>
                                <td>
                                    <span style="padding: 4px 8px; background: ${u.role === 'admin' ? '#f47521' : 'rgba(255,255,255,0.1)'}; border-radius: 4px; font-size: 0.8rem;">${u.role}</span>
                                </td>
                                <td>
                                    <button class="btn" style="background: rgba(255,255,255,0.1); padding: 4px 8px; font-size: 0.8rem;" onclick="window.location.hash='#/users/edit/${u.id}'">Editar</button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        </div>
    `,

    UserForm: (user = {}) => `
        <div class="glass-panel" style="max-width: 600px; margin: 0 auto;">
            <h2 style="margin-bottom: 2rem;">Editar Usuario</h2>
            <form id="userForm">
                <input type="hidden" name="id_usuario" value="${user.id || ''}">
                <div style="margin-bottom: 1rem;">
                    <label>Nombre</label>
                    <input type="text" value="${user.nombre || ''}" disabled style="opacity: 0.7">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label>Email</label>
                    <input type="text" value="${user.email || ''}" disabled style="opacity: 0.7">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label>Rol</label>
                    <select name="rol" style="width: 100%; padding: 0.8rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 8px;">
                        <option value="user" ${user.role === 'user' ? 'selected' : ''}>Usuario</option>
                        <option value="admin" ${user.role === 'admin' ? 'selected' : ''}>Administrador</option>
                    </select>
                </div>
                <div style="margin-bottom: 2rem;">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" value="${user.telefono || ''}">
                </div>
                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <button type="button" class="btn" onclick="window.location.hash='#/users'">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    `,

    SeriesList: (series) => `
        <div class="glass-panel">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2>Gestión de Series</h2>
                <button class="btn btn-primary" onclick="window.location.hash='#/series/new'">+ Nueva Serie</button>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${series.map(s => `
                            <tr>
                                <td>${s.id_serie}</td>
                                <td>${s.nombre}</td>
                                <td>
                                    <button class="btn" style="background: rgba(255,255,255,0.1)" onclick="window.location.hash='#/series/edit/${s.id_serie}'">Editar</button>
                                    <button class="btn btn-danger" onclick="App.deleteSerie(${s.id_serie})">Eliminar</button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        </div>
    `,

    SeriesForm: (serie = {}) => `
        <div class="glass-panel" style="max-width: 600px; margin: 0 auto;">
            <h2 style="margin-bottom: 2rem;">${serie.id_serie ? 'Editar' : 'Nueva'} Serie</h2>
            <form id="serieForm">
                <input type="hidden" name="id_serie" value="${serie.id_serie || ''}">
                <div style="margin-bottom: 2rem;">
                    <label>Nombre</label>
                    <input type="text" name="nombre" value="${serie.nombre || ''}" required>
                </div>
                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <button type="button" class="btn" onclick="window.location.hash='#/series'">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    `,

    DescuentoList: (discounts) => `
        <div class="glass-panel">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2>Gestión de Descuentos</h2>
                <button class="btn btn-primary" onclick="window.location.hash='#/discounts/new'">+ Nuevo Descuento</button>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Valor</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${discounts.map(d => `
                            <tr>
                                <td>${d.nombre}</td>
                                <td>${d.tipo === 'porcentaje' ? 'Porcentaje' : 'Fijo'}</td>
                                <td>${d.tipo === 'porcentaje' ? d.valor + '%' : d.valor + '€'}</td>
                                <td>
                                    <span style="color: ${parseInt(d.activo) ? '#4cd137' : '#ff7675'}">
                                        ${parseInt(d.activo) ? 'Activo' : 'Inactivo'}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn" style="background: rgba(255,255,255,0.1)" onclick="window.location.hash='#/discounts/edit/${d.id_descuento}'">Editar</button>
                                    <button class="btn btn-danger" onclick="App.deleteDiscount(${d.id_descuento})">Eliminar</button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        </div>
    `,

    DescuentoForm: (discount = {}, allProducts = []) => {
        // Parse current dates if editing
        let endDate = '';
        let endTime = '';
        if (discount.fecha_fin) {
            const parts = discount.fecha_fin.split(' ');
            endDate = parts[0];
            endTime = parts[1] ? parts[1].substring(0, 5) : '23:59';
        }

        return `
        <div class="glass-panel" style="max-width: 800px; margin: 0 auto;">
            <h2 style="margin-bottom: 2rem;">${discount.id_descuento ? 'Editar' : 'Nuevo'} Descuento</h2>
            <form id="discountForm">
                <input type="hidden" name="id_descuento" value="${discount.id_descuento || ''}">
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label>Nombre</label>
                        <input type="text" name="nombre" value="${discount.nombre || ''}" required placeholder="Ej: Especial Verano">
                    </div>
                    <div>
                        <label>Estado</label>
                        <select name="activo">
                            <option value="1" ${parseInt(discount.activo) !== 0 ? 'selected' : ''}>Activo</option>
                            <option value="0" ${parseInt(discount.activo) === 0 ? 'selected' : ''}>Inactivo</option>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label>Tipo de Descuento</label>
                        <select name="tipo">
                            <option value="porcentaje" ${discount.tipo === 'porcentaje' ? 'selected' : ''}>Porcentaje (%)</option>
                            <option value="fijo" ${discount.tipo === 'fijo' ? 'selected' : ''}>Monto Fijo (€)</option>
                        </select>
                    </div>
                    <div>
                        <label>Valor</label>
                        <input type="number" step="0.01" name="valor" value="${discount.valor || ''}" required>
                    </div>
                </div>

                <div style="background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem; border: 1px solid rgba(255,255,255,0.05);">
                    <label style="display: block; margin-bottom: 1rem; font-weight: 600; color: #f47521;">Vigencia del Descuento</label>
                    <p style="font-size: 0.8rem; color: #888; margin-bottom: 1rem;">La fecha de inicio se establecerá automáticamente como el momento de creación/edición.</p>
                    
                    <div style="display: grid; grid-template-columns: 2fr 1.5fr; gap: 1.5rem;">
                        <div>
                            <label>Fecha Límite (Dejar vacío para indefinido)</label>
                            <input type="date" name="fecha_fin_date" value="${endDate}">
                        </div>
                        <div>
                            <label>Hora Límite</label>
                            <input type="time" name="fecha_fin_time" value="${endTime}">
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 2rem;">
                    <label style="margin-bottom: 1rem; display: block; font-weight: 600;">Seleccionar Productos Aplicables:</label>
                    <div class="product-selection-list" style="max-height: 250px; overflow-y: auto; background: rgba(0,0,0,0.2); padding: 1rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 0.5rem;">
                            ${allProducts.map(p => `
                                <div class="product-checkbox-item" style="display: flex; align-items: center; gap: 0.8rem; padding: 0.5rem; border-radius: 6px; transition: background 0.2s;">
                                    <input type="checkbox" name="productos[]" value="${p.id_producto}" id="prod_${p.id_producto}" 
                                        style="width: auto; margin: 0;"
                                        ${discount.productos && discount.productos.includes(parseInt(p.id_producto)) ? 'checked' : ''}>
                                    <label for="prod_${p.id_producto}" style="margin: 0; cursor: pointer; font-size: 0.9rem; flex: 1;">${p.nombre}</label>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                    <style>
                        .product-checkbox-item:hover { background: rgba(255,255,255,0.05); }

                    </style>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <button type="button" class="btn" style="background: rgba(255,255,255,0.05)" onclick="window.location.hash='#/discounts'">Cancelar</button>
                    <button type="submit" class="btn btn-primary" style="padding-left: 3rem; padding-right: 3rem;">Guardar Descuento</button>
                </div>
            </form>
        </div>
        `;
    },

    LogList: (logs) => `
            
            <h2>Registros de Actividad (Logs)</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${logs.map(log => `
                            <tr>
                                <td>${log.fecha}</td>
                                <td>${log.nombre_usuario || 'Anónimo'} (${log.email_usuario || '-'})</td>
                                <td>${log.accion}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        </div >
    `,

    PedidoList: (pedidos) => `
        <div class="glass-panel">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2>Gestión de Pedidos</h2>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${pedidos.map(p => `
                            <tr>
                                <td>#${p.id_pedido}</td>
                                <td>${p.usuario_nombre}<br><small style="opacity: 0.7">${p.usuario_email}</small></td>
                                <td>${new Date(p.fecha_pedido).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })}</td>
                                <td style="font-weight: 600">€${parseFloat(p.total).toFixed(2)}</td>
                                <td>
                                    <span class="badge ${p.estado === 'entregado' ? 'badge-success' :
            p.estado === 'pendiente' ? 'badge-warning' :
                p.estado === 'preparando' ? 'badge-info' :
                    'badge-danger'
        }">${p.estado.charAt(0).toUpperCase() + p.estado.slice(1)}</span>
                                </td>
                                <td>
                                    <button class="btn" style="background: rgba(255,255,255,0.1)" onclick="window.location.hash='#/orders/view/${p.id_pedido}'">Ver Detalles</button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        </div>
    `,

    PedidoDetail: (pedido) => `
        <div class="glass-panel" style="max-width: 1000px; margin: 0 auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2>Pedido #${pedido.id_pedido}</h2>
                <button class="btn" onclick="window.location.hash='#/orders'" style="background: rgba(255,255,255,0.1)">← Volver</button>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                <div>
                    <h3 style="margin-bottom: 1rem; font-size: 1.1rem;">Información del Cliente</h3>
                    <p><strong>Nombre:</strong> ${pedido.usuario_nombre}</p>
                    <p><strong>Email:</strong> ${pedido.usuario_email}</p>
                    <p><strong>Fecha del pedido:</strong> ${new Date(pedido.fecha_pedido).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })}</p>
                </div>

                <div>
                    <h3 style="margin-bottom: 1rem; font-size: 1.1rem;">Dirección de Envío</h3>
                    <p>${pedido.direccion || 'N/A'}</p>
                    <p>${pedido.ciudad || ''} ${pedido.codigo_postal || ''}</p>
                    <p>${pedido.provincia || ''}, ${pedido.pais || ''}</p>
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <h3 style="margin-bottom: 1rem; font-size: 1.1rem;">Estado del Pedido</h3>
                <div class="status-buttons-container" style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                    ${['pendiente', 'preparando', 'entregado', 'cancelado'].map(status => {
        let color = 'rgba(255,255,255,0.1)';
        if (status === 'entregado') color = '#4cd137';
        else if (status === 'pendiente') color = '#fbc531';
        else if (status === 'preparando') color = '#00a8ff';
        else if (status === 'cancelado') color = '#e84118';

        const isActive = pedido.estado === status;

        return `
                        <button type="button" 
                            class="btn-status ${isActive ? 'active' : ''}" 
                            data-status="${status}"
                            style="
                                padding: 0.6rem 1.2rem;
                                border-radius: 20px;
                                border: 1px solid ${isActive ? color : 'rgba(255,255,255,0.1)'};
                                background: ${isActive ? color : 'transparent'};
                                color: ${isActive && (status === 'pendiente') ? 'black' : 'white'};
                                cursor: pointer;
                                transition: all 0.3s ease;
                                opacity: ${isActive ? '1' : '0.6'};
                                font-weight: ${isActive ? '600' : 'normal'};
                            "
                            onmouseover="this.style.opacity='1'; this.style.transform='scale(1.05)'"
                            onmouseout="if(!this.classList.contains('active')) { this.style.opacity='0.6'; } this.style.transform='scale(1)'"
                        >
                            ${status.charAt(0).toUpperCase() + status.slice(1)}
                        </button>
                    `}).join('')}
                </div>
            </div>

            <div>
                <h3 style="margin-bottom: 1rem; font-size: 1.1rem;">Productos</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${(pedido.detalles || []).map(d => `
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 1rem;">
                                            ${d.producto_imagen ? `<img src="../../${d.producto_imagen}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;" onerror="this.style.display='none'">` : ''}
                                            <span>${d.producto_nombre}</span>
                                        </div>
                                    </td>
                                    <td>${d.cantidad}</td>
                                    <td>€${parseFloat(d.precio_unitario).toFixed(2)}</td>
                                    <td style="font-weight: 600">€${(parseFloat(d.precio_unitario) * parseInt(d.cantidad)).toFixed(2)}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                        <tfoot>
                            <tr style="font-weight: bold; font-size: 1.1rem;">
                                <td colspan="3" style="text-align: right;">Total:</td>
                                <td>€${parseFloat(pedido.total).toFixed(2)}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    `
};
