const App = {
    state: {
        user: null,
        products: [],
        currentRoute: '',
        api: {
            auth: new AuthApi(),
            product: new ProductApi(),
            user: new UserApi(),
            serie: new SerieApi(),
            serie: new SerieApi(),
            discount: new DiscountApi(),
            log: new LogApi(),
            pedido: new PedidoApi()
        }
    },

    async init() {
        try {
            const authData = await this.state.api.auth.checkAuth();
            if (authData.authenticated) {
                this.state.user = { role: authData.role }; // Minimal user data
                this.router();
            } else {
                window.location.href = '../../index.php?controller=login&action=index';
            }
        } catch (e) {
            window.location.href = '../../index.php?controller=login&action=index';
        }

        window.addEventListener('hashchange', () => this.router());
    },

    async router() {
        if (!this.state.user) {
            // Redirect to main login if not authenticated
            window.location.href = '../../index.php?controller=login&action=index';
            return;
        }

        const app = document.getElementById('app');
        // Check if Shell exists, if not render it
        if (!document.getElementById('main-content')) {
            app.innerHTML = Views.Shell();
            this.bindEvents();
        }

        const hash = window.location.hash || '#/';
        this.state.currentRoute = hash;

        this.updateSidebar(hash);

        // Hide all views
        document.querySelectorAll('.view-section').forEach(el => el.classList.remove('active'));

        // Helper to show view
        const show = (id) => {
            const el = document.getElementById(id);
            if (el) el.classList.add('active');
            return el;
        };

        // Simple Router
        if (hash === '#/' || hash === '#/dashboard') {
            show('view-dashboard');
        }
        else if (hash === '#/products') {
            const container = show('view-products-list');
            // Only fetch if needed or always refresh? Let's refresh for now to keep data sync
            const products = await this.state.api.product.getAll();
            this.state.products = products;
            container.innerHTML = Views.ProductList(products);
        }
        else if (hash === '#/products/new') {
            const container = show('view-products-form');
            container.innerHTML = Views.ProductForm();
            this.bindProductForm();
        }
        else if (hash.startsWith('#/products/edit/')) {
            const id = hash.split('/').pop();
            const product = this.state.products.find(p => p.id_producto == id) || await this.fetchProduct(id);
            if (product) {
                const container = show('view-products-form');
                container.innerHTML = Views.ProductForm(product);
                this.bindProductForm();
            } else {
                window.location.hash = '#/products';
            }
        }
        else if (hash === '#/users') {
            try {
                const container = show('view-users-list');
                const users = await this.state.api.user.getAll();
                container.innerHTML = Views.UserList(users);
            } catch (e) {
                alert('Error al cargar usuarios: ' + (e.error || e));
            }
        }
        else if (hash.startsWith('#/users/edit/')) {
            const id = hash.split('/').pop();
            const users = await this.state.api.user.getAll(); // Optimization: Fetch single if API supported, checking list for now
            const user = users.find(u => u.id == id);
            if (user) {
                const container = show('view-users-form');
                container.innerHTML = Views.UserForm(user);
                this.bindUserForm();
            } else {
                window.location.hash = '#/users';
            }
        }
        else if (hash === '#/series') {
            const container = show('view-series-list');
            const series = await this.state.api.serie.getAll();
            container.innerHTML = Views.SeriesList(series);
        }
        else if (hash === '#/series/new') {
            const container = show('view-series-form');
            container.innerHTML = Views.SeriesForm();
            this.bindSeriesForm();
        }
        else if (hash.startsWith('#/series/edit/')) {
            const id = hash.split('/').pop();
            // Fetch series list to find one, or could fetch single. simple: fetch all
            const series = await this.state.api.serie.getAll();
            const serie = series.find(s => s.id_serie == id);
            if (serie) {
                const container = show('view-series-form');
                container.innerHTML = Views.SeriesForm(serie);
                this.bindSeriesForm();
            } else {
                window.location.hash = '#/series';
            }
        }
        else if (hash === '#/discounts') {
            const container = show('view-discounts-list');
            const discounts = await this.state.api.discount.getAll();
            container.innerHTML = Views.DescuentoList(discounts);
        }
        else if (hash === '#/discounts/new') {
            const container = show('view-discounts-form');
            const allProducts = await this.state.api.product.getAll();
            container.innerHTML = Views.DescuentoForm({}, allProducts);
            this.bindDiscountForm();
        }
        else if (hash.startsWith('#/discounts/edit/')) {
            const id = hash.split('/').pop();
            const discount = await this.state.api.discount.get(id);
            const allProducts = await this.state.api.product.getAll();
            if (discount) {
                const container = show('view-discounts-form');
                container.innerHTML = Views.DescuentoForm(discount, allProducts);
                this.bindDiscountForm();
            } else {
                window.location.hash = '#/discounts';
            }
        }
        else if (hash === '#/orders') {
            const container = show('view-orders-list');
            try {
                const orders = await this.state.api.pedido.getAll();
                container.innerHTML = Views.PedidoList(orders);
            } catch (e) {
                container.innerHTML = '<p>Error al cargar pedidos: ' + e.message + '</p>';
            }
        }
        else if (hash.startsWith('#/orders/view/')) {
            const id = hash.split('/').pop();
            try {
                const pedido = await this.state.api.pedido.get(id);
                if (pedido) {
                    const container = show('view-orders-detail');
                    container.innerHTML = Views.PedidoDetail(pedido);
                    this.bindPedidoForm();
                } else {
                    window.location.hash = '#/orders';
                }
            } catch (e) {
                alert('Error al cargar pedido: ' + e.message);
                window.location.hash = '#/orders';
            }
        }
        else if (hash === '#/logs') {
            const container = show('view-logs-list');
            try {
                const logs = await this.state.api.log.getAll();
                container.innerHTML = Views.LogList(logs);
            } catch (e) {
                container.innerHTML = '<p>Error al cargar logs: ' + e.message + '</p>';
            }
        }
    },

    updateSidebar(hash) {
        const links = document.querySelectorAll('#sidebarNav .nav-link');
        links.forEach(link => {
            link.classList.remove('toggle-active');
            const linkHash = link.getAttribute('href');
            // Check for exact match or if current hash starts with link hash (for sub-routes like /products/new)
            // But be careful with root #/ matching everything.
            if (linkHash === '#/' && (hash === '#/' || hash === '#/dashboard')) {
                link.classList.add('toggle-active');
            } else if (linkHash !== '#/' && hash.startsWith(linkHash)) {
                link.classList.add('toggle-active');
            }
        });
    },

    async fetchProduct(id) {
        try {
            // If not in state (reload), fetch it
            // We need a getProduct endpoint or find in list
            // Just reloading list for simplicity or create getProduct endpoint
            // Implementation choice: We can fetch list again or fetch single. 
            // We added getProducto endpoint in ApiController!
            const response = await fetch(`../../index.php?controller=Api&action=getProducto&id=${id}`).then(r => r.json());
            return response;
        } catch (e) {
            return null;
        }
    },



    bindEvents() {
        const logoutBtn = document.getElementById('logoutBtn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', async (e) => {
                e.preventDefault();
                // Redirect to main PHP logout
                window.location.href = '../../index.php?controller=login&action=logout';
            });
        }
    },

    bindProductForm() {
        const form = document.getElementById('productForm');
        if (form) {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());
                if (!data.id_serie) delete data.id_serie;

                try {
                    await this.state.api.product.save(data);
                    window.location.hash = '#/products';
                } catch (err) {
                    alert('Error al guardar: ' + JSON.stringify(err));
                }
            });
        }
    },

    bindUserForm() {
        const form = document.getElementById('userForm');
        if (form) {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());

                try {
                    await this.state.api.user.save(data);
                    window.location.hash = '#/users';
                } catch (err) {
                    alert('Error al guardar usuario: ' + JSON.stringify(err));
                }
            });
        }
    },

    bindSeriesForm() {
        const form = document.getElementById('serieForm');
        if (form) {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());

                try {
                    await this.state.api.serie.save(data);
                    window.location.hash = '#/series';
                } catch (err) {
                    alert('Error al guardar serie: ' + JSON.stringify(err));
                }
            });
        }
    },

    async deleteSerie(id) {
        if (confirm('¿Estás seguro de eliminar esta serie?')) {
            try {
                await this.state.api.serie.delete(id);
                this.router(); // Reload
            } catch (err) {
                alert('Error: ' + JSON.stringify(err));
            }
        }
    },

    async deleteProduct(id) {
        if (confirm('¿Estás seguro de eliminar este producto?')) {
            try {
                await this.state.api.product.delete(id);
                this.router(); // Reload
            } catch (err) {
                alert('Error: ' + JSON.stringify(err));
            }
        }
    },

    bindDiscountForm() {
        const form = document.getElementById('discountForm');
        if (form) {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());

                // Get all checked products
                const productCheckboxes = form.querySelectorAll('input[name="productos[]"]:checked');
                data.productos = Array.from(productCheckboxes).map(cb => cb.value);

                // Handle split date/time
                if (data.fecha_fin_date) {
                    const time = data.fecha_fin_time || '23:59';
                    data.fecha_fin = `${data.fecha_fin_date} ${time}:00`;
                } else {
                    data.fecha_fin = null;
                }

                try {
                    await this.state.api.discount.save(data);
                    window.location.hash = '#/discounts';
                } catch (err) {
                    alert('Error al guardar descuento: ' + JSON.stringify(err));
                }
            });
        }
    },

    bindPedidoForm() {
        const buttons = document.querySelectorAll('.btn-status');

        buttons.forEach(btn => {
            btn.addEventListener('click', async (e) => {
                const nuevoEstado = btn.dataset.status;
                const hash = window.location.hash;
                const id_pedido = hash.split('/').pop();

                if (!id_pedido || !nuevoEstado) return;

                // Do not re-update if same status (optional, maybe user wants to re-trigger?)
                if (btn.classList.contains('active')) return;

                if (!confirm(`¿Cambiar estado del pedido a "${nuevoEstado}"?`)) return;

                // Visual feedback
                const originalText = btn.innerHTML;
                btn.innerHTML = '...';
                buttons.forEach(b => b.disabled = true);

                try {
                    const result = await this.state.api.pedido.updateEstado(id_pedido, nuevoEstado);

                    if (result && result.status === 'updated') {
                        // Reload to show updates
                        await this.router();
                    } else {
                        alert('Error al actualizar');
                        btn.innerHTML = originalText;
                        buttons.forEach(b => b.disabled = false);
                    }
                } catch (err) {
                    console.error('Error:', err);
                    alert('Error: ' + (err.error || err.message || 'Desconocido'));
                    btn.innerHTML = originalText;
                    buttons.forEach(b => b.disabled = false);
                }
            });
        });
    },

    async deleteDiscount(id) {
        if (confirm('¿Estás seguro de eliminar este descuento?')) {
            try {
                await this.state.api.discount.delete(id);
                this.router(); // Reload
            } catch (err) {
                alert('Error al eliminar: ' + JSON.stringify(err));
            }
        }
    }
};

// Start
document.addEventListener('DOMContentLoaded', () => App.init());
