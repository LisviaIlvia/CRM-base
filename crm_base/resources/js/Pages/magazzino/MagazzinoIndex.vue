<template>
	<crud-index
		:options="setup"
		:headersChild="headers"
		:crudTableChild="crudTable"
	>
		<template v-slot:custom-disponibile="{ item }">
			<v-chip :color="item.disponibile < 0 ? 'red' : item.disponibile === 0 ? 'orange' : 'green'" class="fixed-width-chip text-center">
				{{ item.disponibile }}
			</v-chip>
		</template>
	</crud-index>
</template>

<style scoped>
	.fixed-width-chip {
		width: 100%;
		display: flex;
		justify-content: center;
	}
</style>

<script>
import CrudIndex from '@/Pages/Crud/CrudIndex.vue';

export default {
	name: 'MagazzinoIndex',
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