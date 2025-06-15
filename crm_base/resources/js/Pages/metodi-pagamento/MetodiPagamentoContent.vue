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
				:error-messages="crudDialog.errors && crudDialog.errors.nome ? crudDialog.errors.nome : ''"
			></v-text-field>
		</v-col>
		<v-col
			cols="12"
			class="py-0"
		>
			<v-text-field
				v-model="form.giorni"
				label="Giorni"
				type="number"
				:color="crudDialog.color"
				:error-messages="crudDialog.errors && crudDialog.errors.giorni ? crudDialog.errors.giorni : ''"
			></v-text-field>
		</v-col>
		<v-col
			cols="12"
			class="py-0"
		>
			<v-switch
				v-model="form.predefinito"
				label="Predefinito"
				:color="crudDialog.color"
				hide-details
			></v-switch>
		</v-col>
	</v-row>
</template>

<script>
export default {
	name: 'MetodiPagamentoContent',
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
				setup: {
					field : {
						name: 'predefinito', 
						value: 0
					},
				},
				form: {
					nome: null,
					giorni: null,
					predefinito: false
				}
			};
		},
		getData() {
			if(this.dataElements.resource) {
				this.form = { ...this.dataElements.resource };
				this.form.predefinito = this.dataElements.resource.predefinito == 1 ? true : false;
			}
		},
		getForm() {
			return this.form;
		}
	}
}
</script>
