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
			<v-textarea
				v-model="form.descrizione"
				label="Descrizione"
				:color="crudDialog.color"
				:error-messages="crudDialog.errors && crudDialog.errors.descrizione ? crudDialog.errors.descrizione : ''"
			></v-textarea>
		</v-col>
		<prezzo-iva
			v-model="form.prezzo_iva"
			:aliquoteIva="data.aliquote_iva"
			:color="crudDialog.color"
			:errorMessages="crudDialog.errors"
			:readonly="readonly"
		/>
		<v-col
		  cols="12"
		  class="py-0"
		>
			<v-switch
				v-model="form.predefinita"
				label="Predefinita"
				:color="crudDialog.color"
				hide-details
			></v-switch>
		</v-col>
	</v-row>
</template>

<script>
import PrezzoIva from '@/Components/PrezzoIva.vue';

export default {
	name: 'SpedizioniContent',
	components: {
		PrezzoIva
	},
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
						name: 'predefinita',
						value: false
					},
				},
				form: {
					nome: null,
					descrizione: null,
					predefinita: false,
					prezzo_iva: {
						prezzo: 0,
                        aliquota_iva_id: null,
                        tax_in: true
					}
				},
				data: {
					aliquote_iva: []
				}
			};
		},
		getData() {
			if(this.dataElements.resource) {
				this.form = { ...this.dataElements.resource };
				if(this.crud != 'create') {
					this.form.prezzo_iva = this.dataElements.resource.prezzo_iva;
				}
			}
			this.data.aliquote_iva = this.dataElements.aliquote_iva;
		},
		getForm() {
			return this.form;
		}
	}
}
</script>