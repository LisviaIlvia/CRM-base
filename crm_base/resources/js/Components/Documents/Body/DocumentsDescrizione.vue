<template>
	<v-col
		class="py-0 col-descrizione"
	>
		<v-textarea
			v-model="descrizione"
			label="Descrizione"
			:color="color"
			rows="3"
			auto-grow
			hide-details
			:readonly="readonly"
			@update:modelValue="updateElement"
		/>
	</v-col>
</template>

<style scoped>
	.col-descrizione {
		flex: 0 0 94%;
		max-width: 94%;
	}
</style>

<script>
export default {
	name: 'DocumentsDescrizione',
	props: {
		modelValue: {
			type: Object,
			required: true
		},
		color: {
			type: String,
			required: true
		},
		readonly: {
			type: Boolean,
			default: false
		}
	},
	data() {
		return this.initialState();
	},
	methods: {
		initialState() {
			return {
				...this.modelValue
			};
		},
		updateElement() {
			this.$emit("update:modelValue", { ...this.$data });
		},
		getForm() {
			return this.form;
		}
	},
	watch: {
		modelValue: {
			deep: true,
			handler(newVal) {
				Object.assign(this.$data, newVal);
			}
		}
	}
}
</script>