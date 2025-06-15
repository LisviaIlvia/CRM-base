import axiosService from '@/lib/axiosService';

class crudDialog {
	
	constructor(type) {
		this.instanceAxios = new axiosService();
		
		this.loadingSubmit = false;
		this.urlForm = null;
		this.errors = [];
		this.type = type;
		this.urlOpen = null;
		
		switch(this.type) {
			case 'show':
				this.color = 'color-show';
				this.bg = 'bg-teal-lighten-5';
				this.icon = 'fa-solid fa-eye';
			break;
			case 'create':
				this.color = 'color-create';
				this.bg = 'bg-blue-lighten-5';
				this.icon = 'fa-solid fa-plus';
			break;
			case 'edit':
				this.color = 'color-edit';
				this.bg = 'bg-amber-lighten-5';
				this.icon = 'fa-solid fa-pen';
			break;
			case 'delete':
				this.color = 'color-delete';
				this.bg = 'bg-red-lighten-5';
				this.icon = 'fa-solid fa-trash';
			break;
			case 'clone':
				this.color = 'color-clone';
				this.bg = 'bg-pink-lighten-5';
				this.icon = 'fa-solid fa-clone';
			break;
			case 'magic':
				this.color = 'color-magic';
				this.bg = 'bg-purple-lighten-5';
				this.icon = 'fa-solid fa-wand-magic-sparkles';
			break;
		}
	}
	
	open(url, callbackSuccess, options = {}) {
		const { callbackError = null, extraData = null } = options;
		if(this.type === 'show') this.urlOpen = url;
		this.urlOpen = this.buildUrlData(this.urlOpen, extraData);
		switch (this.type) {
			case 'show':
			case 'create':
			case 'edit':
			case 'magic':
				this.fetchData(url, callbackSuccess, callbackError);
				break;
			case 'delete':
			case 'clone':
				this.urlForm = url;
				callbackSuccess();
				break;
			default:
				throw new Error(`Unsupported type: ${this.type}`);
		}
	}

	buildUrlData(baseUrl, extraData) {
		let urlWithParams = baseUrl;
		if (extraData) {
			const queryParams = Object.keys(extraData).map(
				key => `${encodeURIComponent(key)}=${encodeURIComponent(extraData[key])}`
			).join('&');
			urlWithParams += (baseUrl.includes('?') ? '&' : '?') + queryParams;
		}
		return urlWithParams;
	}

	fetchData(url, callbackSuccess, callbackError) {
		this.instanceAxios.get({
			url: this.urlOpen,
			success: (response) => {
				if(this.type !== 'show') this.urlForm = url;
				callbackSuccess(response.data);
			},
			error: (error) => {
				console.log(error);
				this.handleError(error, callbackError);
			}
		});
	}
	
	send(data, callback, options = {}) {
		const {
			callbackError = null,
			containsFile = false
		} = options;
		
		this.loadingSubmit = true;	
		
		const headers = containsFile ? { 'Content-Type': 'multipart/form-data' } : {};
		const method = containsFile ? 'post' : this.getMethod(this.type);
		const dataToSend = this.prepareData(data, containsFile);
		this.instanceAxios[method]({
			url: this.urlForm,
			data: dataToSend,
			headers: headers,
			success: (response) => {
				callback(response.data);
			},
			error: (error) => {
				this.handleError(error);
				this.loadingSubmit = false;
			}
		});
	}
	
	getMethod() {
		switch (this.type) {
			case 'create':
			case 'magic':
			case 'clone':
				return 'post';
			case 'edit':
				return 'put';
			case 'delete':
				return 'delete';
			default:
				throw new Error('Unsupported operation');
		}
	}
	
	prepareData(data, containsFile) {
		if(this.type === 'delete' || this.type === 'clone') return null; 
		if(containsFile && this.type === 'edit') {
			return { ...data, _method: 'PUT' };
		}
		return data;
	}

	handleError(error, callbackError) {
		console.log(error);
		this.errors = error.errors || {};
		if(callbackError) callbackError(error);
	}
}

export default crudDialog;