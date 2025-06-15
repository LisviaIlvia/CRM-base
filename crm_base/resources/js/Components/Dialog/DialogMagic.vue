<template>
	<v-dialog
		v-model="setup.dialog"
		width="50%"
		@click:outside="onOutsideClick"
	>
		<v-container class="pt-5 overflow-dialog">
			<v-sheet rounded>
				<v-container class="px-0">
					<v-row align="center" class="mt-n10">
						<v-col cols="auto">
							<v-avatar
							  rounded
							  :color="crudDialog.color"
							  elevation="5"
							  class="ml-4 px-10 py-10 elevation-4 dialog-avatar"
							>
								<v-icon color="surface" size="x-large" :icon="crudDialog.icon"></v-icon>
							</v-avatar>
						</v-col>
						<v-col class="mt-6">
							<h3 v-html="dialogCustomTitle ?? text.title"></h3>
						</v-col>
						<v-col cols="auto" class="text-right mt-n3">
							<v-btn color="secondary" icon="fa-solid fa-circle-info" variant="text">
							</v-btn>
							<v-tooltip activator="parent" location="right">
								{{text.infoTooltip}}
							</v-tooltip>
						</v-col>
					</v-row>
					<v-divider class="mt-n5"></v-divider>
				</v-container>
				<v-container class="px-10">
					<v-row>
						<v-col cols="12">
							<h4 class="ml-2 mb-2">Documenti discendenti collegati</h4>
							<v-sheet
								color="grey-lighten-4"
								class="py-2"
								rounded
								
							>
								<v-list-item
									v-if="data.children.length > 0"
									v-for="child in data.children"
									:key="child.data.id"
									:subtitle="`Nr ${child.numero} del ${formatDate(child.data)}`"
									:title="child.type"
								>
									<template v-slot:prepend>
										<v-btn
											color="color-show"
											icon="fa-solid fa-up-right-from-square"
											variant="text"
											@click="visitLink(child.link)"
										></v-btn>
									</template>					
								</v-list-item>
								<p class="px-3 py-1" v-else>Nessun documento collegato</p>
							</v-sheet>
						</v-col>
					</v-row>
					<v-row>
						<v-col cols="12">
							<h4 class="ml-2 mb-1">Elementi prodotto privi di collegamenti discendenti</h4>
							<v-sheet
								color="grey-lighten-4"
								rounded
								
							>
								<v-list-item
									v-if="data.missingProducts.length > 0"
									class="py-2"
									v-for="product in data.missingProducts"
									:key="product.id"
									:subtitle="`Quantità restante ${product.quantita}`"
									:title="product.nome"
								>		
									<template v-slot:prepend>
										<v-list-item-action start>
											<v-checkbox-btn
												v-model="data.missingProductsSelected"
												:value="product.id"
											></v-checkbox-btn>
										</v-list-item-action>
									</template>
									<template v-slot:append>
										<v-list-item-action start>
											<v-number-input
												v-model="product.selectedQuantity"
												class="me-2"
												:min="1"
												:max="product.quantita"
												variant="outlined"
												control-variant="split"
												hide-details
												:disabled="!data.missingProductsSelected.includes(product.id)"
												@update:modelValue="updateImporto(product)"
											></v-number-input>
										</v-list-item-action>	
										<v-list-item-action start>
											<number-decimal
												v-model="product.prezzo"
												class="me-4"
												label="Prezzo"
												:color="crudDialog.color"
												prefix="€"
												:hideDetails="true"
												@update:modelValue="value => { product.selectedPrezzo = value; updateImporto(product);}"
												:disabled="!data.missingProductsSelected.includes(product.id)"
											/>
										</v-list-item-action>
										<v-list-item-action start>
											<number-decimal
												v-model="product.sconto"
												class="me-4"
												label="Sconto"
												:color="crudDialog.color"
												:prefix="product.tipo_sconto"
												:hideDetails="true"
												:disabled="!data.missingProductsSelected.includes(product.id)"
											/>
										</v-list-item-action>
										<v-list-item-action start>
											<number-decimal
												v-model="product.importo"
												label="Importo"
												:color="crudDialog.color"
												prefix="€"
												:hideDetails="true"
												:disabled="!data.missingProductsSelected.includes(product.id)"
											/>
										</v-list-item-action>
									</template>
								</v-list-item>
								<p class="pa-3" v-else>Nessun elemento disponibile</p>
							</v-sheet>
						</v-col>
					</v-row>
					<v-row>
						<v-col cols="12">
							<h4 class="ml-2 mb-1">Elementi altro privi di collegamenti discendenti</h4>
							<v-sheet
								color="grey-lighten-4"
								rounded
								
							>
								<v-list-item
									v-if="data.missingAltro.length > 0"
									class="py-2"
									v-for="altro in data.missingAltro"
									:key="altro.id"
									:subtitle="`Quantità restante ${altro.quantita}`"
									:title="altro.nome"
								>			
									<template v-slot:prepend>
										<v-list-item-action start>
											<v-checkbox-btn
												v-model="data.missingAltroSelected"
												:value="altro.id"
											></v-checkbox-btn>
										</v-list-item-action>	
									</template>
									<template v-slot:append>
										<v-list-item-action start>
											<v-number-input
												v-model="altro.selectedQuantity"
												:min="1"
												:max="altro.quantita"
												variant="outlined"
												color="surface"
												control-variant="split"
												hide-details
												:disabled="!data.missingAltroSelected.includes(altro.id)"
											></v-number-input>
										</v-list-item-action>	
									</template>
								</v-list-item>
								<p class="pa-3" v-else>Nessun elemento disponibile</p>
							</v-sheet>
						</v-col>
					</v-row>
				</v-container>
				<v-form @submit.prevent="sendForm">
					<v-container class="px-10">
						<v-select
							v-model="form.document_type"
							:items="filteredTypesDocument"
							item-title="title"
							item-value="value"
							label="Seleziona Tipo"
							:color="crudDialog.color"
							:disabled="data.missingProducts.length === 0 && data.missingAltro.length === 0"
							:error-messages="crudDialog.errors && crudDialog.errors.document_type ? crudDialog.errors.document_type : ''"
						></v-select>
					</v-container>
					<v-container :class="crudDialog.bg + ' rounded-be rounded-bs'">
						<v-row>
							<v-spacer></v-spacer>
							<v-col cols="auto" class="text-right">
								<v-btn color="secondary" variant="outlined" @click="closeDialog">Chiudi</v-btn>
							</v-col>
							<v-col cols="auto" class="text-right">
								<v-btn
									type="submit" 
									:color="crudDialog.color"
									elevation="4"
									:loading="crudDialog.loadingSubmit"
									:disabled="!data.missingAltroSelected.length > 0 && !data.missingProductsSelected.length > 0"
								>Crea</v-btn>
							</v-col>
						</v-row>
					</v-container>
				</v-form>
			</v-sheet>
		</v-container>
	</v-dialog>
