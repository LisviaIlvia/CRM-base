<template>
	<v-row>
		<v-col
		  cols="6"
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
		  cols="6"
		  class="py-0"
		>
			<v-text-field
				v-model="form.cognome"
				label="Cognome"
				:color="crudDialog.color"
				:error-messages="crudDialog.errors && crudDialog.errors.cognome ? crudDialog.errors.cognome : ''"
			></v-text-field>
		</v-col>
		<v-col
		  cols="12"
		  class="py-0"
		>
			<v-text-field
				v-model="form.ruolo"
				label="Ruolo"
				:color="crudDialog.color"
				:error-messages="crudDialog.errors && crudDialog.errors.ruolo ? crudDialog.errors.ruolo : ''"
			></v-text-field>
		</v-col>
		<v-col
		  cols="6"
		  class="py-0"
		>
			<v-text-field
				v-model="form.telefono"
				label="Telefono"
				:color="crudDialog.color"
				:error-messages="crudDialog.errors && crudDialog.errors.telefono ? crudDialog.errors.telefono : ''"
			></v-text-field>
		</v-col>
		<v-col
		  cols="6"
		  class="py-0"
		>
			<v-text-field
				v-model="form.email"
				label="E-mail"
				:color="crudDialog.color"
				:error-messages="crudDialog.errors && crudDialog.errors.email ? crudDialog.errors.email : ''"
			></v-text-field>
		</v-col>
		<v-col
		  cols="12"
		  class="py-0"
		>
			<v-textarea
				v-model="form.note"
				label="Note"
				:color="crudDialog.color"
				:error-messages="crudDialog.errors && crudDialog.errors.note ? crudDialog.errors.note : ''"
			></v-textarea>
		</v-col>
	</v-row>
</template>

<script>
export default {
	name: 'ReferentiContent',
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
				form: {
					nome: null,
					cognome: null,
					ruolo: null,
					telefono: null,
					email: null,
					note: null,
					element_id: null
				}
			};
		},
		getData() {
			if(this.dataElements.resource) {
				this.form = { ...this.dataElements.resource };
			}
			if(this.crud == 'create') {
				this.form.element_id = this.elementId;
			}
		},
		getForm() {
			return this.form;
		}
	}
}
</script>
