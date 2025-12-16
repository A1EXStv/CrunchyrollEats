const Views = {


    Shell: () => `
        <div class="layout-container fade-in">
            <aside class="sidebar glass-panel" style="border-radius: 0; padding-top: 2rem; margin: 0;">
                <div class="logo" style="font-size: 1.5rem;">Admin</div>
                <nav id="sidebarNav">
                    <a href="#/" class="nav-link" data-link="dashboard">Dashboard</a>
                    <a href="#/products" class="nav-link" data-link="products">Productos</a>
                    <a href="#/series" class="nav-link" data-link="series">Series</a>
                    <a href="#/users" class="nav-link" data-link="users">Usuarios</a>
                    <a href="#" id="logoutBtn" class="nav-link" style="color: #ff6b6b; margin-top: 2rem;">Cerrar Sesión</a>
                </nav>
            </aside>
            <main id="main-content" class="main-content">
                <!-- Content injected here -->
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
                            <th>Telefono</th>
                            <th>Rol</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${users.map(u => `
                            <tr>
                                <td>${u.id}</td>
                                <td>${u.nombre}</td>
                                <td>${u.email}</td>
                                <td>${u.telefono}</td>
                                <td>
                                    <span style="padding: 4px 8px; background: ${u.role === 'admin' ? 'var(--primary-color)' : 'rgba(255,255,255,0.1)'}; border-radius: 4px; font-size: 0.8rem;">${u.role}</span>
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
    `
};