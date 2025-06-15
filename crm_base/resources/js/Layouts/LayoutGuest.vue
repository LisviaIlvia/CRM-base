<template>
	<Head :title="title" />
	<main>
		<v-container class="d-flex flex-column h-screen">
			<v-row>
				<v-col cols="12" sm="8" md="6" lg="4" xl="3" class="mx-auto align-self-center">
					<slot />
				</v-col>
			</v-row>
		</v-container>
	</main>
</template>

<script>
	import { Head } from '@inertiajs/vue3';
	
	export default {
		name: 'layoutGuest',
		components: {
			Head
		},
		props: {
			title: {
				type: String,
				required: true
			}
		}
	}
</script>