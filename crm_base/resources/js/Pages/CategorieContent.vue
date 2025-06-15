<template>
	<v-row>
		<v-col
		  cols="12"
		  class="py-0"
		>
			<v-text-field
				v-model="form.nome"
				label="Nome"
				:color="crudDialog.color"
				:readonly="readonly"
				:error-messages="crudDialog.errors && crudDialog.errors.nome ? crudDialog.errors.nome : ''"
			></v-text-field>
		</v-col>
		<v-col
		  cols="12"
		  class="py-0"
		>
			<v-select
				v-model="form.genitore"
				:items="data.categorie"
				item-title="nome"
				item-value="id"
				label="Genitore"
				:color="crudDialog.color"
				:readonly="readonly"
				:error-messages="crudDialog.errors && crudDialog.errors.genitore ? crudDialog.errors.genitore : ''"
			></v-select>
		</v-col>
	</v-row>
</template>

<script>
export default {
	name: 'CategoriesContent',
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
				form: {
					nome: null,
					genitore: null
				},
				data: {
					categorie: []
				}
			};
		},
		getData() {
			if(this.dataElements.resource) {
				this.form.nome = this.dataElements.resource.nome;
				if(this.dataElements.resource.parent_id) {
					this.form.genitore = this.dataElements.resource.parent_id;
				} else {
					this.form.genitore = 0;
				}				
			}
			this.data.categorie = this.dataElements.categorie;
		},
		getForm() {
			return this.form;
		}
	}
}
</script>
