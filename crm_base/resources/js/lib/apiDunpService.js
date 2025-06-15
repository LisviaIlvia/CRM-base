import axiosService from './axiosService';

class apiDunpService {
    constructor() {
		this.instanceAxios = new axiosService();
        this.instanceAxios.setBaseUrl(import.meta.env.VITE_API_DUNP_URL);
		this.instanceAxios.setHeaders(this.getHeaders());
    }
	
	async getNazioni(callbackSuccess, callbackError = null) {
		this.instanceAxios.get({
			url: '/nazioni',
			success: (response) => {
				const denominazioniNazioni = [...new Set(response.data.data.map(nazione => nazione.denominazione_nazione))];
				callbackSuccess(denominazioniNazioni);
			},
			error: (response) => {
				console.log(response);
				if(callbackError != null) callbackError();
			}
		});
    }

    async getRegioni(callbackSuccess, callbackError = null) {
		this.instanceAxios.get({
			url: '/regioni',
			success: (response) => {
				callbackSuccess(response.data.data);
			},
			error: (response) => {
				console.log(response);
				if(callbackError != null) callbackError();
			}
		});
    }

    async getProvinceByRegione(codice, callbackSuccess, callbackError = null) {
		this.instanceAxios.get({
            url: `/province/byregione/${codice}`,
			success: (response) => {
				callbackSuccess(response.data.data);
			},
			error: (response) => {
				console.log(response);
				if(callbackError != null) callbackError();
			}
		});
    }

    async getComuniByProvincia(sigla, callbackSuccess, callbackError = null) {
		this.instanceAxios.get({
            url: `/comuni/byprovincia/${sigla}`,
			success: (response) => {
				callbackSuccess(response.data.data);
			},
			error: (response) => {
				console.log(response);
				if(callbackError != null) callbackError();
			}
		});
    }

    async getCapByComune(codice_istat, callbackSuccess, callbackError = null) {
		this.instanceAxios.get({
            url: `/cap/bycomune/${codice_istat}`,
			success: (response) => {
				callbackSuccess(response.data.data);
			},
			error: (response) => {
				console.log(response);
				if(callbackError != null) callbackError();
			}
		});
    }

    getHeaders() {
        return {
            'Content-Type': 'application/json',
            'authorization': import.meta.env.VITE_API_DUNP_AUTH,
            'license': import.meta.env.VITE_API_DUNP_LICENSE
        };
    }
}

export default new apiDunpService();