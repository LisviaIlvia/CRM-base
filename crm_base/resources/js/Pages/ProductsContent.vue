<template>
	<v-row>
		<v-col
			:cols="data.giacenza === true ? 3 : 5"
			class="py-0"
		>
			<v-text-field
				v-model="form.codice"
				label="Codice"
				:color="crudDialog.color"
				:readonly="readonly"
				:error-messages="crudDialog.errors && crudDialog.errors.codice ? crudDialog.errors.codice : ''"
			></v-text-field>
		</v-col>
		<v-col
			:cols="data.giacenza === true ? 6 : 7"
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
			v-if="data.giacenza === true"
			cols="3"
			class="py-0"
		>
			<v-text-field
				v-model="form.giacenza_iniziale"
				label="Giacenza Iniziale"
				:color="crudDialog.color"
				type="Number"
				:readonly="readonly"
				:error-messages="crudDialog.errors && crudDialog.errors.giacenza_iniziale ? crudDialog.errors.giacenza_iniziale : ''"
			></v-text-field>
		</v-col>
		<prezzo-iva
			v-model="form.prezzo_iva"
			:aliquoteIva="data.aliquote_iva"
			:color="crudDialog.color"
			:errorMessages="crudDialog.errors"
			:readonly="readonly"
			class="mb-2"
		/>
		<v-col
			class="py-0 col-um"
		>
			<v-select
				v-model="form.unita_misura"
				:items="['NR']"
				label="Unità di misura"
				:color="crudDialog.color"
				:readonly="readonly"
				:error-messages="crudDialog.errors && crudDialog.errors.unita_misura ? crudDialog.errors.unita_misura : ''"
			></v-select>
		</v-col>
		<v-col
			class="py-0 col-cat"
		>
			<v-select
				v-model="form.categorie"
				:items="data.categorie"
				item-title="nome"
				item-value="id"
				label="Categorie"
				:color="crudDialog.color"
				chips
				multiple
				:readonly="readonly"
			></v-select>
		</v-col>
		<v-col
			class="py-0 col-ric"
		>
			<v-select
				v-model="form.ricorrenza"
				:items="data.ricorrenze"
				item-title="title"
				item-value="value"
				label="Ricorrenza"
				:color="crudDialog.color"
				:readonly="readonly"
				:error-messages="crudDialog.errors && crudDialog.errors.ricorrenza ? crudDialog.errors.ricorrenza : ''"
			></v-select>
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
			v-if="data.rinnovabile.active && form.ricorrenza != 'una_tantum'"
			cols="12"
			class="py-0 mt-n5 mb-5"
		>
			<v-switch
				v-model="form.rinnovabile"
				:label="`Il ${data.rinnovabile.text} è rinnovabile`"
				:color="crudDialog.color"
				hide-details
				:readonly="readonly"
			></v-switch>
		</v-col>
	</v-row>
	<v-row
		v-if="crud === 'show'"
		class="mb-0"
	>
		<v-col
			cols="12"
			class="py-0 mt-6"
		>
			<document-group
				:documents="data.documents"
				:key="'document-group-' + form.id"
			/>
		</v-col>
	</v-row>
</template>
<style scoped>
.col-um {
	flex: 0 0 20%;
	max-width: 20%;
}
.col-cat {
	flex: 0 0 50%;
	max-width: 50%;
}
.col-ric {
	flex: 0 0 30%;
	max-width: 30%;
}
</style>

<script>
import PrezzoIva from '@/Components/PrezzoIva.vue';
import DocumentGroup from '@/Components/DocumentGroup.vue';

export default {
	name: 'ProductsContent',
	components: {
		PrezzoIva,
		DocumentGroup
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
				form: {
					codice: null,
					nome: null,
					descrizione: null,
					unita_misura: null,
					categorie: [],
					ricorrenza: null,
					rinnovabile: false,
					giacenza_iniziale: 0,
					prezzo_iva: {
						prezzo: 0,
                        aliquota_iva_id: null,
                        tax_in: false,
						aliquota_iva_predefinita: true
					}
				},
				data: {
					aliquote_iva: [],
					categorie: [],
					ricorrenze: [],
					rinnovabile: {
						active: false,
						text: ''
					},
					giacenza: false,
					documents: {}
				},
			};
		},
		getData() {
			if(this.dataElements.resource) {
				this.form = { ...this.dataElements.resource };
				if(this.crud != 'create') {
					this.form.categorie = this.dataElements.categorie_selezionate;
					this.form.prezzo_iva = this.dataElements.resource.prezzo_iva;
				}
				if(this.crud == 'show') {
					this.data.documents = this.dataElements.resource.documents;
				}
			}
			this.data.aliquote_iva = this.dataElements.aliquote_iva;
			this.data.categorie = this.dataElements.categorie;
			this.data.ricorrenze = this.dataElements.ricorrenze;
			this.data.rinnovabile = {
				active: this.dataElements.rinnovabile.active,
				text: this.dataElements.rinnovabile.text
			};
			this.data.giacenza = this.dataElements.giacenza;
		},
		getForm() {
			return this.form;
		}
	}
}
</script>
