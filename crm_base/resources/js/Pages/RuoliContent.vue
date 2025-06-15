<template>
	<v-row>
		<v-col
		  cols="12"
		  class="py-0"
		>
			<v-text-field
				v-model="form.name"
				label="Nome"
				:color="crudDialog.color"
				:readonly="readonly"
				:error-messages="crudDialog.errors && crudDialog.errors.name ? crudDialog.errors.name : ''"
			></v-text-field>
		</v-col>
	</v-row>
	<v-row class="mt-0">
		<v-col>
			<v-sheet
				rounded
				elevation="4"
			>
				<v-data-table-virtual
					:headers="headersPermissions"
					class="table-custom"
					:items="data.permissions"
					:sort-by="[orderPermissions]"
                    height="506"
				>
					<template v-slot:item.model="{ item }">
						{{ item.model }}
					</template>

					<template v-slot:[`item.${header.key}`]="{ item }" v-for="header in headersPermissions.slice(1)" :key="header.key">
						<v-checkbox
							v-if="item.rules && item.rules[header.key]"
							v-model="form.permissions"
							:value="item.rules[header.key].id"
							:color="crudDialog.color"
							hide-details
							class="d-flex justify-center"
							:readonly="readonly"
						></v-checkbox>
					</template>
				</v-data-table-virtual>
			</v-sheet>
		</v-col>
	</v-row>
</template>

<script>
export default {
	name: 'RuoliContent',
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
					nameField: 'name'
				},
				headersPermissions: [
					{ title: 'Modello', key: 'model', sortable: false },
					{ title: 'Show', key: 'show', sortable: false, align: 'center' },
					{ title: 'Create', key: 'create', sortable: false, align: 'center' },
					{ title: 'Edit', key: 'edit', sortable: false, align: 'center' },
					{ title: 'Delete', key: 'delete', sortable: false, align: 'center' },
					{ title: 'Export', key: 'export', sortable: false, align: 'center' },
					{ title: 'PDF', key: 'pdf', sortable: false, align: 'center' },
					{ title: 'Magic', key: 'magic', sortable: false, align: 'center' },
					{ title: 'Clone', key: 'clone', sortable: false, align: 'center' }
				],
				orderPermissions: { key: 'model', order: 'asc' },
				form: {
					name: null,
					permissions: []
				},
				data: {
					permissions: []
				}
			};
		},
		getData() {
			this.data.permissions = this.dataElements.permissions;
			if(this.dataElements.resource) {
				this.form.name = this.dataElements.resource.name;
				this.data.permissions.forEach((permission) => {
					Object.entries(permission.rules).forEach(([key, rule]) => {
						if (rule.value) {
							this.form.permissions.push(rule.id);
						}
					});
				});
			}
		},
		getForm() {
			return this.form;
		}
	}
}
</script>
