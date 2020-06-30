/**
 * @autor Guilherme Ferro
 * @created 03/05/2019
 * @version 1.3
 * @release 1.1 - mudando para data hash
 * @release 1.2 - retorno dentro do padrão sendResponse
 * @release 1.3 - adc mais opções de enviar dados via data-*
 * @requerid jQuery 2.2.1
 * @requerid Bootstrap 3.3.6
 * @requerid select2 4.0.1
 * @description Adapter para reuso do plugin select2 ajax
 * @this input select
 ----------
 */
jQuery.fn.select2easy = function( param ) {
	let select = $( this );

	let hast   = select.data( 'sl2-hash' ) || '';
	let model  = select.data( 'sl2-model' ) || '';
	let method = select.data( 'sl2-method' ) || '';

	let urlDataParams = "hash=" + hast + "&model=" + model + "&method=" + method;

	let option = {
		minimumInputLength : 1 ,
		placeholder        : "Busque aqui..." ,
		allowClear         : true ,
		theme              : "bootstrap" ,
		width              : '100%' ,
		ajax               : {
			url            : "select2easy?" + urlDataParams ,
			dataType       : 'json' ,
			delay          : 250 ,
			cache          : true ,
			data           : ( params ) => {
				return {
					term : $.trim( params.term ) , // search term
					page : params.page
				};
			} ,
			processResults : ( r ) => {
				return {
					results    : r.data.itens ,
					pagination : {
						more : r.data.prox
					}
				};
			} ,
		} ,
		// escapeMarkup      : function( markup ) { return markup; } ,
		// templateResult    : formatRepo ,
		// templateSelection : formatRepoSelection
	};

	/* TODO v2.0 - disponibilizar format repo
	function formatRepo (repo) {
		if (repo.loading) {
			return repo.text;
		}

		var markup = "<div class='select2-result-repository clearfix'>" +
					 "<div class='select2-result-repository__avatar'><img src='" + repo.owner.avatar_url + "' /></div>" +
					 "<div class='select2-result-repository__meta'>" +
					 "<div class='select2-result-repository__title'>" + repo.full_name + "</div>";

		if (repo.description) {
			markup += "<div class='select2-result-repository__description'>" + repo.description + "</div>";
		}

		markup += "<div class='select2-result-repository__statistics'>" +
				  "<div class='select2-result-repository__forks'><i class='fa fa-flash'></i> " + repo.forks_count + " Forks</div>" +
				  "<div class='select2-result-repository__stargazers'><i class='fa fa-star'></i> " + repo.stargazers_count + " Stars</div>" +
				  "<div class='select2-result-repository__watchers'><i class='fa fa-eye'></i> " + repo.watchers_count + " Watchers</div>" +
				  "</div>" +
				  "</div></div>";

		return markup;
	}

	function formatRepoSelection (repo) {
		return repo.full_name || repo.text;
	}*/

	let myParam = param || {};
	let options = $.extend( option , myParam );

	select.select2( options );
};
