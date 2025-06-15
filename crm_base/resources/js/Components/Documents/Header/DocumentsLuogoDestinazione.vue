<template>
	<v-card
		elevation="4"
		variant="flat"
	>
		<v-toolbar
			class="px-3"
		>
			<v-row class="align-center bg-grey-lighten-3">
				<v-col cols="12">
					<header-box title="Luogo di destinazione" icon="fa-solid fa-location-dot">
						<v-checkbox
							v-model="destinazioneDifferente"
							class="text-center"
							label="Destinazione differente?"
							hide-details
							:color="color"
							:readonly="readonly"
						></v-checkbox>
					</header-box>
				</v-col>
			</v-row>
		</v-toolbar>
		<v-row class="px-4 mt-6 pb-3">
			<v-col 
				v-if="crud == 'create' && destinazioneDifferente == false"
				cols="8" 
				class="py-0"
			>
				<v-select
					v-model="form.indirizzo_id"
					:items="data.indirizzi"
					item-title="nome"
					item-value="id"
					label="Seleziona Indirizzo"
					:color="color"
					:disabled="!entity_id"
					@update:modelValue="updateIndirizzoDettagli"
					:readonly="readonly"
				></v-select>
			</v-col>
			<v-col 
				v-else
				cols="8" 
				class="py-0"
			>
				<v-text-field
					v-model="nome"
					label="Nome"
					:color="color"
					readonly
				></v-text-field>
			</v-col>
			<v-col v-if="crud == 'create'" cols="4" class="py-0">
				<v-text-field
					v-model="telefono"
					label="Telefono"
					:color="color"
					readonly
				></v-text-field>
			</v-col>
			<v-col
				v-if="crud == 'create'"
				class="py-0 col-indirizzo"
			>
				<v-text-field
					v-model="indirizzo"
					label="Indirizzo"
					:color="color"
					readonly
				></v-text-field>
			</v-col>
			<v-col 
				:class="crud === 'create' ? 'py-0 col-comune' : 'py-0'"
				:cols="crud === 'create' ? null : 8"
			>
				<v-text-field
					v-model="comune"
					label="Comune"
					:color="color"
					readonly
				></v-text-field>
			</v-col>
			<v-col 
				:class="crud === 'create' ? 'py-0 col-prov' : 'py-0'"
				:cols="crud === 'create' ? null : 2"
			>
				<v-text-field
					v-model="provincia"
					label="Provincia"
					:color="color"
					readonly
				></v-text-field>
			</v-col>
			<v-col 
				:class="crud === 'create' ? 'py-0 col-cap' : 'py-0'"
				:cols="crud === 'create' ? null : 2"
			>
				<v-text-field
					v-model="cap"
					label="CAP"
					:color="color"
					readonly
				></v-text-field>
			</v-col>
			<v-col
				v-if="crud != 'create'"
				cols="8"
				class="py-0"
			>
				<v-text-field
					v-model="indirizzo"
					label="Indirizzo"
					:color="color"
					readonly
				></v-text-field>
			</v-col>
			<v-col 
				v-if="crud != 'create'" 
				cols="4" 
				class="py-0"
			>
				<v-text-field
					v-model="telefono"
					label="Telefono"
					:color="color"
					readonly
				></v-text-field>
			</v-col>
			<v-col class="py-0 mt-n7 text-center" cols="12" v-if="errors && errors.indirizzo">
				<ul class="errors-indirizzo">
					<li>{{ errors.indirizzo[0] }}</li>
				</ul>
			</v-col>
		</v-row>
	</v-card>
</template>
<style scoped>
	.col-indirizzo {
		flex: 0 0 42%;
		max-width: 42%;
	}
	.col-comune {
		flex: 0 0 34%;
		max-width: 34%;
	}
	.col-prov {
		flex: 0 0 12%;
		max-width: 12%;
	}
	
	.col-cap {
		flex: 0 0 12%;
		max-width: 12%;
	}
	
	.errors-indirizzo {
		font-size: 11px;
		color: rgb(var(--v-theme-error));
		list-style: none;
	}
</style>

