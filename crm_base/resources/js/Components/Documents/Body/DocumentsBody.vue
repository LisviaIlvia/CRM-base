<template>
	<v-row>		
		<v-col
		  cols="12"
		  class="py-0 mt-6"
		>
			<v-card
				elevation="4"
				variant="flat"
			>
				<v-toolbar
					class="px-3"
				>
					<v-row class="align-center bg-grey-lighten-3">
						<v-col cols="12">
							<header-box title="Corpo documento" icon="fa-solid fa-list" />
						</v-col>
					</v-row>
				</v-toolbar>
				<v-row
					class="px-4 mt-4 mb-3"
				>
					<v-col cols="12" v-if="readonly === false">
						<v-row align="center">
							<v-col cols="2">
								<v-select
									v-model="setup.tipoSelezione"
									:items="typeProductList"
									label="Seleziona Tipo"
									@return-value="val => val"
									:item-title="val => val.charAt(0).toUpperCase() + val.slice(1)"
									:item-value="val => val"
									:color="color"
									@update:modelValue="tipoSelezioneCambiata"
									hide-details
								></v-select>
							</v-col>
							<v-col cols="2" v-if="setup.tipoSelezione && !['altro', 'descrizione'].includes(setup.tipoSelezione)">
								<v-select
									v-model="setup.metodoFiltro"
									:items="['Categoria', 'Codice', 'Nome']"
									label="Filtra per"
									@update:modelValue="filtroCambiato"
									:color="color"
									hide-details
								></v-select>
							</v-col>
							<v-col cols="2" v-if="setup.metodoFiltro === 'Categoria' && setup.tipoSelezione && categoriePerTipo.length > 0">
								<v-select
									v-model="setup.categoriaParentSelezionata"
									:items="categoriePerTipo"
									item-value="id"
									item-title="nome"
									label="Seleziona Categoria"
									@update:modelValue="selezionaCategoriaParent"
									:color="color"
									hide-details
								></v-select>
							</v-col>
							<v-col cols="3" v-if="setup.categoriaParentSelezionata && sottocategorie.length > 0">
								<v-select
									v-model="setup.categoriaFiglioSelezionata"
									:items="sottocategorie"
									item-value="id"
									item-title="nome"
									label="Seleziona Sotto Categoria"
									:color="color"
									hide-details
								></v-select>
							</v-col>
							<v-col cols="2" v-if="!['altro', 'descrizione'].includes(setup.tipoSelezione) && setup.categoriaParentSelezionata && (sottocategorie.length === 0 || setup.categoriaFiglioSelezionata) || setup.metodoFiltro === 'Codice' || setup.metodoFiltro === 'Nome'">
								<v-autocomplete
									v-model="setup.elementoSelezionato"
									:items="prodottiFiltrati"
									:item-title="itemTitleElementoSelezionato"
									item-value="id"
									label="Seleziona"
									:color="color"
									hide-details
								>
									<template v-slot:item="{ props, item }">
										<v-list-item v-bind="props">
											<v-list-item-subtitle class="text-grey-lighten-1">
												{{ setup.metodoFiltro === 'Codice' ? item.raw.nome : item.raw.codice }}
											</v-list-item-subtitle>
										</v-list-item>
									</template>
								</v-autocomplete>
							</v-col>
							<v-col cols="1">
								<v-btn
									variant="flat"
									density="comfortable"
									icon="fa-solid fa-plus"
									rounded="sm"
									color="color-create"	
									:disabled="!setup.tipoSelezione || (setup.tipoSelezione !== 'altro' && setup.tipoSelezione !== 'descrizione' && !setup.elementoSelezionato)"
									@click="addElemento()"
								/>
							</v-col>
						</v-row>
					</v-col>
					<v-col class="mb-4 px-4" cols="12" v-if="readonly === false">
						<v-divider
							:thickness="4"
							class="border-opacity-75"
							color="bg-grey-lighten-3"
						></v-divider>
					</v-col>
					<v-col cols="12" v-if="form.elementi.length === 0" class="text-center py-5 mt-n6">
						<span class="grey--text">Non sono stati ancora aggiunti elementi</span>
					</v-col>
					<v-col class="py-0 mt-0 text-center" cols="12" v-if="errors && errors.elementi">
						<ul class="errors-elementi">
							<li>{{ errors.elementi[0] }}</li>
						</ul>
					</v-col>
					<v-col cols="12" class="pb-0">
						<vue-draggable 
							v-model="form.elementi"
							handle=".drag-handle"
							:animation="500"
							:disabled="readonly"
						>
							<v-row 
								class="mb-4" 
								align="center" 
								v-for="(element, index) in form.elementi"
								:key="index"
							>
								<v-col class="drag-handle">
									<v-icon icon="fa-solid fa-grip"></v-icon>
								</v-col>
								<template v-if="element.tipo === 'descrizione'">
									<documents-descrizione
										v-model="form.elementi[index]"
										:color="color"
										:readonly="readonly"
										@update:modelValue="updatedElement => updateDescrizione(index, updatedElement)"
									/>
								</template>
								<template v-else>
									<documents-product
										v-model="form.elementi[index]"
										:aliquoteIva="aliquoteIva"
										:ricorrenze="ricorrenze"
										:color="color"
										:readonly="readonly"
										@update:modelValue="updatedElement => updateProduct(index, updatedElement)"
									/>
								</template>
								<v-col class="py-0 col-remove text-right">
									<v-btn
										icon="fa-solid fa-trash fa-sm"
										density="compact"
										rounded="sm"
										color="color-delete"
										@click="removeElemento(index)"
										:disabled="readonly"
									/>
								</v-col>
								<v-col class="py-0 mt-2" cols="12" v-if="hasErrors(index)">
								    <ul class="errors-elementi">
										<li v-for="error in handleErrors(index)" :key="error">{{ error }}</li>
									</ul>
								</v-col>
							</v-row>
						</vue-draggable>
					</v-col>
				</v-row>
			</v-card>
		</v-col>
		<documents-spedizione
			v-if="spedizioni.length > 0 && spedizioneActive"
			:metodoPagamentoActive="metodoPagamentoActive"
			:aliquoteIva="aliquoteIva"
			:spedizioni="spedizioni"
			:spedizione="data.spedizione"
			:color="color"
			:errors="errors"
			:readonly="readonly"
			@spedizione="calcolaSpedizione"
			ref="spedizioneDocumentsRef"
		/>
		<documents-metodo-pagamento
			v-if="metodiPagamento.length > 0 && metodoPagamentoActive"
			:spedizioneActive="spedizioneActive"
			:metodiPagamento="metodiPagamento"
			:contiBancari="contiBancari"
			:color="color"
			:errors="errors"
			:readonly="readonly"
			@ready="handleReadyMetodoPagamento"
			ref="metodoPagamentoDocumentsRef"
		/>
		<documents-riepilogo
			:elementi="form.elementi"
			:spedizione="data.spedizione"
			:rate="data.rate"
			:rateActive="rateActive"
			:color="color"
			:readonly="readonly"
			ref="riepilogoDocumentsRef"
		/>
	</v-row>
