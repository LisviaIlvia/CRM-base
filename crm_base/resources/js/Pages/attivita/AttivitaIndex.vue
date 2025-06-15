<template>
	<crud-index
		:titleTable="records.title"
		:iconTable="records.icon"
		:options="setup"
		:headersChild="headers"
		:crudTableChild="crudTable"
		:componentsChild="records.components"
		:dialogSetupChild="records.dialogSetup"
		:elementId="elementId"
		classCustom="d-none"
	>
	</crud-index>
	<v-card
		elevation="4"
		variant="flat"
		height="100%"
	>
		<v-toolbar
			class="px-3"
		>
			<v-row class="align-center bg-grey-lighten-3">
				<v-col>
					<header-box :title="records.title" :icon="records.icon" />
				</v-col>
				<v-col
					class="text-right"
				>
					<v-btn 
						v-if="crudTable.urlCreate !== null"
						variant="flat"
						density="comfortable"
						icon="fa-solid fa-plus"
						rounded="sm"
						:class="['mr-0', { 'cursor-not-allowed': !crudTable.urlCreate }]"
						:color="crudTable.colorCreate"
						:loading="crudTable.loading.create"
						:disabled="!crudTable.urlCreate"
						@click="crudTable.openDialogCreate()"
					/>
				</v-col>
			</v-row>
		</v-toolbar>
		<div class="pt-6 pb-4 px-6">
			<v-row
				v-for="activity in sortedActivities"
				:key="activity.id"
			>
				<v-col cols="12" class="mb-2">
					<v-row
						class="bg-grey-lighten-5 pa-2 rounded"
					>
						<v-col cols="4">
							<p><strong>Data: </strong>{{ activity.data }}</p>
						</v-col>	
						<v-col cols="4">
							<p><strong>Tipo: </strong>{{ activity.type }}</p>
						</v-col>	
						<v-col col="4" class="text-right">
							<v-btn 
								v-if="activity.actions.edit !== null"
								icon="fa-solid fa-pen fa-sm"
								density="compact"
								rounded="sm"
								class="me-2"
								:color="crudTable.colorEdit"
								:loading="crudTable.loading.edit && crudTable.loading.recordId === activity.id"
								:disabled="activity.actions.update === false"
								@click="crudTable.openDialogEdit(activity.id, activity.actions.edit, activity.actions.update)"
							/>
							<v-btn
								v-if="activity.actions.destroy !== null"
								icon="fa-solid fa-trash fa-sm"
								density="compact"
								rounded="sm"
								:color="crudTable.colorDelete"
								:loading="crudTable.loading.delete && crudTable.loading.recordId === activity.id"
								:disabled="activity.actions.destroy === false"
								@click="crudTable.openDialogDelete(activity.id, activity[records.nameDelete], activity.actions.destroy)"
							/>
						</v-col>
						<v-divider color="grey-lighten-3"></v-divider>
						<v-col cols="12">
							<p><strong>Descrizione:<br></strong>{{ activity.descrizione || 'Nessuna descrizione' }}</p>
						</v-col>
					</v-row>
				</v-col>
			</v-row>	
		</div>
	</v-card>
</template>

<script>
import CrudIndex from '@/Pages/Crud/CrudIndex.vue';

export default {
	name: 'AttivitaIndex',
	components: {
		CrudIndex
	},
	props: {
		records: {
			type: Object,
			required: true
		},
		elementId: {
			type: Number,
			default: null
		}
	},
	computed: {
		sortedActivities() {
			return [...this.records.data].sort((a, b) => new Date(b.data) - new Date(a.data));
		}
	},
	data() {
		return this.initialState();
	},
	methods: {
		initialState() {
			return {
				setup: {
					single: this.records.single,
					type: this.records.type,
					order: this.records.order
				},
				headers: this.records.headers,
				crudTable: new this.$crudTable(this.records)
			}
		}
	}
};
</script>