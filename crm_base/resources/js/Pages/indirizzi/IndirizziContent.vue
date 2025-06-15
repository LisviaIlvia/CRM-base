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
				:readonly="form.nome === 'PRINCIPALE'"
				:error-messages="crudDialog.errors && crudDialog.errors.nome ? crudDialog.errors.nome : ''"
			></v-text-field>
		</v-col>
		<indirizzo-dettagli
			:readonly="readonly"
			:color="crudDialog.color"
			:errors="crudDialog.errors"
			ref="indirizzoDettagliRef"
		/>
		<v-col
			cols="12"
			class="py-0"
		>
			<v-textarea
				v-model="form.note"
				label="Note"
				:color="crudDialog.color"
				:readonly="readonly"
				:error-messages="crudDialog.errors && crudDialog.errors.note ? crudDialog.errors.note : ''"
			></v-textarea>
		</v-col>
	</v-row>
</template>

<script>
import IndirizzoDettagli from '@/Components/IndirizzoDettagli.vue';

export default {
	name: 'IndirizziContent',
	components: {
		IndirizzoDettagli
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
					note: null,
					element_id: null
				}
			};
		},
		getData() {
			if(this.dataElements && this.dataElements.resource) {
				this.form.nome = this.dataElements.resource.nome;
				this.form.note = this.dataElements.resource.note;
				const indDet = this.$refs.indirizzoDettagliRef;
				indDet.form.nazione = this.dataElements.resource.nazione;
				indDet.form.indirizzo = this.dataElements.resource.indirizzo;
				indDet.form.comune = this.dataElements.resource.codice_comune;
				indDet.form.nome_comune = this.dataElements.resource.comune;
				indDet.form.provincia = this.dataElements.resource.provincia;
				indDet.form.cap = this.dataElements.resource.cap;
				indDet.form.regione = this.dataElements.resource.codice_regione;
				indDet.form.nome_regione = this.dataElements.resource.regione;
				indDet.form.telefono = this.dataElements.resource.telefono;
				
				if(indDet.form.nazione == 'ITALIA') {
					indDet.loadRegioni();
					indDet.loadProvince(false);
					indDet.loadComuni(false);
					indDet.loadCAP(false);
				}
				
				if(this.crud == 'create') {
					indDet.form.nazione = 'ITALIA';
					this.form.nome = this.dataElements.resource.nome;
					this.form.element_id = this.elementId;
				}
			}
		},
		getForm() {
			return { ...this.form, ...this.$refs.indirizzoDettagliRef.getForm() };
		}
	}
}
</script>
