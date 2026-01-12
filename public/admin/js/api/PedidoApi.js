class PedidoApi extends BaseApi {
    async getAll() {
        return this.request('getPedidos');
    }

    async get(id) {
        return this.request(`getPedido&id=${id}`);
    }

    async updateEstado(id_pedido, estado) {
        console.log('PedidoApi.updateEstado called with:', { id_pedido, estado });
        console.log('typeof id_pedido:', typeof id_pedido);
        console.log('typeof estado:', typeof estado);

        const body = {
            id_pedido: parseInt(id_pedido),
            estado: String(estado)
        };
        console.log('Sending body:', JSON.stringify(body));

        return this.request('updatePedidoEstado', 'POST', body);
    }
}
