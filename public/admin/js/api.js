const API_BASE = '../../index.php?controller=Api';

const Api = {
    async request(action, method = 'GET', body = null) {
        const headers = {};
        const options = { method, headers, credentials: 'include' };

        if (body) {
            headers['Content-Type'] = 'application/json';
            options.body = JSON.stringify(body);
        }

        try {
            const response = await fetch(`${API_BASE}&action=${action}`, options);
            const contentType = response.headers.get("content-type");
            if (contentType && contentType.indexOf("application/json") !== -1) {
                const data = await response.json();
                if (!response.ok) throw data;
                return data;
            } else {
                throw { error: "Invalid response format (not JSON)" };
            }
        } catch (err) {
            console.error('API Error:', err);
            throw err;
        }
    },



    checkAuth() {
        return this.request('checkAuth');
    },

    logout() {
        return this.request('logout');
    },



    getProducts() {
        return this.request('getProductos');
    },

    saveProduct(product) {
        return this.request('saveProducto', 'POST', product);
    },

    deleteProduct(id) {
        return this.request('deleteProducto', 'POST', { id });
    },




    saveUser(user) {
        return this.request('saveUser', 'POST', user);
    },
    getUsers() {
            return this.request('getUsers');
    },


    getSeries() {
        return this.request('getSeries');
    },

    saveSerie(serie) {
        return this.request('saveSerie', 'POST', serie);
    },

    deleteSerie(id) {
        return this.request('deleteSerie', 'POST', { id });
    },

    
};