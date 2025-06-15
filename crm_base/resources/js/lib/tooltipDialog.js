class tooltipDialog {
	
	static show(name, gender = 'm') {
		let prefisso = '';
		
		switch(gender) {
			case 'm':
				prefisso = ' il ';
				break;
			case 'md':
				prefisso = ' lo ';
				break;
			case 'f':
				prefisso = ' la ';
				break;
			case 'a':
				prefisso = " l'";
				break;
			
		}
		
		if( gender == 'f' ) prefisso = ' una nuova ';
		return {
			title: 'Visualizza ' + name.charAt(0).toUpperCase() + name.slice(1),
			infoTooltip: 'In questa finestra è possibile visualizzare' + prefisso + name
		}
	}
	
	static create(name, gender = 'm') {
		let prefisso = '';
		
		switch(gender) {
			case 'm':
				prefisso = ' un nuovo ';
				break;
			case 'f':
				prefisso = ' una nuova ';
				break;
			
		}
		return {
			title: 'Crea ' + name.charAt(0).toUpperCase() + name.slice(1),
			infoTooltip: 'In questa finestra è possibile creare' + prefisso + name
		}
	}
	
	static edit(name, gender = 'm') {
		let prefisso = '';
		
		switch(gender) {
			case 'm':
				prefisso = ' il ';
				break;
			case 'md':
				prefisso = ' lo ';
				break;
			case 'f':
				prefisso = ' la ';
				break;
			case 'a':
				prefisso = " l'";
				break;
			
		}
		return {
			title: 'Modifica ' + name.charAt(0).toUpperCase() + name.slice(1),
			infoTooltip: 'In questa finestra è possibile modificare' + prefisso + name
		}
	}
	
	static delete(name, gender = 'm') {
		let prefisso = '';
		
		switch(gender) {
			case 'm':
				prefisso = ' il ';
				break;
			case 'md':
				prefisso = ' lo ';
				break;
			case 'f':
				prefisso = ' la ';
				break;
			case 'a':
				prefisso = " l'";
				break;
			
		}
		
		return {
			title: 'Elimina ' + name.charAt(0).toUpperCase() + name.slice(1),
			infoTooltip: 'In questa finestra è possibile eliminare' + prefisso + name,
			content: ''
		}
	}
	
	static magic(name, gender = 'm') {
		let prefisso = '';
		
		switch(gender) {
			case 'm':
				prefisso = 'dal ';
				break;
			case 'f':
				prefisso = 'dalla ';
				break;
			case 'a':
				prefisso = "dall'";
				break;
			
		}
		
		return {
			title: 'Crea un collegamento per ' + name,
			infoTooltip: 'In questa finestra è possibile creare un nuovo documento partendo ' + prefisso + name
		}
	}
	
		
	static clone(name, gender = 'm') {
		let prefisso = '';
		
		switch(gender) {
			case 'm':
				prefisso = ' il ';
				break;
			case 'md':
				prefisso = ' lo ';
				break;
			case 'f':
				prefisso = ' la ';
				break;
			case 'a':
				prefisso = " l'";
				break;
			
		}
		
		return {
			title: 'Clona ' + name.charAt(0).toUpperCase() + name.slice(1),
			infoTooltip: 'In questa finestra è possibile clonare' + prefisso + name,
			content: ''
		}
	}
}

export default tooltipDialog;