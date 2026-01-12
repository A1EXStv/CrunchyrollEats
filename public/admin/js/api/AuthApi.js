class AuthApi extends BaseApi {
    checkAuth() {
        return this.request('checkAuth');
    }

    logout() {
        return this.request('logout');
    }
}
