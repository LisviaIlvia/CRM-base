<template>
	<crud-index
		:titleTable="records.title"
		:iconTable="records.icon"
		:options="setup"
		:headersChild="headers"
		:crudTableChild="crudTable"
		:componentsChild="records.components"
		:dialogSetupChild="records.dialogSetup"
	>
	</crud-index>
</template>

<script>
import CrudIndex from '@/Pages/Crud/CrudIndex.vue';

export default {
	name: 'ReferentiIndex',
	components: {
		CrudIndex
	},
	props: {
		records: {
			type: Object,
			required: true
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