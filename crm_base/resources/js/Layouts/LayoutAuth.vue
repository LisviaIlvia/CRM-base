<template>
	<v-app>
		<Head :title="title" />
		<v-app-bar elevation="3">
			<v-app-bar-title><img class="mt-2" height="30" src="/images/logo.png"></v-app-bar-title>
			<account-menu 
				:name="user.name"
				@flash-message="flashMessage"
			/>
		</v-app-bar>
		<sidebar-menu :rail="rail" :role="user.role" :permissions="user.permissions"/>
		<v-main>
			<v-container fluid class="px-0 py-3">
				<v-row class="px-3 background-principale">
					<v-col cols="auto" class="custom-border-button align-self-center">
						<v-btn
							color="surface"
							variant="text"
							density="compact"
							:icon="!rail ? 'fa-solid fa-chevron-left' : 'fa-solid fa-chevron-right'"
							@click.stop="toggleRail"
						></v-btn>
					</v-col>
					<v-col class="align-self-center pl-5">
						<h3 class="text-white">{{title}}</h3>
					</v-col>
					<v-col cols="auto" class="align-self-center py-0 px-5 icon-bar">
						<v-icon color="surface" :icon="icon"></v-icon>
					</v-col>
				</v-row>
				<v-row class="ma-0 px-8 py-10">
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
				class="pa-5"
			>
				<v-icon :icon="snackbar.icon" class="mr-2"></v-icon>
				<span class="text-message-snackbar">{{ snackbar.message }}</span>
			</v-snackbar>
		</v-main>
	</v-app>
</template>

<style>
	.icon-bar .v-icon {
		font-size: 26px;
	}
	
	.icon-bar .v-icon svg path {
		fill: #fff;
	}
	
	.icon-bar .v-icon svg {
		height: 28px;
	}
</style>

<script>
import { Head } from '@inertiajs/vue3';
import SidebarMenu from '@/Layouts/Components/SidebarMenu.vue';
import AccountMenu from '@/Layouts/Components/AccountMenu.vue';
export default {
	name: 'layoutAuth',
	components: {
		Head,
		SidebarMenu,
		AccountMenu
	},
	props: {
		title: {
			type: String,
			required: true
		},
		icon: {
			type: String,
			required: true
		}
	},
	provide() {
		return {
			flashMessage: this.flashMessage
		}
	},
	mounted() {
		this.getUser();
	},
	data() {
		return this.initialState();
	},
	methods: {
		initialState() {
			return {
				rail: false,
				user: {
					name: '',
					role: '',
					permissions: []
				},
				snackbar: {
					show: false,
					message: '',
					color: '',
					timeout: 3000,
					location: 'top right',
					icon: ''
				}
			}
		},
		getUser() {
			const { auth } = this.$usePage().props;
			this.user.name = auth.user.name;
			this.user.role = auth.user.role;
			this.user.permissions = auth.user.permissions;
		},
		toggleRail() {
			this.rail = !this.rail;
		},
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