<template>
	<documents-header
		:intestatari="data.intestatari"
		:tipiIntestatari="data.tipiIntestatari"
		:numero="data.numero"
		:previousDocDate="data.previousDocDate"
		:nextDocDate="data.nextDocDate"
		:interlocutore="data.interlocutore"
		:entityId="elementId"
		:entityType="elementCustom"
		:color="crudDialog.color"
		:errors="crudDialog.errors"
		:readonly="readonly"
		@ready="handleReadyHeader"
		ref="headerDocumentRef"
	/>
	<documents-body
		:prodotti="data.prodotti"
		:aliquoteIva="data.aliquoteIva"
		:spedizioni="data.spedizioni"
		:metodiPagamento="data.metodiPagamento"
		:contiBancari="data.contiBancari"
		:ricorrenze="data.ricorrenze"
		:color="crudDialog.color"
		:errors="crudDialog.errors"
		:readonly="readonly"
		@ready="handleReadyBody"
		ref="bodyDocumentRef"
	/>
	<documents-footer
		:color="crudDialog.color"
		:errors="crudDialog.errors"
		@ready="handleReadyFooter"
		:readonly="readonly"
		ref="footerDocumentRef"
	/>

</template>

<script>
export default {
	name: 'DocumentsContent',
	props: {
		crudDialog: {
			type: Object,
			required: true
		},
		dataElements: {
			type: Object,
			required: true
		},
		elementId: {
			type: Number,
			default: null
		},
		elementCustom: {
			type: String,
			default: null
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
	provide() {
		return {
			crud: this.crud
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
					nameField: 'numero',
					containsFile: true
				},
				data: {
					aliquoteIva: [],
					tipiIntestatari: [],
					intestatari: {},
					numero: '',
					previousDocDate: '',
					nextDocDate: '',
					interlocutore: '',
					prodotti: [],
					metodiPagamento: [],
					document: null,
					contiBancari: [],
					spedizioni: [],
					ricorrenze: [],
					allegati: []
				}
			};
		},
		getData() {
			this.data.aliquoteIva = this.dataElements.aliquote_iva;
			this.data.tipiIntestatari = this.dataElements.tipi_intestatari;
			this.data.prodotti = this.dataElements.prodotti;
			this.data.metodiPagamento = this.dataElements.metodi_pagamento;
			this.data.contiBancari = this.dataElements.conti_bancari;
			this.data.spedizioni = this.dataElements.spedizioni;
			this.data.ricorrenze = this.dataElements.ricorrenze;
			if(this.dataElements.resource) {
				this.data.previousDocDate = this.dataElements.resource.previous_doc_date;
				this.data.interlocutore = this.dataElements.resource.interlocutore;
				this.data.intestatari = this.dataElements.resource.intestatari;
				if(this.crud == 'create') {
					this.data.numero = this.dataElements.resource.numero;
				}
				if(this.crud == 'edit' || this.crud == 'show') {
					this.data.nextDocDate = this.dataElements.resource.next_doc_date;
				}
			}
		},
		getForm() {
			const headerFormData = this.$refs.headerDocumentRef.getForm();
			const bodyFormData = this.$refs.bodyDocumentRef.getForm();
			const footerFormData = this.$refs.footerDocumentRef.getForm();
			const combinedFormData = { ...this.form, ...headerFormData, ...bodyFormData, ...footerFormData };
			return combinedFormData;
		},
		handleReadyHeader() {
			const headerDoc = this.$refs.headerDocumentRef;
			headerDoc.trasportoActive = this.dataElements.trasporto_active;
			const entrata = this.dataElements.entrata;
			if(entrata) headerDoc.numeroBlock = false;
			headerDoc.data.stati = this.dataElements.stati;
			if(this.dataElements.resource) {
				if(this.crud == 'edit' || this.crud == 'show') {
					headerDoc.form.numero = this.dataElements.resource.numero;
					headerDoc.form.data = new Date(this.dataElements.resource.document.data);
					headerDoc.form.entity_type = this.dataElements.resource.document.entity_type;
					headerDoc.form.entity_id = this.dataElements.resource.document.entity_id;
					headerDoc.form.stato = this.dataElements.resource.stato;
					headerDoc.data.indirizzo = this.dataElements.resource.document.indirizzo;
				}
				if(this.crud == 'show' && entrata === false) {
					headerDoc.data.parents = this.dataElements.resource.relation.parents;
					headerDoc.data.children = this.dataElements.resource.relation.children;
				}
			}
		},
		handleReadyBody() {
			const bodyDoc = this.$refs.bodyDocumentRef;
			bodyDoc.trasportoActive = this.dataElements.trasporto_active;
			bodyDoc.spedizioneActive = this.dataElements.spedizione_active;
			bodyDoc.metodoPagamentoActive = this.dataElements.metodo_pagamento_active;
			bodyDoc.rateActive = this.dataElements.rate_active;
			if(this.dataElements.resource) {
				if(this.crud == 'edit' || this.crud == 'show') {
					bodyDoc.form.elementi = this.dataElements.resource.document.elementi;
					bodyDoc.data.spedizione = this.dataElements.resource.document.spedizione;
					bodyDoc.data.metodo_pagamento_id = this.dataElements.resource.document.metodo_pagamento_id;
					bodyDoc.data.conto_bancario_id = this.dataElements.resource.document.conto_bancario_id;
					let rate = this.dataElements.resource.document.rate;
					rate.forEach((rata) => {
						if (rata.data !== null) {
							rata.data = new Date(rata.data);
						}
					});
					bodyDoc.data.rate = rate;
				}
			}
		},
		handleReadyFooter() {
			const footerDoc = this.$refs.footerDocumentRef;
			if(this.dataElements.entrata === false) footerDoc.trasportoActive = this.dataElements.trasporto_active;
			if(this.dataElements.resource) {
				if(this.crud == 'edit' || this.crud == 'show') {
					const footerDoc = this.$refs.footerDocumentRef;
					footerDoc.form.note = this.dataElements.resource.document.note;
					footerDoc.data.allegati = this.dataElements.resource.document.allegati;
					footerDoc.data.trasporto = this.dataElements.resource.document.trasporto;
				}
			}
		}
	}
}
</script>