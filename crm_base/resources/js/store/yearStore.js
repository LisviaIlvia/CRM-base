import { defineStore } from 'pinia';

export const useYearStore = defineStore('year', {
	state: () => ({
		selectedYear: new Date().getFullYear()
	}),
	actions: {
		setSelectedYear(year) {
			this.selectedYear = year;
		}
	}
});