<script>
export default {
	name: 'DocumentsLuogoDestinazione',
	props: {
		entity_id: {
			type: Number,
			default: null
		},
		entity_type: {
			type: String,
			default: null
		},
		intestatari: {
			type: Object,
			required: true
		},
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
	inject: ['crud'],
	emits: ['ready'],
	mounted() {
		this.$emit('ready');
	},
	computed: {
		telefono() {
			return this.crud === 'create' ? this.data.indirizzoDettagli.telefono : this.form.indirizzo.telefono;
		},
		indirizzo() {
			return this.crud === 'create' ? this.data.indirizzoDettagli.indirizzo : this.form.indirizzo.indirizzo;
		},
		comune() {
			return this.crud === 'create' ? this.data.indirizzoDettagli.comune : this.form.indirizzo.comune;
		},
		provincia() {
			return this.crud === 'create' ? this.data.indirizzoDettagli.provincia : this.form.indirizzo.provincia;
		},
		cap() {
			return this.crud === 'create' ? this.data.indirizzoDettagli.cap : this.form.indirizzo.cap;
		}
	},
	data() {
		return this.initialState();
	},
	methods: {
		initialState() {
			return {
				destinazioneDifferente: false,
				form: {
					indirizzo_id: null,	
					indirizzo: {
						indirizzo: '', 
						comune: '', 
						provincia: '', 
						cap: '',
						telefono: ''
					}
				},
				data: {
					indirizzi: [],
					indirizzoDettagli: { indirizzo: '', comune: '', provincia: '', cap: '', telefono: '' }
				}
			}
		},
		updateIndirizzo() {
			this.data.indirizzi = [];
			this.data.indirizzoDettagli = { indirizzo: '', comune: '', provincia: '', cap: '', telefono: ''};
			this.form.indirizzo_id = null;
			let intestatarioSelezionato;
			if (this.entity_type && this.entity_id) {
				const intestatari = this.intestatari[this.entity_type];
				if (intestatari && intestatari.length) {
					intestatarioSelezionato = intestatari.find(intestatario => intestatario.id === this.entity_id);
				}
			}
			
			if (intestatarioSelezionato && intestatarioSelezionato.indirizzi && intestatarioSelezionato.indirizzi.length > 0) {
				this.data.indirizzi = intestatarioSelezionato.indirizzi.map(indirizzo => {
					if (indirizzo.nome === 'PRINCIPALE') {
						this.form.indirizzo_id = indirizzo.id;
					}
					return {
						id: indirizzo.id,
						nome: `${indirizzo.nome} - ${indirizzo.comune} (${indirizzo.provincia}), ${indirizzo.indirizzo}`,
						dettagli: indirizzo
					};
				});
				this.updateIndirizzoDettagli(this.form.indirizzo_id);
			}
		},
		updateIndirizzoDettagli(indirizzoId) {
			let indirizzi = this.data.indirizzi;
			const indirizzoSelezionato = indirizzi.find(indirizzo => indirizzo.id === indirizzoId);
			if (indirizzoSelezionato && indirizzoSelezionato.dettagli) {
				const dettagli = indirizzoSelezionato.dettagli;
					this.data.indirizzoDettagli = {
						nazione: dettagli.nazione || '',
						indirizzo: dettagli.indirizzo || '',
						comune: dettagli.comune || '',
						provincia: dettagli.provincia || '',
						cap: dettagli.cap || '',
						telefono: dettagli.telefono || '',
						email: dettagli.email || ''
					};
			} else {
				this.data.indirizzoDettagli = { indirizzo: '', comune: '', provincia: '', cap: '', telefono: ''};
			}
		},
		resetIndirizzo() {
			this.form.indirizzo_id = null;
			this.data.indirizzi = [];
			this.data.indirizzoDettagli = { indirizzo: '', comune: '', provincia: '', cap: '', telefono: '' };
		
		},
		getForm() {
			if(this.crud == 'create') {
				const indirizzoSelezionato = this.data.indirizzi.find(indirizzo => indirizzo.id === this.form.indirizzo_id);
				if (indirizzoSelezionato && indirizzoSelezionato.dettagli) {
					return { indirizzo: {
							indirizzo: indirizzoSelezionato.dettagli.indirizzo,
							comune: indirizzoSelezionato.dettagli.comune,
							provincia: indirizzoSelezionato.dettagli.provincia,
							cap: indirizzoSelezionato.dettagli.cap,
							telefono: indirizzoSelezionato.dettagli.telefono,
							note: indirizzoSelezionato.dettagli.note
						}
					}
				}
			} else {
				return { indirizzo: this.form.indirizzo };
			}
		}
	},
	watch: {
		entity_type(newVal) {
			this.resetIndirizzo();
		},
		entity_id(newVal) {
			this.updateIndirizzo();
		}
	}
}
</script>