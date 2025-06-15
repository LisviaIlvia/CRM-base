<template>
	<crud-index
		:options="setup"
		:headersChild="headers"
		:crudTableChild="crudTable"
	>
		<template v-slot:custom-predefinito="{ item }">
			<v-chip :color="item.predefinito === 1 ? 'green' : 'red'" text-color="white">
				<v-icon>{{ item.predefinito === 1 ? 'fa-solid fa-check' : 'fa-solid fa-xmark' }}</v-icon>
			</v-chip>
		</template>
	</crud-index>
</template>

<script>
import CrudIndex from '@/Pages/Crud/CrudIndex.vue';

export default {
	name: 'MetodiPagamentoIndex',
	components: {
		CrudIndex
	},
	data() {
		return this.initialState();
	},
	methods: {
		initialState() {
			return {
				setup: {
					single: this.$usePage().props.single,
					type: this.$usePage().props.type
				},
				headers: this.$usePage().props.headers,
				crudTable: new this.$crudTable(this.$usePage().props)
			}
		}
	}
};
</script>