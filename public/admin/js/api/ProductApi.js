class ProductApi extends BaseApi {
    getAll() {
        // Mapped from getProducts
        return this.request('getProductos');
    }

    save(product) {
        // Mapped from saveProduct
        return this.request('saveProducto', 'POST', product);
    }

    delete(id) {
        // Mapped from deleteProduct
        return this.request('deleteProducto', 'POST', { id });
    }
}
