<template>
	<v-container
		class="pa-0 ma-0"
	>
		<v-row>
			<v-col
				cols="auto"
				class="icon-custom-box align-self-center px-4 pt-4"
			>
				<i :class="icon"></i> 
			</v-col>
			<v-col
				cols="auto"
				class="align-self-center"
			>
				<h3 class="h3-custom">
					{{ title }}
				</h3>
			</v-col>
			<v-spacer class="spazio-menu"></v-spacer>
			<v-col
				cols="auto"
				class="align-self-center"
			>
				<slot />
			</v-col>
		</v-row>
	</v-container>	
</template>

<style scoped>
	.h3-custom {
		font-size: 1.3rem;
		font-weight: 500;
		padding-left: 0.2rem;
		
	}	
	.icon-custom-box {
		border-right: 3px solid #E0E0E0;
	}
	.icon-custom-box > i {
		font-size: 1.50rem;
	}
</style>

<script>
export default {
	name: 'HeaderBox',
	props: {
		icon: {
			type: String,
			required: true
		},
		title: {
			type: String,
			required: true
		}
	}
};
</script>