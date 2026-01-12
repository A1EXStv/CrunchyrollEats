class UserApi extends BaseApi {
    getAll() {
        // Mapped from getUsers
        return this.request('getUsers');
    }

    save(user) {
        // Mapped from saveUser
        return this.request('saveUser', 'POST', user);
    }
}
