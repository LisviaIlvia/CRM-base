<template>
	<dialog-edit
		:crudDialog="crudDialog"
		:sendForm="sendForm"
		ref="dialogEditRef"
		@reset="handleReset"
	>
		<component
			:is="componentContent"
			:dataElements="data.elements"
			:crudDialog="crudDialog"
			crud="edit"
			ref="contentRef"
		/>
	</dialog-edit>
</template>

<script>
export default {
	name: 'CrudEdit',
	props: {
		componentContent: {
			type: Object,
			default: () => ({})
		}
	},
	data() {
		return this.initialState();
	},
	methods: {
		initialState() {
			return {
				data: {
					elements: {}
				},
				crudDialog: new this.$crudDialog('edit')
			};
		},
		openDialog(urlOpen, urlForm) {
			const setup = this.$refs.contentRef?.setup || {};
			const { extraData = null, callbackError = null } = setup;
			this.$refs.dialogEditRef.openDialogEdit(urlOpen, urlForm, (data) => {
					this.data.elements = data;
				},
				{ extraData, callbackError }
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

			this.$refs.dialogEditRef.sendFormEdit(form, atts || undefined);
		},
		handleReset() {
			Object.assign(this.$data, this.initialState());
		}
	}
}
</script>