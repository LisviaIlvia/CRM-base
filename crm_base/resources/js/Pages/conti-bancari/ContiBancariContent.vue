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
		<iban
			v-model="form.iban"
			:color="crudDialog.color"
			:readonly="readonly"
			:errorMessages="crudDialog.errors && crudDialog.errors.iban ? crudDialog.errors.iban : ''"
		/>
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
import Iban from '@/Components/Iban.vue';

export default {
	name: 'ContiBancariContent',
	components: {
		Iban
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
						name: 'predefinito',
						value: false
					},
				},
				form: {
					nome: null,
					iban: '',
					predefinito: false
				}
			};
		},
		getData() {
			if(this.dataElements.resource) {
				this.form = { ...this.dataElements.resource };
			}
		},
		getForm() {
			return this.form;
		}
	}
}
</script>