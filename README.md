# Laravel Select2easy

Uso global e generico do plugin select2 ajax utilizando o laravel

### pre-requisitos
    - plugin Select2

### publush 
    php artisan vendo:publish --provider="Gsferro\Select2Easy\Providers\Select2EasyServiceProvider"    
    
### Uso    
    * Implemente na model a trait ``select2Ajax``
    * Crie uma stact function Sl2NomeMetodo que sera chamado implementação em ``data-sl2-method``
    
- view
    * no select coloque a Classe selectAjax 
    * coloque o attributo data-sl2-method = "nomeDoMetodoEstaticoDaModel" required

    * coloque o attributo data-sl2-model = 'caminho\para\Model'
     ou 
    * coloque o attributo data-sl2-hash = "{{ Crypt::encryptString('caminho\para\Model') }}"
    
    * instancie o plugin no select
>
``` javascript    
    <script type="text/javascript">
        $( function() {
            $( '.selectAjax' ).select2Ajax( {
                // minimumInputLength : 2 ,
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
    public static function sl2Sigla( $term, $page )
    {
        // required
        $select2Search = [
            "sigla",
            "descricao",
            "sigla_old",
            "ug",
            "ugr"
        ];

        // required
        $select2Text = "sigla";

        // opc
        $limitPage = 10; // default 6

        return self::select2ajax( $term, $page, $select2Search, $select2Text, $limitPage );
    }
```    