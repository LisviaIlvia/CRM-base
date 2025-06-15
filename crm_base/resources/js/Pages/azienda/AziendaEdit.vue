<template>
	<dialog-edit
		:crudDialog="crudDialog"
		:sendForm="sendForm"
		ref="dialogEditRef"
		@reset="handleReset"
	>
		<v-row>
			<v-col
			  cols="12"
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
						  cols="12"
						  class="py-0"
						>
							<v-text-field
								v-model="form.ragione_sociale"
								label="Ragione Sociale"
								:color="crudDialog.color"
								:error-messages="crudDialog.errors && crudDialog.errors.nome ? crudDialog.errors.nome : ''"
							></v-text-field>
						</v-col>
					</v-row>
				</v-card>
			</v-col>
			<v-col
			  cols="12"
			  class="py-0 mt-6 mb-4"
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
						>
							<v-text-field
								v-model="form.partita_iva"
								label="Partiva IVA"
								:color="crudDialog.color"
								:error-messages="crudDialog.errors && crudDialog.errors.partita_iva ? crudDialog.errors.partita_iva : ''"
							></v-text-field>
						</v-col>
						<v-col
						  cols="6"
						  class="py-0"
						>
							<v-text-field
								v-model="form.codice_fiscale"
								label="Codice Fiscale"
								:color="crudDialog.color"
								:error-messages="crudDialog.errors && crudDialog.errors.codice_fiscale ? crudDialog.errors.codice_fiscale : ''"
							></v-text-field>
						</v-col>
						<v-col
						  cols="12"
						  class="py-0"
						>
							<v-text-field
								v-model="form.pec"
								label="Pec"
								:color="crudDialog.color"
								:error-messages="crudDialog.errors && crudDialog.errors.pec ? crudDialog.errors.pec : ''"
							></v-text-field>
						</v-col>
					</v-row>
				</v-card>
			</v-col>
		</v-row>
	</dialog-edit>
</template>

<script>

export default {
	name: 'AziendaEdit',
	data() {
		return this.initialState();
	},
	methods: {
		initialState() {
			return {
				form: {
					id: null,
					ragione_sociale: null,
					partita_iva: null,
					codice_fiscale: null,
					pec: null
				},
				crudDialog: new this.$crudDialog('edit')
			};
		},
		openDialog(urlOpen, urlForm) {
			this.$refs.dialogEditRef.openDialogEdit(urlOpen, urlForm, (data) => {
				this.form.id = data.resource.id;
				this.form.ragione_sociale = data.resource.ragione_sociale;
				this.form.partita_iva = data.resource.partita_iva;
				this.form.codice_fiscale = data.resource.codice_fiscale;
				this.form.pec = data.resource.pec;
			});
		},
		sendForm() {
			this.$refs.dialogEditRef.sendFormEdit(this.form);
		},
		handleReset() {
			Object.assign(this.$data, this.initialState());
		}
	}
}
</script>