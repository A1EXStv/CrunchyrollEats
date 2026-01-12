const API_BASE = '../../index.php?controller=Api';

class BaseApi {
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
    }
}
