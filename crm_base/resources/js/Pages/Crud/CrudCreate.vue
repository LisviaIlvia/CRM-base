<template>
	<dialog-create
		:crudDialog="crudDialog"
		:sendForm="sendForm"
		ref="dialogCreateRef"
		@reset="handleReset"
	>
		<component
			:is="componentContent"
			:dataElements="data.elements"
			:crudDialog="crudDialog"
			v-bind="elementsProps"
			crud="create"
			ref="contentRef"
		/>
	</dialog-create>
</template>

<script>
export default {
	name: 'CrudCreate',
	props: {
		componentContent: {
			type: Object,
			default: () => ({})
		},
		elementId: {
			type: Number,
			default: null
		},
		elementCustom: {
			type: [Number, String, Array, Object],
			default: null
		},
		year: {
			type: Number,
			default: null
		}
	},
	data() {
		return this.initialState();
	},
	computed: {
		elementsProps() {
			return {
				...(this.elementCustom !== null ? { elementCustom: this.elementCustom } : {}),
				...(this.elementId !== null ? { elementId: this.elementId } : {})
			};
		}
	},
	methods: {
		initialState() {
			return {
				data: {
					elements: {}
				},
				crudDialog: new this.$crudDialog('create')
			};
		},
		openDialog(urlOpen, urlForm) {
			const extraData = {
				...(this.elementId ? { element_id: this.elementId } : {}),
				...(this.year ? { year: this.year } : {})
			};
			
			this.$refs.dialogCreateRef.openDialogCreate(urlOpen, urlForm, (data) => {
					this.data.elements = data;
				},
				{ extraData }
			);
		},
		sendForm() {
			const setup = this.$refs.contentRef?.setup || {};
			const form = this.$refs.contentRef.getForm();
			const atts = {
				...(setup.nameField && { nameField: setup.nameField }),
				...(setup.field && { field: setup.field }),
				...(setup.containsFile && { containsFile: setup.containsFile }),
				...(setup.callbackError && { callbackError: setup.callbackError })
			};

			this.$refs.dialogCreateRef.sendFormCreate(form, atts || undefined);
		},
		handleReset() {
			Object.assign(this.$data, this.initialState());
		}
	}
}
</script>