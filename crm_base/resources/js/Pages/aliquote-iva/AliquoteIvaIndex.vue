<template>
	<crud-index
		:options="setup"
		:headersChild="headers"
		:crudTableChild="crudTable"
	>
		<template v-slot:custom-aliquota="{ item }">
			<span>{{ item.aliquota }}%</span>
		</template>
		<template v-slot:custom-predefinita="{ item }">
			<v-chip :color="item.predefinita === true ? 'green' : 'red'">
				<v-icon>{{ item.predefinita === true ? 'fa-solid fa-check' : 'fa-solid fa-xmark' }}</v-icon>
			</v-chip>
		</template>
	</crud-index>
</template>

<script>
import CrudIndex from '@/Pages/Crud/CrudIndex.vue';

export default {
	name: 'AliquoteIvaIndex',
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
					type: this.$usePage().props.type,
					order: this.$usePage().props.order
				},
				headers: this.$usePage().props.headers,
				crudTable: new this.$crudTable(this.$usePage().props)
			}
		}
	}
};
</script>