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
					<header-box :title="trasportoActive ? 'Luogo di destinazione' : 'Indirizzo'" icon="fa-solid fa-location-dot">
						<v-checkbox
							v-if="trasportoActive && crud == 'create'"
							v-model="destinazioneDifferente"
							class="text-center"
							label="Destinazione differente?"
							hide-details
							:color="color"
							@update:modelValue="updateIndirizzoDifferente"
						></v-checkbox>
					</header-box>
				</v-col>
			</v-row>
		</v-toolbar>
		<v-row 
			v-if="destinazioneDifferente == false"
			class="px-4 mt-6 pb-3"
		>
			<v-col 
				cols="8" 
				class="py-0"
			>
				<v-select
					v-if="crud == 'create' && destinazioneDifferente == false"
					v-model="form.indirizzo_id"
					:items="data.indirizzi"
					item-title="nome"
					item-value="id"
					label="Seleziona Indirizzo"
					:color="color"
					:disabled="!entityId"
					@update:modelValue="updateIndirizzoDettagli"
					:readonly="readonly"
				></v-select>
				<v-text-field
					v-else
					v-model="editableFields.nome"
					label="Nome"
					:color="color"
				></v-text-field>
			</v-col>
			<v-col cols="4" class="py-0">
				<v-text-field
					v-model="editableFields.telefono"
					label="Telefono"
					:color="color"
					:readonly="destinazioneDifferente == false"
				></v-text-field>
			</v-col>
			<v-col class="py-0 col-indirizzo">
				<v-text-field
					v-model="editableFields.indirizzo"
					label="Indirizzo"
					:color="color"
					:readonly="destinazioneDifferente == false"
				></v-text-field>
			</v-col>
			<v-col class="py-0 col-comune">
				<v-text-field
					v-model="editableFields.comune"
					label="Comune"
					:color="color"
					:readonly="destinazioneDifferente == false"
				></v-text-field>
			</v-col>
			<v-col class="py-0 col-prov">
				<v-text-field
					v-model="editableFields.provincia"
					label="Provincia"
					:color="color"
					:readonly="destinazioneDifferente == false"
				></v-text-field>
			</v-col>
			<v-col class="py-0 col-cap">
				<v-text-field
					v-model="editableFields.cap"
					label="CAP"
					:color="color"
					:readonly="destinazioneDifferente == false"
				></v-text-field>
			</v-col>
			<v-col class="py-0 mt-n7 text-center" cols="12" v-if="errors && errors.indirizzo">
				<ul class="errors-indirizzo">
					<li>{{ errors.indirizzo[0] }}</li>
				</ul>
			</v-col>
		</v-row>
		<v-row 
			v-else
			class="px-4 mt-6 pb-3"
		>
			<v-col
				cols="12" 
				class="py-0"
			>
				<v-text-field
					v-model="editableFields.nome"
					label="Nome"
					:color="color"
				></v-text-field>
			</v-col>
			<indirizzo-dettagli
				v-if="destinazioneDifferente"
				:readonly="readonly"
				:color="color"
				:errors="errors"
				ref="indirizzoDettagliRef"
			/>
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
import IndirizzoDettagli from '@/Components/IndirizzoDettagli.vue';

