<template>
	<v-row>
		<v-col
			cols="12"
			class="py-0"
		>
			<number-decimal
				v-model="form.aliquota"
				label="Aliquota"
				:color="crudDialog.color"
				:readonly="readonly"
				:error-messages="crudDialog.errors && crudDialog.errors.aliquota ? crudDialog.errors.aliquota : ''"
			/>
		</v-col>
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
			<v-textarea
				v-model="form.descrizione"
				label="Descrizione"
				:color="crudDialog.color"
				:readonly="readonly"
				:error-messages="crudDialog.errors && crudDialog.errors.descrizione ? crudDialog.errors.descrizione : ''"
			></v-textarea>
		</v-col>
		<v-col
		  cols="12"
		  class="py-0"
		>
			<v-switch
				v-model="form.predefinita"
				label="Predefinita"
				:color="crudDialog.color"
				:readonly="readonly"
				hide-details
			></v-switch>
		</v-col>
	</v-row>
</template>

<script>
export default {
	name: 'AliquotaIvaContent',
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
					nameField: 'aliquota',
					field : {
						name: 'predefinita',
						value: false
					},
				},
				form: {
					aliquota: 0,
					nome: null,
					descrizione: null,
					predefinita: false
				}
			};
		},
		getData() {
			if(this.dataElements.resource) {
				this.form = { ...this.dataElements.resource };
				this.form.aliquota = this.form.aliquota ?? 0;
			}
		},
		getForm() {
			return this.form;
		}
	}
}
</script>