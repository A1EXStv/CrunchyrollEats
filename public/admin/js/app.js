const App = {
    state: {
        user: null,
        products: [],
        currentRoute: ''
    },

    async init() {
        try {
            const authData = await Api.checkAuth();
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

        const container = document.getElementById('main-content');
        const hash = window.location.hash || '#/';
        this.state.currentRoute = hash;

        this.updateSidebar(hash);

        // Simple Router
        if (hash === '#/' || hash === '#/dashboard') {
            container.innerHTML = Views.DashboardHome();
        }
        else if (hash === '#/products') {
            const products = await Api.getProducts();
            this.state.products = products;
            container.innerHTML = Views.ProductList(products);
        }
        else if (hash === '#/products/new') {
            container.innerHTML = Views.ProductForm();
            this.bindProductForm();
        }
        else if (hash.startsWith('#/products/edit/')) {
            const id = hash.split('/').pop();
            const product = this.state.products.find(p => p.id_producto == id) || await this.fetchProduct(id);
            if (product) {
                container.innerHTML = Views.ProductForm(product);
                this.bindProductForm();
            } else {
                window.location.hash = '#/products';
            }
        }
        else if (hash === '#/users') {
            try {
                const users = await Api.getUsers();
                container.innerHTML = Views.UserList(users);
            } catch (e) {
                alert('Error al cargar usuarios: ' + (e.error || e));
            }
        }
        else if (hash.startsWith('#/users/edit/')) {
            const id = hash.split('/').pop();
            const users = await Api.getUsers(); // Optimization: Fetch single if API supported, checking list for now
            const user = users.find(u => u.id == id);
            if (user) {
                container.innerHTML = Views.UserForm(user);
                this.bindUserForm();
            } else {
                window.location.hash = '#/users';
            }
        }
        else if (hash === '#/series') {
            const series = await Api.getSeries();
            container.innerHTML = Views.SeriesList(series);
        }
        else if (hash === '#/series/new') {
            container.innerHTML = Views.SeriesForm();
            this.bindSeriesForm();
        }
        else if (hash.startsWith('#/series/edit/')) {
            const id = hash.split('/').pop();
            // Fetch series list to find one, or could fetch single. simple: fetch all
            const series = await Api.getSeries();
            const serie = series.find(s => s.id_serie == id);
            if (serie) {
                container.innerHTML = Views.SeriesForm(serie);
                this.bindSeriesForm();
            } else {
                window.location.hash = '#/series';
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
                    await Api.saveProduct(data);
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
                    await Api.saveUser(data);
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
                    await Api.saveSerie(data);
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
                await Api.deleteSerie(id);
                this.router(); // Reload
            } catch (err) {
                alert('Error: ' + JSON.stringify(err));
            }
        }
    },

    async deleteProduct(id) {
        if (confirm('¿Estás seguro de eliminar este producto?')) {
            try {
                await Api.deleteProduct(id);
                this.router(); // Reload
            } catch (err) {
                alert('Error: ' + JSON.stringify(err));
            }
        }
    }
};

// Start
document.addEventListener('DOMContentLoaded', () => App.init());