</template>

<style scoped>
	.col-remove {
		flex: 0 0 4%;
		max-width: 4%;
	}
	.drag-handle {
		cursor: grab;
		flex: 0 0 2%;
		max-width: 2%;
	}
	.errors-elementi {
		font-size: 11px;
		color: rgb(var(--v-theme-error));
		list-style: none;
		text-align: center;
	}
</style>

<script>
import { VueDraggable  } from 'vue-draggable-plus';
import DocumentsRiepilogo from '@/Components/Documents/Body/DocumentsRiepilogo.vue';
import DocumentsSpedizione from '@/Components/Documents/Body/DocumentsSpedizione.vue';
import DocumentsMetodoPagamento from '@/Components/Documents/Body/DocumentsMetodoPagamento.vue';
import DocumentsDescrizione from '@/Components/Documents/Body/DocumentsDescrizione.vue';
import DocumentsProduct from '@/Components/Documents/Body/DocumentsProduct.vue';

export default {
	name: 'DocumentsBody',
	components: {
		VueDraggable,
		DocumentsRiepilogo,
		DocumentsSpedizione,
		DocumentsMetodoPagamento,
		DocumentsDescrizione,
		DocumentsProduct
	},
	props: {
		prodotti: {
			type: Object,
			required: true
		},
		aliquoteIva: {
			type: Object,
			required: true
		},
		spedizioni: {
			type: Array,
			default: []
		},
		metodiPagamento: {
			type: Array,
			default: []
		},
		contiBancari: {
			type: Array,
			default: []
		},
		ricorrenze: {
			type: Array,
			required: true
		},
		color: {
			type: String,
			required: true
		},
		readonly: {
			type: Boolean,
			default: false
		},
		errors: {
			type: Object,
			default: () => ({})
		}
	},
	inject: ['crud'],
	computed: {
		typeProductList() {
			let list = this.trasportoActive === false 
				? ['altro', 'descrizione'] 
				: ['descrizione'];

			return list.concat(Object.keys(this.prodotti));
		},
		itemTitleElementoSelezionato() {
			
			let stringa = this.setup.metodoFiltro === 'Categoria' || this.setup.metodoFiltro === 'Nome' ? 'nome' : 'codice';
			return stringa;
		},
		categoriePerTipo() {
			if (!this.setup.tipoSelezione || !this.prodotti[this.setup.tipoSelezione]) {
				return [];
			}

			let tutteLeCategorie = [];
			this.prodotti[this.setup.tipoSelezione].forEach(prodotto => {
				tutteLeCategorie.push(...prodotto.categories);
			});

			tutteLeCategorie = tutteLeCategorie.filter((categoria, index, self) =>
				index === self.findIndex((c) => (
					c.id === categoria.id
				))
			);

			let categorieParent = tutteLeCategorie.filter(categoria => categoria.parent_id === null);

			return categorieParent;
		},
		sottocategorie() {
			if (!this.setup.categoriaParentSelezionata) return [];

			let sottocategorie = [];
			let tutteLeCategorie = [];
			this.prodotti[this.setup.tipoSelezione].forEach(prodotto => {
				tutteLeCategorie.push(...prodotto.categories);
			});

			sottocategorie = tutteLeCategorie.filter(categoria => categoria.parent_id === this.setup.categoriaParentSelezionata);

			return sottocategorie;
		},
		prodottiFiltrati() {
			let selezioneTipo = this.setup.tipoSelezione;
			if (!selezioneTipo) return [];

			let prodotti = this.prodotti[selezioneTipo] || [];
			if (this.setup.categoriaParentSelezionata) {
				prodotti = prodotti.filter(prodotto =>
					prodotto.categories.some(categoria => categoria.id === this.setup.categoriaParentSelezionata)
				);
			}

			if (this.setup.categoriaFiglioSelezionata) {
				prodotti = prodotti.filter(prodotto =>
					prodotto.categories.some(categoria => categoria.id === this.setup.categoriaFiglioSelezionata)
				);
			}

			return prodotti;
		}
	},
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
				rateActive: false,
				trasportoActive: false,
				spedizioneActive: false,
				metodoPagamentoActive: false,
				setup: {
					tipoSelezione: null,
					metodoFiltro: null,
					categoriaParentSelezionata: null,
					categoriaFiglioSelezionata: null,
					elementoSelezionato: null,
					drag: false
				},
				form: {
					elementi: []
				},
				data: {
					imponibile: 0,
					subtotale: 0,
					iva: 0,
					sconto: 0,
					totale: 0,
					spedizione: {
						prezzo: 0,
						sconto: 0,
						iva: {
							aliquota_iva_id: null,
							aliquota: ''
						},
						spedizione_id: 0
					},
					metodo_pagamento_id: 0,
					conto_bancario_id: 0,
					rate: []
				},
				crudDialog: new this.$crudDialog('create')
			};
		},
		addElemento() {
		    if (this.setup.tipoSelezione !== null) {
				let tipo = this.setup.tipoSelezione;
				let nuovoElemento;
				if(tipo === 'descrizione') {
					nuovoElemento = {
						tipo: tipo,
						descrizione: ''
					};
				} else {
					nuovoElemento  = {
						tipo: tipo,
						nome: '',
						quantita: '',
						prezzo: 0.00,
						unita_misura: 'NR',
						tipo_sconto: '%',
						sconto: 0.00,
						importo: '',
						iva: {
							aliquota_iva_id: this.initializeSelectedIva(),
							aliquota: ''
						},
						ricorrenza: ''
					};
					if(this.setup.elementoSelezionato !== null) {
						const dettagliElemento = this.prodotti[tipo].find(p => p.id === this.setup.elementoSelezionato);
						nuovoElemento.id = dettagliElemento.id;
						nuovoElemento.nome = dettagliElemento.nome;
						nuovoElemento.unita_misura = dettagliElemento.unita_misura;
						nuovoElemento.prezzo = dettagliElemento.prezzo;
						nuovoElemento.iva.aliquota_iva_id = dettagliElemento.aliquota_iva_id;
						nuovoElemento.iva.aliquota = dettagliElemento.aliquota_iva.aliquota;
						nuovoElemento.ricorrenza = dettagliElemento.ricorrenza;
					}	
				}
				this.form.elementi.push(nuovoElemento);
				const index = this.form.elementi.length - 1;
				if(tipo != 'descrizione') this.updateAliquotaIva(this.form.elementi[index]);
			}				
		},
		removeElemento(index) {
			this.form.elementi.splice(index, 1);
		},
		filtroCambiato() {
			this.setup.categoriaParentSelezionata = null;
			this.setup.categoriaFiglioSelezionata = null;
			this.setup.elementoSelezionato = null;
		},
		tipoSelezioneCambiata() {
			this.setup.categoriaParentSelezionata = null;
			this.setup.categoriaParentSelezionata = null;
			this.setup.categoriaFiglioSelezionata = null;
			this.setup.elementoSelezionato = null;
			this.setup.metodoFiltro = null;
		},
		selezionaCategoriaParent(categoriaId) {
			this.setup.categoriaParentSelezionata = categoriaId;
			this.setup.categoriaFiglioSelezionata = null;
		},
		updateProduct(index, updatedElement) {
			const elemento = this.form.elementi[index] = updatedElement;
			this.calcolaImporto(elemento);
			this.updateAliquotaIva(elemento);
		},
		updateDescrizione(index, updatedElement) {
			const elemento = this.form.elementi[index] = updatedElement;
		},
		calcolaImporto(elemento) {
			if (elemento) {
				const quantita = parseFloat(elemento.quantita) || 0;
				const prezzo = parseFloat(elemento.prezzo) || 0;
				let sconto = 0;

				if (elemento.tipo_sconto === '%') {
					sconto = parseFloat(elemento.sconto) / 100 || 0;
				} else {
					sconto = parseFloat(elemento.sconto) || 0;
				}

				const subtotale = quantita * prezzo;
				let importo;

				if (elemento.tipo_sconto === '%') {
					importo = subtotale - (subtotale * sconto);
				} else {
					importo = subtotale - sconto;
				}

				elemento.importo = importo.toFixed(2);
			}
		},
		updateAliquotaIva(elemento) {
			const iva = this.aliquoteIva.find(item => item.id === elemento.iva.aliquota_iva_id);
			if(iva) {
				elemento.iva.aliquota = iva.aliquota;
			}
		},
		calcolaSpedizione(spedizione) {
			this.data.spedizione = spedizione;
		},
		initializeSelectedIva() {
			const predefinitaIva = this.aliquoteIva.find(iva => iva.predefinita === 1);
			if (predefinitaIva) {
				return predefinitaIva.id;
			}
			return null;
		},
		hasErrors(index) {
			const errorKeys = [
				`elementi.${index}.nome`,
				`elementi.${index}.quantita`,
				`elementi.${index}.tipo`,
				`elementi.${index}.prezzo`,
				`elementi.${index}.descrizione`
			];
			return errorKeys.some(key => this.errors && this.errors[key]);
		},
		handleErrors(index) {
			let errors = [];
			const fields = ['nome', 'quantita', 'tipo', 'prezzo', 'descrizione'];
			fields.forEach(field => {
				let errorKey = `elementi.${index}.${field}`;
				if (this.errors && this.errors[errorKey]) {
					errors.push(this.errors[errorKey][0]);
				}
			});
			return errors;
		},
		handleReadyMetodoPagamento() {
			if(this.crud == 'edit' || this.crud == 'show') {
				this.$refs.metodoPagamentoDocumentsRef.form.metodo_pagamento_id = this.data.metodo_pagamento_id;
				this.$refs.metodoPagamentoDocumentsRef.form.conto_bancario_id = this.data.conto_bancario_id;
			}
		},
		getAliquotaIvaPredefinita() {
			return this.aliquoteIva.find(iva => iva.predefinita === 1);
		},
		getForm() {
			let combinedData = null;
			
			if(this.spedizioneActive === true && this.$refs.spedizioneDocumentsRef && this.$refs.spedizioneDocumentsRef.form) {
				combinedData = {
					...combinedData,
					...this.$refs.spedizioneDocumentsRef.getForm()
				}
			}
			
			if(this.metodoPagamentoActive === true && this.$refs.metodoPagamentoDocumentsRef && this.$refs.metodoPagamentoDocumentsRef.form) {
				combinedData = {
					...combinedData,
					...this.$refs.metodoPagamentoDocumentsRef.getForm()
				}
			}
			
			if(this.rateActive === true && this.$refs.riepilogoDocumentsRef && this.$refs.riepilogoDocumentsRef.form) {
				combinedData = {
					...combinedData,
					...this.$refs.riepilogoDocumentsRef.getForm()
				}
			}

			if(combinedData !== null) {
				return {
					...this.form,
					...combinedData
				};
			} else {
				return this.form;
			}
		}
	}
}
</script>