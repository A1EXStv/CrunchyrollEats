class SerieApi extends BaseApi {
    getAll() {
        // Mapped from getSeries
        return this.request('getSeries');
    }

    save(serie) {
        // Mapped from saveSerie
        return this.request('saveSerie', 'POST', serie);
    }

    delete(id) {
        // Mapped from deleteSerie
        return this.request('deleteSerie', 'POST', { id });
    }
}
