class LogApi extends BaseApi {
    async getAll() {
        return this.request('getLogs');
    }
}
