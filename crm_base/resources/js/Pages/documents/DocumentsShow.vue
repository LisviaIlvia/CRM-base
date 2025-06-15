<template>
	<dialog-show
		:crudDialog="crudDialog"
		ref="dialogShowRef"
		@reset="handleReset"
	>
		<template v-slot:header>
			<tools-show
				:dialogTitle="dialogTitle"
				:dialogType="dialogType"
				:typesRelation="data.typesRelation"
				:magicUrl="data.magicUrl"
				:pdfUrl="data.pdfUrl"
				@update-relation-show="updateRelationShow"
			/>
		</template>
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
import ToolsShow from '@/Components/ToolsShow.vue';

export default {
	name: 'DocumentsShow',
	components: {
		ToolsShow
	},
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
					elements: null,
					typesRelation: [],
					magicUrl: null,
					pdfUrl: null,
					dialogTitle: '',
					dialogType: ''
				},
				crudDialog: new this.$crudDialog('show')
			};
		},
		openDialog(urlOpen) {
			this.dialogTitle = this.$refs.dialogShowRef.dialogTitle;
			this.dialogType = this.$refs.dialogShowRef.dialogType;
			const setup = this.$refs.contentRef?.setup || {};
			const { extraData = null, callbackError = null } = setup;
			this.$refs.dialogShowRef.openDialogShow(urlOpen, (data) => {
				this.data.elements = data;
				this.data.typesRelation = data.types_relation;
				this.data.magicUrl = data.resource.magic_url;
				this.data.pdfUrl = data.resource.pdf_url.pdf;
				},
				{ extraData, callbackError }
			);
		},
		handleReset() {
			Object.assign(this.$data, this.initialState());
		},
		updateRelationShow(relation) {
			const headerDoc = this.$refs.contentRef?.$refs?.headerDocumentRef;
			headerDoc.data.parents = relation.parents;
			headerDoc.data.children = relation.children;
		}
	}
}
</script>