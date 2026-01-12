class DiscountApi extends BaseApi {
    getAll() {
        return this.request('getDescuentos');
    }

    get(id) {
        return this.request(`getDescuento&id=${id}`);
    }

    save(discount) {
        return this.request('saveDescuento', 'POST', discount);
    }

    delete(id) {
        return this.request('deleteDescuento', 'POST', { id });
    }

    toggle(id, activo) {
        return this.request('toggleDescuento', 'POST', { id, activo });
    }
}
