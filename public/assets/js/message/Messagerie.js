Messagerie = {};

Messagerie.Launch = function(params){

	Messagerie.urlRef = params.urlRef;
	Messagerie.urlReception = params.urlReception;
	Messagerie.urlBrouillons = params.urlBrouillons;
	Messagerie.urlCorbeille = params.urlCorbeille;
	Messagerie.urlEnvoyes = params.urlEnvoyes;

	Messagerie.element_navPills = $('#messagerie-block #v-pills-messagerie .nav-link');
	Messagerie.globale_content_pills = $('#messagerie-block #v-pills-tabContent');
	Messagerie.element_navPills_content_reception = $('#messagerie-block #v-pills-reception');
	Messagerie.element_navPills_content_brouillons = $('#messagerie-block #v-pills-brouillons');
	Messagerie.element_navPills_content_corbeille = $('#messagerie-block #v-pills-corbeille');
	Messagerie.element_navPills_content_envoyes = $('#messagerie-block #v-pills-envoyes');

	Messagerie.global_content_bloc_message = $('#messagerie-block #bloc_view_message');


	/**
	 * Evenement global à la page index
	 */
	Messagerie.Event = function() {

		Messagerie.element_navPills.click(function() {
			Messagerie.LoadElement($(this).attr('id'));
		})

	}

	/**
	 * Evènement sur liste des messages (reception, brouillons, envoyes, corbeille etc..)
	 */
	Messagerie.EventListeMessage = function(id_global) {

		$(id_global + ' .list-group a.list-group-item').click(function() {

		var lu = true;
		if($(this).hasClass('no-read'))
	    {
			lu = false;
	    }
			if(event.ctrlKey)
			{
				$(id_global + ' .list-group a.list-group-item').each(function() {
					if($(this).hasClass('active')) {
						$(this).children('span').removeClass().addClass('oi oi-check');
					}
				});
				
				if($(this).hasClass('active'))
				{
					$(this).removeClass('active');
					if(lu)
					{
						$(this).children('span').removeClass('oi-check').addClass('oi-envelope-open');
					}
					else
					{
						$(this).children('span').removeClass('oi-check').addClass('oi-envelope-closed');
					}
				}
				else
				{
					$(this).addClass('active');
					if(lu)
					{
						$(this).children('span').removeClass('oi-envelope-open').addClass('oi-check');
					}
					else
					{
						$(this).children('span').removeClass('oi-envelope-closed').addClass('oi-check');
					}
				}
			}
			else
			{
				$(id_global + ' .list-group a.list-group-item').each(function() {
					$(this).removeClass('active');
					if($(this).hasClass('no-read'))
				    {
						$(this).children('span').removeClass('oi-check').addClass('oi-envelope-closed');
				    }
					else
					{
						$(this).children('span').removeClass('oi-check').addClass('oi-envelope-open');
					}
					
				});

				$(this).addClass('active');
				Messagerie.LoadMessage($(this).attr('href'));
			}

			return false;

		});
		
		
	}

	/**
	 * Charge un element en fonction de son id (pills)
	 */
	Messagerie.LoadElement = function(id)
	{
		var url = '#';
		var content = '';

		switch (id) {
		case 'reception':
			url = Messagerie.urlReception;
			content = Messagerie.element_navPills_content_reception;
			break;
		case 'brouillons':
			url = Messagerie.urlBrouillons;
			content = Messagerie.element_navPills_content_brouillons;
			break;
		case 'corbeille':
			url = Messagerie.urlCorbeille;
			content = Messagerie.element_navPills_content_corbeille;
			break;
		case 'envoyes':
			url = Messagerie.urlEnvoyes;
			content = Messagerie.element_navPills_content_envoyes;
			break;


		default:
			alert('Element inconnu');
		return false;
		break;
		}

		Messagerie.globale_content_pills.showLoading();

		$.ajax({
			method: 'GET',
			url: url,
		})
		.done(function( html ) {
			content.html(html);
			Messagerie.globale_content_pills.hideLoading();
		});
	}

	/**
	 * Appel ajax du message selectionné (ouverture et fermeture pop-in)
	 */
	Messagerie.LoadMessage = function(url)
	{
		Messagerie.global_content_bloc_message.showLoading();

		$.ajax({
			method: 'GET',
			url: url,
		})
		.done(function( html ) {
			Messagerie.global_content_bloc_message.html(html);
			Messagerie.global_content_bloc_message.hideLoading();
		});
	}
}