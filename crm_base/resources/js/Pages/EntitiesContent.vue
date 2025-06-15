<template>
	<v-row>
		<v-col
			:cols="data.activity === false || crud === 'create' ? 12 : 9"
			class="py-0"
		>
			<v-row class="mt-0">
				<v-col
					cols="4"
					class="py-0"
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
									<header-box title="Anagrafica" icon="fa-solid fa-address-card" />
								</v-col>
							</v-row>
						</v-toolbar>
						<v-row
							class="px-4 mt-6 pb-3"
						>
							<v-col
							  cols="6"
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
							  cols="6"
							  class="py-0"
							>
								<v-select
									v-model="form.profilo"
									:items="data.profili"
									label="Profilo"
									:color="crudDialog.color"
									:readonly="readonly"
									:error-messages="crudDialog.errors && crudDialog.errors.profilo ? crudDialog.errors.profilo : ''"
								></v-select>
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
						</v-row>
					</v-card>
				</v-col>
				<v-col
				  cols="4"
				  class="py-0"
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
									<header-box title="Dati fiscali" icon="fa-solid fa-file-invoice" />
								</v-col>
							</v-row>
						</v-toolbar>
						<v-row
							class="px-4 mt-6 pb-3"
						>
							<v-col
							  cols="6"
							  class="py-0"
							  v-if="showDatiFiscaliAzienda"
							>
								<v-text-field
									v-model="form.partita_iva"
									label="Partiva IVA"
									:color="crudDialog.color"
									:readonly="readonly"
									:error-messages="crudDialog.errors && crudDialog.errors.partita_iva ? crudDialog.errors.partita_iva : ''"
								></v-text-field>
							</v-col>
							<v-col
							  :cols="showDatiFiscaliAzienda ? 6 : 12"
							  class="py-0"
							>
								<v-text-field
									v-model="form.codice_fiscale"
									label="Codice Fiscale"
									:color="crudDialog.color"
									:readonly="readonly"
									:error-messages="crudDialog.errors && crudDialog.errors.codice_fiscale ? crudDialog.errors.codice_fiscale : ''"
								></v-text-field>
							</v-col>
							<v-col
							  cols="6"
							  class="py-0"
							  v-if="showDatiFiscaliAzienda"
							>
								<v-text-field
									v-model="form.cuu"
									label="CUU"
									:color="crudDialog.color"
									:readonly="readonly"
									:error-messages="crudDialog.errors && crudDialog.errors.cuu ? crudDialog.errors.cuu : ''"
								></v-text-field>
							</v-col>
							<v-col
							  cols="6"
							  class="py-0"
							  v-if="showDatiFiscaliAzienda"
							>
								<v-text-field
									v-model="form.pec"
									label="Pec"
									:color="crudDialog.color"
									:readonly="readonly"
									:error-messages="crudDialog.errors && crudDialog.errors.pec ? crudDialog.errors.pec : ''"
								></v-text-field>
							</v-col>
						</v-row>
					</v-card>
				</v-col>
				<v-col
					cols="4"
					class="py-0"
				>
					<v-card
						elevation="4"
						variant="flat"
						height="100%"
					>
						<v-toolbar
							class="px-3"
						>
							<v-row class="align-center bg-grey-lighten-3">
								<v-col cols="12">
									<header-box title="Contatti" icon="fa-solid fa-envelopes-bulk" />
								</v-col>
							</v-row>
						</v-toolbar>
						<v-row
							class="px-4 mt-6 pb-3"
						>
							<v-col
							  cols="12"
							  class="py-0"
							>
								<v-text-field
									v-model="form.email"
									label="E-mail"
									:color="crudDialog.color"
									:readonly="readonly"
									:error-messages="crudDialog.errors && crudDialog.errors.email ? crudDialog.errors.email : ''"
								></v-text-field>
							</v-col>
							<v-col
							  cols="12"
							  class="py-0"
							>
								<v-text-field
									v-model="form.telefono"
									label="Telefono"
									:color="crudDialog.color"
									:readonly="readonly"
									:error-messages="crudDialog.errors && crudDialog.errors.telefono ? crudDialog.errors.telefono : ''"
								></v-text-field>
							</v-col>
						</v-row>
					</v-card>
				</v-col>
				<v-col
					cols="12"
					class="py-0 mt-9"
					v-if="crud != 'create'"
				>
					<v-row>
						<v-col
							cols="6"
							class="py-0"
						>
							<indirizzi :records="data.indirizzi" :elementId="form.id" />
						</v-col>
						<v-col
							cols="6"
							class="py-0"
						>
							<referenti :records="data.referenti" :elementId="form.id" />
						</v-col>
					</v-row>
				</v-col>
				<v-col
					cols="12"
					class="py-0 mt-6"
					v-if="crud == 'create'"
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
									<header-box title="Indirizzo" icon="fa-solid fa-location-dot" />
								</v-col>
							</v-row>
						</v-toolbar>
						<v-row
							class="px-4 mt-6 pb-3"
						>
							<v-col
							  cols="12"
							  class="py-0 pt-3"
							>
								<indirizzi-content
									:dataElements="data.indirizzo"
									:crudDialog="data.indirizzoObject"
									crud="create"
									:readonly="readonly"
									ref="indirizzoContentRef"
								/>
							</v-col>		
						</v-row>
					</v-card>
				</v-col>
				<v-col
				  cols="12"
				  class="py-0 mb-6"
				  :class="crud !== 'create' ? 'mt-9' : 'mt-6'"
				>
					<v-card
						elevation="4"
						variant="flat"
						height="100%"
					>
						<v-toolbar
							class="px-3"
						>
							<v-row class="align-center bg-grey-lighten-3">
								<v-col cols="12">
									<header-box title="Informazioni aggiuntive" icon="fa-solid fa-note-sticky" />
								</v-col>
							</v-row>
						</v-toolbar>
						<v-row
							class="px-4 mt-6 pb-3"
						>
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
					</v-card>
				</v-col>
			</v-row>
		</v-col>
		<v-col
			cols="3"
			class="py-0"
			v-if="data.activity === true"
		>
			<attivita :records="data.attivita" :elementId="form.id" />
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
				:entityId="form.id"
				:entityType="data.type"
				:key="'document-group-' + form.id"
			/>
		</v-col>
	</v-row>
