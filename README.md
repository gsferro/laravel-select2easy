# Laravel Select2easy

Uso global e generico do plugin select2 ajax utilizando o laravel

### pre-requisitos
    - plugin Select2

### publush 
    php artisan vendo:publish --provider="Gsferro\Select2Easy\Providers\Select2EasyServiceProvider"    
    
### diretrivas blade    
    Coloque as no seu arquivo de layout
     @select2easyCss()
     @select2easyJs()
     
     ou adicione os arquivos css e js de /vendor/select2easy/*
    
### Uso    
    * Implemente na model a trait ``select2Ajax``
    * Crie uma stact function Sl2NomeMetodo que sera chamado implementação em ``data-sl2-method``
    
- view
    * no select coloque a Classe selectAjax 
    * required
        * coloque o attributo data-sl2_method = "nomeDoMetodoEstaticoDaModel" 

    * e 
        * coloque o attributo data-sl2_model = 'caminho\para\Model' ou
        * coloque o attributo data-sl2_hash = "{{ Crypt::encryptString('caminho\para\Model') }}"
    
    * instancie o plugin no select
>
``` javascript    
    <script type="text/javascript">
        $( function() {
            $( '#select2easy' ).select2easy( {
                // select2
                // minimumInputLength : 2 ,

                // select2eay server side
                // sl2_method 	: 'string Method' ,
                // sl2_hash  	: '\Illuminate\Support\Facades\Crypt::encryptString('Model')' ,
                // ou
                // sl2_model    : 'string Model' ,
            } );
        } )
    </script>
```    
- model        
    * use a trait select2Ajax
    * crie o nomeDoMetodoEstaticoDaModel passando o term e page
    ex:
>    
``` php

    use Select2Easy;
    public static function sl2( $term, $page ) // nome usado na view
    {
        // required
        $select2Search = [
            "name",
        ];

        // required
        $select2Text = "name";

        // opc
        $limitPage = 10; // default 6

        return self::select2easy( $term, $page, $select2Search, $select2Text, $limitPage );
    }
```    