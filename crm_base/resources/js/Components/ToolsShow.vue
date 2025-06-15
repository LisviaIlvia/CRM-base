<template>
	<v-col v-if="ddtUscitaUrl !== ''"
		cols="auto" class="text-right mt-n13 mr-4  rounded-b bg-grey-lighten-5 elevation-3"
	>
		<p 
			class="text-center text-title-tools font-weight-bold"
		>DOCUMENTI</p>
		<v-divider
			class="mb-2"
		></v-divider>
		<v-btn 
			v-if="ddtUscitaUrl !== ''"
			class="icon-doc mr-3"
			variant="flat"
			density="comfortable"
			icon="custom:ddt-uscita"
			rounded="sm"
			color="color-doc"
			title="DDT Uscita"
			@click="openLink(ddtUscitaUrl)"
		/>
		<v-btn 
			v-if="ddtEntrataUrl !== ''"
			class="icon-doc mr-3"
			variant="flat"
			density="comfortable"
			icon="custom:ddt-entrata"
			rounded="sm"
			color="color-doc"
			title="DDT Entrata"
			@click="openLink(ddtEntrataUrl)"
		/>
		<v-btn 
			v-if="ordAcquistoUrl !== ''"
			class="icon-doc mr-3"
			variant="flat"
			density="comfortable"
			icon="custom:ordini-acquisto"
			rounded="sm"
			color="color-doc"
			title="Ordini Acquisto"
			@click="openLink(ordAcquistoUrl)"
		/>
		<v-btn 
			v-if="ordVenditaUrl !== ''"
			class="icon-doc mr-3"
			variant="flat"
			density="comfortable"
			icon="custom:ordini-vendita"
			rounded="sm"
			color="color-doc"
			title="Ordini Vendita"
			@click="openLink(ordVenditaUrl)"
		/>
		<v-btn 
			v-if="ftAcquistoUrl !== ''"
			class="icon-doc"
			variant="flat"
			density="comfortable"
			icon="custom:fatture-acquisto"
			rounded="sm"
			color="color-doc"
			title="Fatture Acquisto"
			@click="openLink(ftAcquistoUrl)"
		/>
		<v-btn 
			v-if="ncPassiveUrl !== ''"
			class="icon-doc"
			variant="flat"
			density="comfortable"
			icon="custom:note-credito-passive"
			rounded="sm"
			color="color-doc"
			title="Note di Credito"
			@click="openLink(ncPassiveUrl)"
		/>
		<v-btn 
			v-if="ftProformaUrl !== ''"
			class="icon-doc mr-3"
			variant="flat"
			density="comfortable"
			icon="custom:fatture-vendita"
			rounded="sm"
			color="color-doc"
			title="Fatture Proforma"
			@click="openLink(ftProformaUrl)"
		/>
		<v-btn 
			v-if="ftVenditaUrl !== ''"
			class="icon-doc mr-3"
			variant="flat"
			density="comfortable"
			icon="custom:fatture-vendita"
			rounded="sm"
			color="color-doc"
			title="Fatture Vendita"
			@click="openLink(ftVenditaUrl)"
		/>
		<v-btn 
			v-if="rcVenditaUrl !== ''"
			class="icon-doc mr-3"
			variant="flat"
			density="comfortable"
			icon="custom:ricevute-vendita"
			rounded="sm"
			color="color-doc"
			title="Ricevute Vendita"
			@click="openLink(rcVenditaUrl)"
		/>
		<v-btn 
			v-if="sagOmgUrl !== ''"
			class="icon-doc mr-3"
			variant="flat"
			density="comfortable"
			icon="custom:saggi-omaggio"
			rounded="sm"
			color="color-doc"
			title="Saggi Omaggio"
			@click="openLink(sagOmgUrl)"
		/>
		<v-btn 
			v-if="ncAttiveUrl !== ''"
			class="icon-doc"
			variant="flat"
			density="comfortable"
			icon="custom:note-credito-attive"
			rounded="sm"
			color="color-doc"
			title="Note di Credito"
			@click="openLink(ncAttiveUrl)"
		/>
		<v-btn 
			v-if="docMacUrl !== ''"
			class="icon-doc"
			variant="flat"
			density="comfortable"
			icon="custom:documenti-macero"
			rounded="sm"
			color="color-doc"
			title="Documenti Macero"
			@click="openLink(docMacUrl)"
		/>
	</v-col>
	<v-col v-if="pdfUrl !== null || magazzinoUrl !== null" cols="auto" class="text-right mt-n13 mr-4 rounded-b bg-grey-lighten-5 elevation-3">
		<p 
			class="text-center text-title-tools font-weight-bold"
		>AZIONI</p>
		<v-divider
			class="mb-2"
		></v-divider>
		<div class="text-center">
			<v-btn 
				v-if="magicUrl && magicUrl.magic !== null && magicUrl.magicsync !== null"
				variant="flat"
				density="comfortable"
				icon="fa-solid fa-wand-magic-sparkles"
				class="mr-3"
				rounded="sm"
				color="color-magic"
				title="Crea documenti"
				:disabled="magicUrl.magic === false && magicUrl.magicsync === false"
				:loading="setup.loadingMagic"
				@click="openDialogMagic"
			/>
			<v-btn 
				v-if="emailSend === true"
				variant="flat"
				density="comfortable"
				icon="fa-solid fa-envelope"
				class="mr-3"
				rounded="sm"
				color="color-email"
				title="Invia e-mail"
				@click=""
			/>
			<v-btn 
				v-if="pdfUrl !== null"
				variant="flat"
				density="comfortable"
				icon="fa-solid fa-file-pdf"
				rounded="sm"
				color="color-pdf"
				title="Crea PDF"
				@click="openLink(pdfUrl)"
			/>
			<v-btn 
				v-if="magazzinoUrl !== null"
				variant="flat"
				class="mx-auto"
				density="comfortable"
				icon="fa-solid fa-warehouse"
				rounded="sm"
				color="color-mag"
				title="Visualizza Magazzino"
				@click="openLink(magazzinoUrl)"
			/>
			<v-btn 
				v-if="emailSend === true"
				variant="flat"
				density="comfortable"
				icon="fa-solid fa-envelope"
				class="mr-3"
				rounded="sm"
				color="color-email"
				title="Invia e-mail"
				@click=""
			/>
		</div>
	</v-col>
	<dialog-magic
		v-if="magicUrl && magicUrl.magic !== '' && magicUrl.magicsync !== ''"
		ref="dialogMagicRef"
		:dialogTitle="dialogTitle"
		:dialogType="dialogType"
		:typesRelation="typesRelation"
		@dialog-magic-opened="setup.loadingMagic = false"
		@show-notification-magic="showNotificationMagic"
		@update-relation="updateRelation"
	/>
