<template>
	<v-card
		v-if="crudTable && components"
		:class="classCustom"
	>
		<v-toolbar
			class="px-3"
		>
			<v-row 
				v-if="setup"
				class="align-center bg-grey-lighten-3"
			>
				<v-col
					v-if="iconTable != ''"
					cols="auto"
					class="icon-custom-box align-self-center px-4 pt-4"
				>
					<i :class="iconTable"></i> 
				</v-col>
				<v-col
					v-if="titleTable != ''"
					cols="auto"
					class="align-self-center"
				>
					<h3 class="h3-custom">
						{{ titleTable }}
					</h3>
				</v-col>
				<v-col cols="4">
					<v-text-field
						v-model="setup.search"
						class="border-custom-none"
						bg-color="surface"
						hide-details
						placeholder="Ricerca..."
					></v-text-field>
				</v-col>
				<v-col class="text-right">
					<v-btn
						v-if="crudTable.urlExportExcel !== null"
						variant="flat"
						density="comfortable"
						icon="fa-solid fa-file-excel"
						rounded="sm"
						class="mr-2"
						:color="crudTable.colorExportExcel"
						:disabled="!crudTable.urlExportExcel"
						@click="crudTable.exportExcel(this.setup.yearStore.selectedYear)"
					/>
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
		<v-data-table
			v-if="crudTable && setup"
			v-model:items-per-page="setup.itemsPerPage"
			:headers="headers"
			:items="crudTable.records"
			:sort-by="[setup.order]"
			:search="setup.search"
		>
			<template v-for="header in headers" v-slot:[`item.${header.key}`]="{ item }">
				<template v-if="header.key === 'actions'">
					<div class="d-flex justify-end">
						<v-btn 
							v-if="item.actions.pdf && item.actions.pdf != false"
							icon="fa-solid fa-file-pdf fa-sm"
							density="compact"
							rounded="sm"
							class="me-2"
							:color="crudTable.colorPdf"
							:disabled="!item.actions.pdf"
							@click="crudTable.openPdf(item.actions.pdf)"
						/>
						<v-btn 
							v-if="item.actions.clone && item.actions.clone != false"
							icon="fa-solid fa-clone fa-sm"
							density="compact"
							rounded="sm"
							class="me-2"
							:color="crudTable.colorClone"
							:disabled="!item.actions.clone"
							@click="crudTable.openDialogClone(item.id, item[setup.attrNameDialog], item.actions.clone)"
						/>
						<v-btn 
							icon="fa-solid fa-eye fa-sm"
							density="compact"
							rounded="sm"
							class="me-2"
							:color="crudTable.colorShow"
							:loading="crudTable.loading.show && crudTable.loading.recordId === item.id"
							:disabled="item.actions.show === false"
							@click="crudTable.openDialogShow(item.id, item.actions.show)"
						/>
						<v-btn 
							v-if="item.actions.edit !== null"
							icon="fa-solid fa-pen fa-sm"
							density="compact"
							rounded="sm"
							class="me-2"
							:color="crudTable.colorEdit"
							:loading="crudTable.loading.edit && crudTable.loading.recordId === item.id"
							:disabled="item.actions.update === false"
							@click="crudTable.openDialogEdit(item.id, item.actions.edit, item.actions.update)"
						/>
						<v-btn
							v-if="item.actions.destroy !== null"
							icon="fa-solid fa-trash fa-sm"
							density="compact"
							rounded="sm"
							:color="crudTable.colorDelete"
							:loading="crudTable.loading.delete && crudTable.loading.recordId === item.id"
							:disabled="item.actions.destroy === false"
							@click="crudTable.openDialogDelete(item.id, item[setup.attrNameDialog], item.actions.destroy)"
						/>
					</div>
				</template>
				<template v-else>
					<template v-if="$slots[`custom-${header.key}`]">
						<slot :name="'custom-' + header.key" :item="item"></slot>
					</template>
					<template v-else>
						{{ item[header.key] }}
					</template>
				</template>
			</template>
		</v-data-table>
	</v-card>
	<component 
		v-if="components.show"
		:is="components.show"
		ref="dialogShowRef" 
		:dialogTitle="setup.single"
		:dialogType="setup.type"
		:dialogSetup="dialogSetup.show"
		:componentContent="components.content"
		@dialog-show-opened="crudTable.stopLoadingShow"
	></component>
	<component 
		v-if="components.create"
		:is="components.create"
		ref="dialogCreateRef"
		:dialogTitle="setup.single"
		:dialogType="setup.type"
		:elementId="elementId"
		:elementCustom="elementCustom"
		:year="this.setup.yearStore.selectedYear"
		:dialogSetup="dialogSetup.create"
		:componentContent="components.content"
		@dialog-create-opened="crudTable.stopLoadingCreate"
		@store-record="crudTable.add"
		@show-notification="showNotification"
	></component>
	<component
		v-if="components.edit"
		:is="components.edit"
		ref="dialogEditRef"
		:dialogTitle="setup.single"
		:dialogType="setup.type"
		:dialogSetup="dialogSetup.edit"
		:componentContent="components.content"
		@dialog-edit-opened="crudTable.stopLoadingEdit"
		@update-record="crudTable.update"
		@show-notification="showNotification"
	></component>
	<dialog-delete
		ref="dialogDeleteRef"
		:dialogTitle="setup.single"
		:dialogType="setup.type"
		:tooltip="setup.tooltipDialog"
		:textRequest="setup.textRequestDialog"
		:textConfirm="setup.textConfirmDialog"
		@dialog-delete-opened="crudTable.stopLoadingDelete" 
		@destroy-record="crudTable.remove"
		@show-notification="showNotification"
	/>
	<dialog-clone
		ref="dialogCloneRef"
		:dialogTitle="setup.single"
		:dialogType="setup.type"
		:tooltip="setup.tooltipDialog"
		:textRequest="setup.textRequestDialog"
		:textConfirm="setup.textConfirmDialog"
		@dialog-clone-opened="crudTable.stopLoadingClone" 
		@clone-record="crudTable.clone"
		@show-notification="showNotification"
	/>
