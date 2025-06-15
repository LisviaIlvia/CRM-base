<template>
	<v-sheet>
		<v-container fluid class="px-0 mt-2">
			<v-row>
				<v-col
					cols="5"
					class="py-0"
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
										<v-col cols="8">
											<header-box title="Anagrafica" icon="fa-solid fa-address-card" />
										</v-col>
										<v-col class="text-right">
											<v-btn 
												variant="flat"
												density="comfortable"
												icon="fa-solid fa-pen fa-sm"
												rounded="sm"
												class="mr-0"
												color="color-edit"
												:loading="setup.loadingDialogEdit"
												:disabled="!data.actions.update"
												@click="openDialogEdit(data.actions.edit, data.actions.update)"
											/>
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
											:color="setup.color"
											readonly
										></v-text-field>
									</v-col>
								</v-row>
							</v-card>
						</v-col>
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
											:color="setup.color"
											readonly
										></v-text-field>
									</v-col>
									<v-col
									  :cols="6"
									  class="py-0"
									>
										<v-text-field
											v-model="form.codice_fiscale"
											label="Codice Fiscale"
											:color="setup.color"
											readonly
										></v-text-field>
									</v-col>
									<v-col
									  cols="12"
									  class="py-0"
									>
										<v-text-field
											v-model="form.pec"
											label="Pec"
											:color="setup.color"
											readonly
										></v-text-field>
									</v-col>
								</v-row>
							</v-card>
						</v-col>
					</v-row>
				</v-col>
				<v-col
					cols="7"
					class="py-0"
				>
					<v-row>
						<v-col
							cols="12"
							class="mb-3 py-0"
						>
							<indirizzi :records="data.indirizzi" :elementId="form.id"/>
						</v-col>
					</v-row>
				</v-col>
			</v-row>
		</v-container>
	</v-sheet>
	<azienda-edit
		ref="AziendaEdit"
		:dialogTitle="setup.title"
		:dialogType="setup.type"
		@dialog-edit-opened="stoploadingDialogEdit"
		@update-record="updateRecord"
		@show-notification="showNotification"
	/>
</template>

<script>
import AziendaEdit from '@/Pages/azienda/AziendaEdit.vue';
import Indirizzi from '@/Pages/Indirizzi/IndirizziIndex.vue';

export default {
	name: 'AziendaIndex',
	components: {
		AziendaEdit,
		Indirizzi
	},
	inject: ['flashMessage'],
	mounted() {
		this.setup.dialogEdit = this.$refs.AziendaEdit;
	},
	data() {
		return this.initialState();
	},
	methods: {
		initialState() {
			return {
				setup: {
					title: this.$usePage().props.title,
					type: this.$usePage().props.type,
					color: 'color-show',
					dialogEdit: null,
					loadingDialogEdit: false
				},
				form: {
					id: this.$usePage().props.data.id,
					ragione_sociale: this.$usePage().props.data.ragione_sociale,
					nome: this.$usePage().props.data.nome,
					partita_iva: this.$usePage().props.data.partita_iva,
					codice_fiscale: this.$usePage().props.data.codice_fiscale,
					pec: this.$usePage().props.data.pec
				},
				data: {
					indirizzi: this.$usePage().props.data.indirizzi,
					actions: this.$usePage().props.data.actions
				}
			};
		},
		openDialogEdit(urlOpen, urlForm) {
			this.setup.loadingDialogEdit = true;
			this.setup.dialogEdit.openDialog(urlOpen, urlForm);
		},
		updateRecord(data) {
			this.form.ragione_sociale = data.ragione_sociale;
			this.form.partita_iva = data.partita_iva;
			this.form.codice_fiscale = data.codice_fiscale;
			this.form.pec = data.pec;
		},
		stoploadingDialogEdit() {
			this.setup.loadingDialogEdit = false;
		},
		showNotification(notification) {
			this.flashMessage(notification);
		}
	}
}
</script>