</template>

<style scoped>
	.dialog-avatar {
		z-index: 10;
	}
	.overflow-dialog {
		overflow-y: auto;
	}
	::-webkit-scrollbar {
		width: 12px;
	}
	::-webkit-scrollbar-track {
		background: #F3E5F5;
		border-radius: 4px;
	}
	::-webkit-scrollbar-thumb {
		background-color: #E1BEE7;
		border-radius: 4px;
	}
	::-webkit-scrollbar-thumb:hover {
		background-color: #CE93D8;
		border-radius: 4px;
	}
</style>

<script>
import { useYearStore } from '@/store/yearStore';

export default {
	name: 'DialogMagic',
	props: {
		dialogTitle: {
			type: String,
			required: true
		},
		dialogType: {
			type: String,
			required: true
		},
		typesRelation: {
			type: Array,
			required: true
		},
		dialogCustomTitle: {
			type: String,
			default: null
		}
	},
	emits: ['dialog-magic-opened', 'show-notification-magic', 'update-relation'],
	data() {
		return this.initialState();
	},
	computed: {
		filteredTypesDocument() {
			if (this.data.children && this.data.children.length > 0) {
				const existingTypes = this.data.children.map(child => child.type);
				return this.typesRelation.filter(typeRelation => existingTypes.includes(typeRelation.type));
			}
			return this.typesRelation;
		}
	},
	methods: {
		initialState() {
			return {
				setup: {
					dialog: false,
				},
				form: {
					document_type: null
				},
				data: {
					parents: null,
					children: null,
					missingAltro: [],
					missingProducts: [],
					missingAltroSelected: [],
					missingProductsSelected: []
				},
				text: this.$tooltipDialog.magic(this.dialogTitle, this.dialogType),
				crudDialog: new this.$crudDialog('magic')
			};
		},
		resetState() {
			this.setup.dialog = false;
			setTimeout(() => {
				Object.assign(this.$data, this.initialState());
			}, 200);
		},
		onOutsideClick(event) {
			if (event.target.className.includes('v-dialog--active')) {
				return;
			}
			this.resetState();
		},
		openDialog(urlOpen, urlForm) {
			this.crudDialog.urlOpen = urlOpen;
			this.crudDialog.open(urlForm,  (data) => {
				console.log(data);
				this.data.parents = data.parents;
				this.data.children = data.children;
				this.data.missingAltro = data.missingAltro;
				this.data.missingAltro = data.missingAltro.map(item => ({
					...item,
					selectedQuantity: item.quantita
				}));
				this.data.missingProducts = data.missingProducts.map(item => ({
					...item,
					selectedQuantity: item.quantita
				}));
				this.setup.dialog = true;
				this.$emit('dialog-magic-opened');
			});
		},	
		sendForm(options = {}) {
			const {
				field = null,
				nameField = null, 
				callbackError = null,
				containsFile = false
			} = options;
			this.form.year = useYearStore().selectedYear;
			this.form.altro = this.data.missingAltro.filter(item => this.data.missingAltroSelected.includes(item.id));
			this.form.prodotti = this.data.missingProducts.filter(item => this.data.missingProductsSelected.includes(item.id));
			
			this.crudDialog.send(this.form, (data) => {
				this.resetState();
				this.$emit('update-relation', data.relation);
				const typeRelation = this.typesRelation.find(type => type.value === data.document_type);
				this.$emit('show-notification-magic', {
					type: 'success',
					text: `${typeRelation.title} creato con successo.`,
				});
			}, {
				'callbackError': callbackError,
				'containsFile': containsFile
			});
		},
		formatDate(date) {
			const [year, month, day] = date.split('-');
			return `${day}/${month}/${year}`;
		},
		updateSelectedProducts(id, isSelected) {
			const index = this.data.missingProductsSelected.indexOf(id);
			if (isSelected && index === -1) {
				this.data.missingProductsSelected.push(id);
			} else if (!isSelected && index !== -1) {
				this.data.missingProductsSelected.splice(index, 1);
			}
		},
		updateSelectedAltro(id, isSelected) {
			const index = this.data.missingAltroSelected.indexOf(id);
			if (isSelected && index === -1) {
				this.data.missingAltroSelected.push(id);
			} else if (!isSelected && index !== -1) {
				this.data.missingAltroSelected.splice(index, 1);
			}
		},
		updateImporto(elemento) {
			if (elemento) {
				console.log(elemento);
				if(!elemento.selectedPrezzo) elemento.selectedPrezzo = elemento.prezzo;

				const quantita = parseFloat(elemento.selectedQuantity) || 0;
				const prezzo = parseFloat(elemento.selectedPrezzo) || 0;
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
				console.log(quantita);
				console.log(prezzo);
				console.log(elemento);
				elemento.importo = importo.toFixed(2);
			}
		},
		visitLink(link) {
			window.open(link, '_blank');
		},
		closeDialog() {
			this.resetState();
		}
	}
}
</script>