</template>

<style>
	.icon-doc .v-icon svg path {
		fill: #fff;
	}
	.text-title-tools {
		font-size: 0.775rem !important;
		line-height: 1;
		padding: 2px;
		margin-top: 6px;
	}
</style>

<script>
export default {
	name: 'ToolsShow',
	props: {
		dialogTitle: {
			type: String,
			default: ''
		},
		dialogType: {
			type: String,
			default: ''
		},
		typesRelation: {
			type: [Array],
			default: () => []
		},
		magicUrl: {
			type: [Object, Boolean, null],
			default: null
		},
		pdfUrl: {
			type: [String, Boolean, null],
			default: null
		},
		magazzinoUrl: {
			type: [String, Boolean, null],
			default: null
		},
		emailSend: {
			type: Boolean,
			default: false
		},
		ddtUscitaUrl: {
			type: String,
			default: ''
		},
		ddtEntrataUrl: {
			type: String,
			default: ''
		},
		ordAcquistoUrl: {
			type: String,
			default: ''
		},
		ordVenditaUrl: {
			type: String,
			default: ''
		},
		rcVenditaUrl: {
			type: String,
			default: ''
		},
		ftAcquistoUrl: {
			type: String,
			default: ''
		},
		ftProformaUrl: {
			type: String,
			default: ''
		},
		ftVenditaUrl: {
			type: String,
			default: ''
		},
		rcVenditaUrl: {
			type: String,
			default: ''
		},
		sagOmgUrl: {
			type: String,
			default: ''
		},
		ncAttiveUrl: {
			type: String,
			default: ''
		},
		ncPassiveUrl: {
			type: String,
			default: ''
		},
		docMacUrl: {
			type: String,
			default: ''
		}
	},
	inject: ['flashMessage'],
	emits: ['update-relation-show'],
	data() {
		return this.initialState();
	},
	methods: {
		initialState() {
			return {
				setup: {
					loadingMagic: false,
					loadingMagazzino: false
				},
				crudDialog: new this.$crudDialog('show')
			};
		},
		openDialogMagic() {
			this.setup.loadingMagic = true;
			this.$refs.dialogMagicRef.openDialog(this.magicUrl.magic, this.magicUrl.magicsync);
		},
		showNotificationMagic(notification) {
			this.flashMessage(notification);
		},
		updateRelation(relation) {
			this.$emit('update-relation-show', relation);
		},
		openLink(url) {
			window.open(url, '_blank');
		}
	}
}
</script>