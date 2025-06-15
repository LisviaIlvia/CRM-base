import axios from 'axios';

class axiosService {
	
	constructor() {
		this.baseUrl = import.meta.env.VITE_APP_URL;
		this.headers = { 'Content-Type': 'application/json' };
		this.instance = axios.create({
			baseURL: this.baseUrl,
			validateStatus: function (status) {
				return status < 500;
			},
		});

		this.instance.interceptors.response.use(
			(response) => {
				switch(response.status) {
					case 422:
						return Promise.reject({
							code: '422 - Errore di validazione dati',
							errors: response.data.errors
						});
					break;
					case 404:
						return Promise.reject({
							code: '404 - Pagina non trovata',
							content: response.data.message
						});
					break;
					case 403:
						return Promise.reject({
							code: '403 - Accesso negato',
							content: response.data.message
						});
					break;
				}
				return response;
			},
			(error) => {
				console.log('Si è verificato un errore:', error);
				return Promise.reject(error);
			}
		);
	}
	
	async get(config) {
		
		let headers = config.headers ? config.headers : this.headers;
		
		try {
			const response = await this.instance.get(config.url, { params: config.params, headers: headers });
			return this.manageSuccess(response, config);
		} catch (error) {
			return this.manageError(error, config);
		}
	}

	async post(config) {
		
		let headers = config.headers ? config.headers : this.headers;
		
		try {
			const response = await this.instance.post(config.url, config.data, { headers: headers });
			return this.manageSuccess(response, config);
		} catch (error) {
			return this.manageError(error, config);
		}
	}

	async put(config) {

		let headers = config.headers ? config.headers : this.headers;

		try {
			const response = await this.instance.put(config.url, config.data, { headers: headers });
			return this.manageSuccess(response, config);
		} catch (error) {
			return this.manageError(error, config);
		}
	}
	
	async patch(config) {
		
		let headers = config.headers ? config.headers : this.headers;
		
		try {
			const response = await this.instance.patch(config.url, config.data, { headers: headers });
			return this.manageSuccess(response, config);
		} catch (error) {
			return this.manageError(error, config);
		}
	}
	
	async delete(config) {
		
		let headers = config.headers ? config.headers : this.headers;
		
		try {
			const response = await this.instance.delete(config.url, { params: config.params, headers: headers });
			return this.manageSuccess(response, config);
		} catch (error) {
			return this.manageError(error, config);
		}		
	}
	
	manageSuccess(response, config) {
		if (config.hasOwnProperty('success')) {
			return config.success(response);
		} else {
			return response;
		}
	}
	
	manageError(error, config) {
		if (config.hasOwnProperty('error')) {
			config.error(error);
		} else {
			console.log(error);
		}
	}
	
	setBaseUrl(baseUrl) {
        this.baseUrl = baseUrl;
        this.instance.defaults.baseURL = baseUrl;
    }
	
	setHeaders(headers) {
		this.headers = headers;
	}

}

export default axiosService;