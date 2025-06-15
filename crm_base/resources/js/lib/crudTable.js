class crudTable {

	constructor(props) {
		this.records = props.data;
		this.originalRecords = [...props.data];
		this.filter = props.filter;
		this.urlCreate = props.new?.['create'] || null;
		this.urlStore = props.new?.['store'] || null;
		this.urlExportExcel = props.export;
		this.colorShow = 'color-show';
		this.colorCreate = 'color-create';
		this.colorEdit = 'color-edit';
		this.colorDelete = 'color-delete';
		this.colorExportExcel = 'color-export-excel';
		this.colorPdf = 'color-pdf';
		this.colorClone = 'color-clone';
		this.loading = {
			show: false,
			create: false,
			edit: false,
			delete: false,
			clone: false,
			recordId: null
		};

		this.dialogShow = null;
		this.dialogCreate = null;
		this.dialogEdit = null;
		this.dialogDelete = null;
		this.dialogClone = null;
	}

	setDialogs(dialogs) {
		this.dialogShow = dialogs.show;
		this.dialogCreate = dialogs.create;
		this.dialogEdit = dialogs.edit;
		this.dialogDelete = dialogs.delete;
		this.dialogClone = dialogs.clone;
	}

	add(newRecord, fieldName = null, value = null) {
		let lastIndex = null;

		if (Array.isArray(newRecord)) {
			for (let record of newRecord) {
				this.records.push(record);
				this.originalRecords.push(record);
			}
			lastIndex = this.records.length - newRecord.length;
		} else {
			this.records.push(newRecord);
			this.originalRecords.push(newRecord);
			lastIndex = this.records.length - 1;
		}

		if (fieldName !== null && value !== null && newRecord[fieldName] === 1) {
			this.records.forEach((record, index) => {
				if (index !== lastIndex) {
					record[fieldName] = value;
				}
			});

			this.originalRecords.forEach((record, index) => {
				if (index !== lastIndex) {
					record[fieldName] = value;
				}
			});
		}
	}

	update(updatedRecord, fieldName = null, value = null) {
		const index = this.records.findIndex((record) => record.id === updatedRecord.id);
		if (index >= 0) {
			Object.assign(this.records[index], updatedRecord);
			Object.assign(this.originalRecords[index], updatedRecord);

			if(fieldName != null && value != null && updatedRecord[fieldName] === true) {
				this.records.forEach((record, idx) => {
					if (idx !== index) {
						record[fieldName] = value;
					}
				});

				this.originalRecords.forEach((record, idx) => {
					if (idx !== index) {
						record[fieldName] = value;
					}
				});
			}
		}
	}

	clone(newRecord) {
		this.add(newRecord);
	}

	remove(deletedRecordId) {
		const index = this.records.findIndex((record) => record.id === deletedRecordId);
		if (index >= 0) {
			this.records.splice(index, 1);
			this.originalRecords.splice(index, 1);
		}
	}

	exportExcel(year) {
		const url = `${this.urlExportExcel}?year=${year}`;
		window.location.href = url;
	}

	startLoadingShow(recordId) {
		this.loading.show = true;
		this.loading.recordId = recordId;
	}

	startLoadingCreate() {
		this.loading.create = true;
	}

	startLoadingEdit(recordId) {
		this.loading.edit = true;
		this.loading.recordId = recordId;
	}

	startLoadingClone(recordId) {
		this.loading.clone = true;
		this.loading.recordId = recordId;
	}

	startLoadingDelete(recordId) {
		this.loading.delete = true;
		this.loading.recordId = recordId;
	}

	stopLoadingShow() {
		this.loading.show = false;
	}

	stopLoadingCreate() {
		this.loading.create = false;
	}

	stopLoadingEdit() {
		this.loading.edit = false;
		this.loading.recordId = null;
	}

	stopLoadingClone() {
		this.loading.clone = false;
		this.loading.recordId = null;
	}

	stopLoadingDelete() {
		this.loading.delete = false;
		this.loading.recordId = null;
	}

	openPdf(urlOpen) {
		window.open(urlOpen, '_blank');
	}

	openDialogShow(recordId, urlOpen) {
		this.startLoadingShow(recordId);
		this.dialogShow.openDialog(urlOpen);
	}

	openDialogCreate() {
		this.startLoadingCreate();
		this.dialogCreate.openDialog(this.urlCreate, this.urlStore);
	}

	openDialogEdit(recordId, urlOpen, urlForm) {
		this.startLoadingEdit(recordId);
		this.dialogEdit.openDialog(urlOpen, urlForm);
	}

	openDialogClone(recordId, data, urlForm) {
		this.startLoadingClone(recordId);
		this.dialogClone.openDialog(data, urlForm);
	}

	openDialogDelete(recordId, data, urlForm) {
		this.startLoadingDelete(recordId);
		this.dialogDelete.openDialog(data, urlForm);
	}
}

export default crudTable;