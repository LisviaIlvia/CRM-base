<template>
	<dialog-show
		:crudDialog="crudDialog"
		ref="dialogShowRef"
		@reset="handleReset"
	>
		<template v-slot:content>
			<component
				:is="componentContent"
				:dataElements="data.elements"
				:crudDialog="crudDialog"
				:readonly="true"
				crud="show"
				ref="contentRef"
			/>
		</template>
	</dialog-show>
</template>

<script>
export default {
	name: 'CrudShow',
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
				crudDialog: new this.$crudDialog('show')
			};
		},
		openDialog(urlOpen) {
			const setup = this.$refs.contentRef?.setup || {};
			const { extraData = null, callbackError = null } = setup;
			this.$refs.dialogShowRef.openDialogShow(urlOpen, (data) => {
					this.data.elements = data;
				},
				{ extraData, callbackError }
			);
		},
		handleReset() {
			Object.assign(this.$data, this.initialState());
		}
	}
}
</script>