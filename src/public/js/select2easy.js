/**
 * Initializes a select2 dropdown with additional functionality for ajax requests and cascading selects.
 *
 *  @autor Guilherme Ferro
 * @created 03/05/2019
 * @requerid select2 ^4.0.1
 * @requerid jQuery ^3.0
 * @description Adapter para reuso do plugin select2 ajax
 *
 * @param {Object} _param - Optional parameters for customizing the select2 dropdown and ajax requests.
 * @param {string} [_param.sl2_hash] - A hash used for identifying the select2 dropdown.
 * @param {string} [_param.sl2_model] - The model name used for the ajax request.
 * @param {string} [_param.sl2_method] - The method used for the ajax request.
 * @param {string} [_param.sl2_child] - The select2 dropdown that will be cascaded with the current dropdown.
 * @param {number} [_param.minimuminputlength] - The minimum number of characters required for a search.
 * @param {number} [_param.delay] - The delay in milliseconds before sending an ajax request.
 * @param {string} [_param.sl2_parent_id] - The id of the parent select2 dropdown.
 * @return {void}
 *
 * @this input select
 */
jQuery.fn.select2easy = function( _param )
{
	// opcional enviar os dados
	let dados =  $.extend( {
		// sl2_hash 		: '\Illuminate\Support\Facades\Crypt::encryptString('Model')' ,
		// sl2_model   	: 'string Model' ,
		// sl2_method	: 'string Method' ,
		// sl2_child	: 'string Method' ,
		// minimuminputlength 	: 'int select2' ,
		// delay 				: 'int ajax' ,
		// sl2_parent_id 	: 'string Id parent',
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

	// pode ser tanto init, com um valor de busca default quanto ser passado por paramentro
	// qdo ha um pai, qto  valor que sera enviado no evento change e passado para o filho
	let parentId = !!dados.sl2_parent_id ? dados.sl2_parent_id : (select.data( 'sl2_parent_id' ) || '');
	if (parentId.length > 0) {
		urlDataParams += "&parent_id=" + parentId;
	}

	let option = {
		minimumInputLength: typeof minLenght === 'number' ? minLenght : 0,
		placeholder: "Busque aqui...",
		allowClear: true,
		theme: "bootstrap",
		width: '100%',
		ajax: {
			url: "/select2easy?" + urlDataParams,
			dataType: 'json',
			delay: delay ?? 500,
			cache: true,
			data: (params) => {
				return {
					term: $.trim(params.term), // search term
					page: params.page,
				};
			},
			processResults: (r) => {
				return {
					results: r.data.itens,
					pagination: {
						more: r.data.prox
					}
				};
			},
		},
		// markup
		escapeMarkup: function(markup) {
			return markup;
		},
		templateResult: function(data) {
			return data.html;
		},
		templateSelection: function(data) {
			return data.text;
		}
	};

	let options = $.extend( option , dados );

	select.select2( options );

	/*
	|---------------------------------------------------
	|  Cascade functionality
	|---------------------------------------------------
	|
	| Inspired by https://gist.github.com/ajaxray/187e7c9a00666a7ffff52a8a69b8bf31#file-readme-md
	|
	*/
	let child = !!dados.sl2_child ? dados.sl2_child : (select.data('sl2_child') || null);
	if (child !== null) {
		const cascade = parentId => {
			child.prop("disabled", true)
				.select2easy({
					sl2_parent_id :parentId,
					theme: options.theme
				});
			child.prop("disabled", false);
		};
		// encapsula
		child = $(child);
		// pega o valor do select pai (para ver se tem default)
		let parentId = select.find(':selected').val();
		// verifica se o select pai tem valor
		let isParentInit = (parentId === undefined);
		// caso tenha valor, reinicializa o select filho
		if (!isParentInit) {
			$(() => {
				cascade(parentId);
			});
		}
		// disabled child initial
		child.prop("disabled", isParentInit);

		let clear = () => {
			child.empty().trigger('change');
		}
		// on change
		select.on("change", () => {
			clear();
			let id = $(this).find(':selected').val();
			id !== undefined && cascade(id);
		});

		select.on('select2:unselect', () => {
			child.prop("disabled", true);
			clear();
		});
	}
};