</template>

<script>
import Indirizzi from '@/Pages/indirizzi/IndirizziIndex.vue';
import Referenti from '@/Pages/referenti/ReferentiIndex.vue';
import Attivita from '@/Pages/attivita/AttivitaIndex.vue';
import IndirizziContent from '@/Pages/indirizzi/IndirizziContent.vue';
import DocumentGroup from '@/Components/DocumentGroup.vue';

export default {
	name: 'EntitiesContent',
	components: {
		Indirizzi,
		Referenti,
		Attivita,
		IndirizziContent,
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
	created() {
		this.getData();
	},
	data() {
		return this.initialState();
	},
	computed: {
		showDatiFiscaliAzienda() {
			return this.form.profilo !== 'Privato';
		}
	},
	methods: {
		initialState() {
			return {
				form: {
					id: null,
					codice: null,
					profilo: null,
					nome: null,
					partita_iva: null,
					codice_fiscale: null,
					email: null,
					telefono: null,
					pec: null,
					cuu: null,
					note: null,
					indirizzo: null
				},
				data: {
					type: null,
					profili: [],
					indirizzi: {},
					referenti: {},
					attivita: {},
					documents: {},
					indirizzo: null,
					indirizzoObject: {
						color: this.crudDialog.color,
						errors: null
					},
					activity: false
				}
			};
		},
		getData() {
			if(this.dataElements.resource) {
				this.form.id = this.dataElements.resource.id;
				this.form.codice = this.dataElements.resource.codice;
				this.form.profilo = this.dataElements.resource.profilo;
				this.form.nome = this.dataElements.resource.nome;
				this.form.partita_iva = this.dataElements.resource.partita_iva;
				this.form.codice_fiscale = this.dataElements.resource.codice_fiscale;
				this.form.email = this.dataElements.resource.email;
				this.form.telefono = this.dataElements.resource.telefono;
				this.form.pec = this.dataElements.resource.pec;
				this.form.cuu = this.dataElements.resource.cuu;
				this.form.note = this.dataElements.resource.note;
				
				if(this.crud == 'edit' || this.crud == 'show') {
					this.data.indirizzi = this.dataElements.resource.indirizzi;
					this.data.referenti = this.dataElements.resource.referenti;
					this.data.attivita = this.dataElements.resource.attivita;
					this.data.activity = this.dataElements.resource.activity;
				}
				
				if(this.crud == 'show') {
					this.data.type = this.dataElements.resource.type;
					this.data.documents = this.dataElements.resource.documents;
				}
			}
			
			if (this.dataElements.profili) {
				this.data.profili = this.dataElements.profili;
				if(this.crud == 'create') this.form.profilo = 'Azienda';
			}

			if (this.dataElements.indirizzo) {
				this.data.indirizzo = this.dataElements.indirizzo;
			}
		},
		getForm() {
			if(this.crud == 'create') this.form.indirizzo = this.$refs.indirizzoContentRef.getForm();
			return this.form;
		}
	}
}
</script>
