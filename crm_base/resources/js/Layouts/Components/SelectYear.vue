<template>
	<v-select
		v-model="SelectYear"
		:items="years"
		label="Seleziona un anno"
		class="width-year"
		hide-details
		@update:modelValue="updateYear"
	></v-select>
</template>

<style scoped>
	.width-year {
		max-width: 160px;
	}
</style>

<script>
import { useYearStore } from '@/store/yearStore';

export default {
	name: 'SelectYear',
	data() {
		return this.initialState();
	},
	created() {
		this.fillYears();
	},
	methods: {
		initialState() {
			const yearStore = useYearStore();
			return {
				SelectYear: yearStore.selectedYear,
				years: []
			}
		},
		fillYears() {
			const currentYear = new Date().getFullYear();
			const startYear = import.meta.env.VITE_APP_ANNO_INIZIALE;
			for (let year = parseInt(startYear); year <= currentYear; year++) {
				this.years.push(year);
			}
		},
		updateYear(newYear) {
			const yearStore = useYearStore();
			yearStore.setSelectedYear(newYear);
		}
	}
};
</script>