export default {
	name: 'DocumentsIndirizzo',
	components: {
		IndirizzoDettagli
	},
	props: {
		entityId: {
			type: Number,
			default: null
		},
		entityType: {
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
	data() {
		return this.initialState();
	},
	methods: {
		initialState() {
			return {
				trasportoActive: false,
				destinazioneDifferente: false,
				form: {
					indirizzo_id: null,	
					indirizzo: {
						nome: '',
						indirizzo: '', 
						comune: '', 
						provincia: '', 
						cap: '',
						telefono: ''
					}
				},
				data: {
					indirizzi: [],
					indirizzoDettagli: { nome: '', indirizzo: '', comune: '', provincia: '', cap: '', telefono: '' },
					nazioni: ['ITALIA'],
					regioni: [],
					province: [],
					comuni: [],
					cap: []
				},
				editableFields: {
					nome: '',
					indirizzo: '',
					nazione: 'ITALIA',
					regione: '',
					nome_regione: '',
					comune: '',
					nome_comune: '',
					provincia: '',
					cap: '',
					telefono: ''
				}
			}
		},
		syncEditableFields() {
			this.editableFields.nome = this.crud === 'create' ? this.data.indirizzoDettagli.nome : this.form.indirizzo.nome;
			this.editableFields.indirizzo = this.crud === 'create' ? this.data.indirizzoDettagli.indirizzo : this.form.indirizzo.indirizzo;
			this.editableFields.comune = this.crud === 'create' ? this.data.indirizzoDettagli.comune : this.form.indirizzo.comune;
			this.editableFields.provincia = this.crud === 'create' ? this.data.indirizzoDettagli.provincia : this.form.indirizzo.provincia;
			this.editableFields.cap = this.crud === 'create' ? this.data.indirizzoDettagli.cap : this.form.indirizzo.cap;
			this.editableFields.telefono = this.crud === 'create' ? this.data.indirizzoDettagli.telefono : this.form.indirizzo.telefono;
		},
		updateIndirizzoDifferente() {
			this.data.indirizzoDettagli = { nome: '', indirizzo: '', comune: '', provincia: '', cap: '', telefono: ''};
			this.form.indirizzo_id = null;
		},
		updateIndirizzo() {
			this.data.indirizzi = [];
			this.data.indirizzoDettagli = { nome: '', indirizzo: '', comune: '', provincia: '', cap: '', telefono: ''};
			this.form.indirizzo_id = null;
			let intestatarioSelezionato;

			if (this.entityType && this.entityId) {
				const intestatari = this.intestatari[this.entityType];
				if (intestatari && intestatari.length) {
					intestatarioSelezionato = intestatari.find(intestatario => intestatario.id === this.entityId);
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
					indirizzo: dettagli.indirizzo || '',
					comune: dettagli.comune || '',
					provincia: dettagli.provincia || '',
					cap: dettagli.cap || '',
					telefono: dettagli.telefono || '',
					email: dettagli.email || ''
				};
			} else {
				this.data.indirizzoDettagli = { nome: '', indirizzo: '', comune: '', provincia: '', cap: '', telefono: ''};
			}
		},
		resetIndirizzo() {
			this.form.indirizzo_id = null;
			this.data.indirizzi = [];
			this.data.indirizzoDettagli = { nome: '', indirizzo: '', comune: '', provincia: '', cap: '', telefono: '' };
		
		},
		getForm() {
			if(this.crud == 'create') {
				const indirizzoSelezionato = this.data.indirizzi.find(indirizzo => indirizzo.id === this.form.indirizzo_id);
				if (indirizzoSelezionato && indirizzoSelezionato.dettagli) {
					const intestatari = this.intestatari[this.entityType];
					const intestatarioSelezionato = intestatari.find(intestatario => intestatario.id === this.entityId);
					return { indirizzo: {
							nome: intestatarioSelezionato.nome,
							indirizzo: indirizzoSelezionato.dettagli.indirizzo,
							comune: indirizzoSelezionato.dettagli.comune,
							provincia: indirizzoSelezionato.dettagli.provincia,
							cap: indirizzoSelezionato.dettagli.cap,
							telefono: indirizzoSelezionato.dettagli.telefono
						}
					};
				} else {
					const indDet = this.$refs.indirizzoDettagliRef;
					if(indDet) {
						return { indirizzo: {
								nome: this.editableFields.nome,
								indirizzo: indDet.form.indirizzo,
								comune: indDet.form.nome_comune,
								provincia: indDet.form.provincia,
								cap: indDet.form.cap,
								telefono: indDet.form.telefono
							}
						};
					 }				 
				}
			} else {
				return { indirizzo: this.form.indirizzo };
			}
		}
	},
	watch: {
		entityType: {
			immediate: true,
			handler(newVal) {
				if (newVal !== null) {
					this.resetIndirizzo();
				}
			}
		},
		entityId: {
			immediate: true,
			handler(newVal) {
				if (newVal !== null) {
					this.updateIndirizzo();
				}
			}
		},
		intestatari: {
			immediate: true,
			handler(newVal) {
				if (newVal !== null && this.entityId) {
					this.updateIndirizzo();
				}
			}
		},
		'data.indirizzoDettagli': {
			handler(newVal) {
				this.syncEditableFields();
			},
			immediate: true
		}
	}
}
</script>