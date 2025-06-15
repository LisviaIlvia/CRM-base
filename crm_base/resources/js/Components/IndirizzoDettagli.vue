<template>
	<v-row class="ma-0">
		<v-col 
			cols="6" 
			class="py-0"
		>
			<v-select
				v-model="form.nazione"
				:items="data.nazioni"
				label="Nazione"
				:color="color"
				:readonly="readonly"
				@update:modelValue="loadRegioni"
				:error-messages="errors && errors.nazione ? errors.nazione : ''"
			></v-select>
		</v-col>
		<v-col 
			cols="6" 
			class="py-0" 
			v-if="form.nazione === 'ITALIA'"
		>
			<v-select
				v-model="form.regione"
				:items="data.regioni"
				item-title="denominazione_regione"
				item-value="codice_regione"
				label="Regione"
				:readonly="readonly"
				@update:modelValue="loadProvince"
				:error-messages="errors && errors.regione ? errors.regione : ''"
			></v-select>
		</v-col>
		<v-col 
			cols="4" 
			class="py-0" 
			v-if="form.nazione === 'ITALIA'"
		>
			<v-select
				v-model="form.provincia"
				:items="data.province"
				item-title="denominazione_provincia"
				item-value="sigla_provincia"
				label="Provincia"
				:disabled="!form.regione"
				:readonly="readonly"
				@update:modelValue="loadComuni"
				:error-messages="errors && errors.provincia ? errors.provincia : ''"
			></v-select>
		</v-col>
		<v-col 
			cols="6" 
			class="py-0"
		>
			<v-select
				v-if="form.nazione === 'ITALIA'"
				v-model="form.comune"
				:items="data.comuni"
				item-title="denominazione_ita"
				item-value="codice_istat"
				label="Comune"
				:disabled="!form.provincia"
				:readonly="readonly"
				@update:modelValue="loadCAP"
				:error-messages="errors && errors.comune ? errors.comune : ''"
			></v-select>
			<v-text-field
				v-else
				v-model="form.nome_comune"
				label="Città"
				:color="color"
				:readonly="readonly"
				:error-messages="errors && errors.comune ? errors.comune : ''"
			></v-text-field>
		</v-col>
		<v-col 
			cols="2" 
			class="py-0" 
			v-if="form.nazione === 'ITALIA'"
		>
			<v-select
				v-model="form.cap"
				:items="data.cap"
				item-title="cap"
				item-value="cap"
				label="CAP"
				:disabled="!form.comune"
				:readonly="readonly"
				:error-messages="errors && errors.cap ? errors.cap : ''"
			></v-select>
		</v-col>
		<v-col
			cols="8"
			class="py-0"
		>
			<v-text-field
				v-model="form.indirizzo"
				label="Indirizzo"
				:color="color"
				:readonly="readonly"
				:error-messages="errors && errors.indirizzo ? errors.indirizzo : ''"
			></v-text-field>
		</v-col>
		<v-col
			cols="4"
			class="py-0"
		>
			<v-text-field
				v-model="form.telefono"
				label="Telefono"
				:color="color"
				:readonly="readonly"
				:error-messages="errors && errors.telefono ? errors.telefono : ''"
			></v-text-field>
		</v-col>
	</v-row>
</template>

<script>
export default {
	name: 'IndirizzoDettagli',
	props: {
		color: {
			type: String,
			required: true
		},
		errors: {
			type: Object,
			default: () => ({})
		},
		readonly: {
			type: Boolean,
			default: false
		}
	},
	mounted() {
		this.loadNazioni();
		this.loadRegioni();
	},
	data() {
		return this.initialState();
	},
	methods: {
		initialState() {
			return {
				form: {
					nazione: 'ITALIA',
					indirizzo: null,
					comune: null,
					nome_comune: null,
					provincia: null,
					cap: null,
					regione: null,
					nome_regione: null,
					telefono: null
				},
				data: {
					nazioni: [],
					regioni: [],
					province: [],
					comuni: [],
					cap: []
				}
			};
		},
		loadNazioni() {
			this.$apiDunpService.getNazioni((data) => {
				this.data.nazioni = data;
			});
		},
		loadRegioni() {
			if(this.form.nazione == 'ITALIA') {
				this.$apiDunpService.getRegioni((data) => {
					this.data.regioni = data;
				});
			} else {
				this.form.regione = null;
				this.form.provincia = null;
				this.form.comune = null;
				this.form.cap = null;
				this.data.regioni = [];
				this.data.province = [];
				this.data.comuni = [];
				this.data.cap = [];
			}
		},
		loadProvince(reset = true) {
			if (!this.form.regione) {
			    this.form.provincia = null;
				this.form.comune = null;
				this.form.cap = null;
				this.data.province = [];
				this.data.comuni = [];
				this.data.cap = [];
				return;
			}
			this.$apiDunpService.getProvinceByRegione(this.form.regione, (data) => {
				this.data.province = data;
				if(reset) {
					this.form.nome_regione = this.data.regioni.find(r => r.codice_regione === this.form.regione)?.denominazione_regione || '';
					this.form.provincia = null;
					this.form.comune = null;
					this.form.cap = null;
					this.data.comuni = [];
					this.data.cap = [];
				}
			});
		},
		loadComuni(reset = true) {
			if (!this.form.provincia) {
				this.form.comune = null;
				this.form.cap = null;
				this.data.comuni = [];
				this.data.cap = [];
				return;
			}
			this.$apiDunpService.getComuniByProvincia(this.form.provincia, (data) => {
				this.data.comuni = data;
				if(reset) {
					this.form.comune = null;
					this.form.cap = null;
					this.data.cap = [];
				}
			});
		},
		loadCAP(reset = true) {
			if (!this.form.comune) {
				this.form.cap = null;
				this.data.cap = [];
				return;
			}
			this.$apiDunpService.getCapByComune(this.form.comune, (data) => {
				this.data.cap = data;
				if(reset) {
					this.form.nome_comune = this.data.comuni.find(c => c.codice_istat === this.form.comune)?.denominazione_ita || '';
					this.form.cap = null;
				}
			});
		},
		getForm() {
			return this.form;
		}
	}
}	
</script>
