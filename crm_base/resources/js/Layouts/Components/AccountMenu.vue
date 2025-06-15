<template>
	<v-spacer class="spazio-menu"></v-spacer>
	 <select-year />
	<h3 class="ms-4">{{ name }}</h3>
	<v-menu open-on-hover>
		<template v-slot:activator="{ props }">
			<v-btn icon="fa-solid fa-user" v-bind="props"></v-btn>
		</template>
		<v-list>
			<v-list-item v-for="(item, index) in items" :key="index" @click="item.click()">
				<v-list-item-title>{{ item.title }}</v-list-item-title>
			</v-list-item>
		</v-list>
	</v-menu>
	<profilo-edit
		ref="profiloEdit"
		:dialogTitle="setup.title"
		:dialogType="setup.type"
		@show-notification="showNotification"
		@update-user="updateUser"
	/>
</template>

<style>
.spazio-menu {
	flex-grow: 5 !important;
}
</style>

<script>
import SelectYear from '@/Layouts/Components/SelectYear.vue';
import ProfiloEdit from '@/Pages/profilo/ProfiloEdit.vue';

export default {
	name: 'AccountMenu',
	components: {
		SelectYear,
		ProfiloEdit
	},
	props: {
		name: {
			type: String,
			required: true
		}
    },
	emits: ['flash-message'],
	data() {
		return this.initialState();
	},
	methods: {
		initialState() {
			return {
				setup: {
					title: 'Profilo',
					type: 'm',
				},
				items: [
					{ title: 'Profilo', click: () => this.profilo() },
					{ title: 'Logout', click: () => this.logout() }
				]
			}
		},
		profilo() {
			let urlOpen = this.$route('profilo.edit');
			let urlForm = this.$route('profilo.update');
			this.$refs.profiloEdit.openDialog(urlOpen, urlForm);
		},
		logout() {
			this.$axiosService.post({
				url: this.$route('logout'),
				success: (response) => {
					this.$inertia.visit(this.$route('login'));
				}
			});
		},
		updateUser(name) {
			this.user = name;
		},
		showNotification(notification) {
			notification.user = this.user;
			this.$emit('flash-message', notification);
		}
	}
}
</script>