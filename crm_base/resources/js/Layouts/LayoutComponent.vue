<template>
	<v-container fluid class="px-0 py-0">
		<v-row class="ma-0 px-0 py-0">
			<v-col cols="12" class="pa-0 ma-0">
				<slot />
			</v-col>
		</v-row>
	</v-container>
	<v-snackbar
		v-model="snackbar.show"
		:color="snackbar.color"
		:timeout="snackbar.timeout"
		:location="snackbar.location"
		transition="slide-x-reverse-transition"
		z-index="3000"
	>
		<v-icon :icon="snackbar.icon" class="mr-2"></v-icon>
		<span class="text-message-snackbar">{{ snackbar.message }}</span>
	</v-snackbar>
</template>

<script>
export default {
	name: 'LayoutComponent',
	props: {
		flashMessage: {
			type: Object,
			default: () => ({ type: "", text: "" })
		}
	},
	setup() {
		const snackbar = {
			show: false,
			message: '',
			color: '',
			timeout: 3000,
			location: 'top right',
			icon: ''
		};
		
		return { snackbar };
	},
	watch: {
		flashMessage(value) {
			if (value.text !== "") {
				this.snackbar.message = value.text;
				switch (value.type) {
					case "success":
						this.snackbar.color = 'success';
						this.snackbar.icon = 'fa-solid fa-circle-check';
						this.user.name = value.user || this.user.name;
						break;
					case "error":
						this.snackbar.color = 'error';
						this.snackbar.icon = 'fa-solid fa-circle-xmark';
						break;
				}
				this.snackbar.show = true;
			}
		}
	}
}
</script>