</template>

<style scoped>
	.h3-custom {
		font-size: 1.3rem;
		font-weight: 500;
		padding-left: 0.2rem;
		
	}	
	.icon-custom-box {
		border-right: 3px solid #E0E0E0;
	}
	.icon-custom-box > i {
		font-size: 1.50rem;
	}
</style>

<script>
import { useYearStore } from '@/store/yearStore';
import { markRaw } from 'vue';

export default {
	name: 'crudIndex',
	inject: ['flashMessage'],
	props: {
		titleTable: {
			type: String,
			default: ''
		},
		iconTable: {
			type: String,
			default: ''
		},
		options: {
			type: Object,
			default: () => ({}) 
		},
		headersChild: {
			type: Array,
			default: [] 
		},
		crudTableChild: {
			type: Object,
			default: () => ({}) 
		},
		componentsChild: { 
			type: Object, 
			default: () => ({}) 
		},
		dialogSetupChild: {
			type: Object, 
			default: () => ({}) 
		},
		elementId: {
			type: Number,
			default: null
		},
		elementCustom: {
			type: [Number, String, Array, Object],
			default: null
		},
		onlyIndex: {
			type: Boolean,
			default: false
		},
		classCustom: {
			type: String,
			default: ''
		}
	},
	data() {
		return this.initialState();
	},
	computed: {
		componentsToLoad() {
			return (this.componentsChild && Object.keys(this.componentsChild).length > 0)
				? this.componentsChild
				: this.$usePage().props.components;
		}
	},
	async mounted() {
		await this.loadAllComponents();
		
		this.applyYearFilter();
		if(this.onlyIndex === false) {
			this.setCrudTableDialogs();
		}
	},
	methods: {
		initialState() {
			let textRequestDialog = '';
			const single = this.options.single || this.$usePage().props.single;
			const type = this.options.type || this.$usePage().props.type;
			const attrNameDialog = this.options.attrNameDialog || this.$usePage().props.nameDialog;
			
			switch(type) {
				case 'm':
					textRequestDialog = 'il ' + single;
				break;
				case 'f':
					textRequestDialog = 'la ' + single;
				break;
				case 'a':
					textRequestDialog = "l'" + single;
				break;
			};
			return {
				setup: {
					single,
					type,
					itemsPerPage: this.options.itemsPerPage || this.$usePage().props.itemsPerPage,
					order: this.options.order || this.$usePage().props.order,
					search: this.options.filter || this.$usePage().props.filter,
					tooltipDialog: { text: single, type: type },
					textRequestDialog: textRequestDialog,
					textConfirmDialog: single,
					attrNameDialog,
					yearStore: useYearStore(),
					activeYear: this.options.activeYear ? this.options.activeYear : this.$usePage().props.activeYear,
					recordsYear: this.$usePage().props.recordsYear || false
				},
				dialogSetup: (this.dialogSetupChild && Object.keys(this.dialogSetupChild).length > 0) ? this.dialogSetupChild : this.$usePage().props.dialogSetup,
				headers: this.headersChild?.length ? this.headersChild : this.$usePage().props.headers,
				crudTable: (this.crudTableChild && Object.keys(this.crudTableChild).length > 0) ? this.crudTableChild : new this.$crudTable(this.$usePage().props),
				components: {}
			}
		},
		setCrudTableDialogs() {
			const refsMap = {
				show: 'dialogShowRef',
				create: 'dialogCreateRef',
				edit: 'dialogEditRef',
				delete: 'dialogDeleteRef',
				clone: 'dialogCloneRef'
			};
			const methods = ['stopLoadingShow', 'stopLoadingCreate', 'stopLoadingEdit', 'stopLoadingDelete', 'stopLoadingClone', 'add', 'update', 'remove', 'clone'];

			this.crudTable.setDialogs(
				Object.keys(refsMap).reduce((acc, key) => {
					acc[key] = this.$refs[refsMap[key]];
					return acc;
				}, {})
			);
			methods.forEach((method) => {
				this.crudTable[method] = this.crudTable[method].bind(this.crudTable);
			});
		},
		async loadComponent(componentKey) {
			const componentName = this.componentsToLoad[componentKey];
			if(componentName) {
				try {
					const componentPath = `${componentName}.vue`;
					const loadedComponent = (await import(/* @vite-ignore */ componentPath)).default;
					return markRaw(loadedComponent);
				} catch (error) {
					console.error(`Failed to load component "${componentKey}"`, error);
				}
			}
			return null;
		},
		async loadAllComponents() {
			const loadPromises = Object.keys(this.componentsToLoad).map((key) =>
				this.loadComponent(key).then((component) => {
					this.components[key] = component;
				})
			);
			await Promise.all(loadPromises);
		},
		showNotification(notification) {
			this.flashMessage(notification);
		},
		applyYearFilter() {
			if(!this.setup.activeYear || !this.setup.yearStore.selectedYear) {
				return;
			}

			const year = this.setup.yearStore.selectedYear;
			if(this.setup.recordsYear) {
				const filteredRecord = this.crudTable.originalRecords.find(
					(record) => record.year === year
				);
				this.crudTable.records = filteredRecord ? filteredRecord.records : [];
			} else {
				this.crudTable.records = this.crudTable.originalRecords.filter((record) => {
					const recordYear = parseInt(record.data.split('/')[2], 10);
					return recordYear === year;
				});
			}
		}
	},
	watch: {
		'setup.yearStore.selectedYear': function(newYear) {
			this.applyYearFilter();
		}
	}
}
</script>