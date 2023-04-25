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
jQuery.fn.select2easy = function( _param ) {

	// opcional enviar os dados
	let dados =  $.extend( {
		// sl2_hash  			: '\Illuminate\Support\Facades\Crypt::encryptString('Model')' ,
		// sl2_model    		: 'string Model' ,
		// sl2_method 			: 'string Method' ,
		// sl2_method 			: 'string Method' ,
		// minimuminputlength 	: 'int select2' ,
		// delay 				: 'int ajax' ,
	} , _param );

	let select = $( this );
	// model
	let hash   = !!dados.sl2_hash   ? dados.sl2_hash 	: (select.data( 'sl2_hash' ) 	|| '');
	let model  = !!dados.sl2_model  ? dados.sl2_model 	: (select.data( 'sl2_model' ) 	|| '');
	let method = !!dados.sl2_method ? dados.sl2_method 	: (select.data( 'sl2_method' ) 	|| '');
	// select2
	let minLenght = !!dados.minimuminputlength ? dados.minimuminputlength : (select.data( 'minimuminputlength' ) || false);
	// ajax
	let delay = !!dados.delay ? dados.delay : (select.data( 'delay' ) || 500);

	// encapsulamento
	let urlDataParams = "hash=" + hash + "&model=" + model + "&method=" + method;

	let option = {
		minimumInputLength : typeof minLenght === 'number' ? minLenght : 0 ,
		placeholder        : "Busque aqui..." ,
		allowClear         : true ,
		theme              : "bootstrap" ,
		width              : '100%' ,
		ajax               : {
			url            : "/select2easy?" + urlDataParams ,
			dataType       : 'json' ,
			delay          : delay ?? 500 ,
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

	let options = $.extend( option , dados );

	select.select2( options );
};
