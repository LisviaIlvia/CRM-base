<template>
	<v-row>
		<v-col
			cols="12"
			class="py-0"
		>
			<v-select
				v-model="form.type"
				:items="data.types"
				label="Tipo"
				:color="crudDialog.color"
				:readonly="readonly"
				:error-messages="crudDialog.errors && crudDialog.errors.type ? crudDialog.errors.type : ''"
			></v-select>
		</v-col>
		<v-col
			cols="12"
			class="py-0"
		>
			<v-date-input 
				v-model="form.data"
				placeholder="gg/mm/aaaa"
				variant="outlined"
				label="Data"
				:color="crudDialog.color"
				:prepend-icon="null"
				prepend-inner-icon="fa-solid fa-calendar"
				:error-messages="crudDialog.errors && crudDialog.errors.data ? crudDialog.errors.data : ''"
			></v-date-input>
		</v-col>
		<v-col
			cols="12"
			class="py-0"
		>
			<v-textarea
				v-model="form.descrizione"
				label="Descrizione"
				:color="crudDialog.color"
				:readonly="readonly"
				:error-messages="crudDialog.errors && crudDialog.errors.descrizione ? crudDialog.errors.descrizione : ''"
			></v-textarea>
		</v-col>
	</v-row>
</template>

<script>
export default {
	name: 'AttivitaContent',
	props: {
		crudDialog: {
			type: Object,
			required: true
		},
		dataElements: {
			type: Object,
			required: true
		},
		readonly: {
			type: Boolean,
			default: false
		},
		crud: {
			type: String,
			required: true
		},
		elementId: {
			type: Number,
			default: null
		}
	},
	mounted() {
		this.getData();
	},
	data() {
		return this.initialState();
	},
	methods: {
		initialState() {
			return {
				setup: {
					nameField: 'type'
				},
				form: {
					type: null,
					data: null,
					descrizione: null,
					element_id: null
				},
				data: {
					types: []
				}
			};
		},
		formatDate() {
			if(this.form.data !== null) {
				const day = `0${this.form.data.getDate()}`.slice(-2);
				const month = `0${this.form.data.getMonth() + 1}`.slice(-2);
				const year = this.form.data.getFullYear();
				this.form.data = `${year}-${month}-${day}`;
			}
		},
		getData() {
			if(this.dataElements && this.dataElements.resource) {
				this.form.type = this.dataElements.resource.type;
				this.form.descrizione = this.dataElements.resource.descrizione;
				this.data.types = this.dataElements.types;
				if(this.crud == 'create') {
					this.form.element_id = this.elementId;
				}
				if(this.crud == 'edit' || this.crud == 'show') {
					this.form.data = new Date(this.dataElements.resource.data);
				}
			}
		},
		getForm() {
			this.formatDate();
			return this.form;
		}
	}
}
</script>
