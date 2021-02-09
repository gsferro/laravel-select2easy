# Laravel Select2easy

Uso global e generico, de uma forma easy, do plugin select2 ajax utilizando o laravel

Você pode implementar quantos metodos quiser, para chamar a modelo em varias ocaciões dentro do projeto
Ex: Model User.

- sl2Nome: usando o nome como busca
- sl2Email: usando o nome como busca
- sl2Login: usando o nome como login

### Pre-requisitos
    - plugin jquery

### Config 
- Publish 
    php artisan vendo:publish --provider="Gsferro\Select2Easy\Providers\Select2EasyServiceProvider"    
    
- Diretrivas blade    
    Coloque as no seu arquivo de layout
    ``` html
    @select2easyCss()
    @select2easyJs()
    ``` 
    Ou adicione os arquivos css e js de /vendor/select2easy/*
    
### Uso    
    Implemente na model a trait Select2Easy
    Crie uma stact function Sl2NomeMetodo que sera chamado implementação em data-sl2_method ou { sl2_method : ""}
    
- View
    no select coloque a class select2Easy 
    required
    * coloque o attributo data-sl2_method = "nomeDoMetodoEstaticoDaModel" 
     
    * coloque o attributo data-sl2_model = 'caminho\para\Model'
    *  ou coloque o attributo data-sl2_hash = "{{ Crypt::encryptString('caminho\para\Model') }}"
    
- Instancie o plugin no select2easy
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
- Model        
    * import usando: ```use Gsferro\Select2Easy\Http\Traits\Select2Easy```
    * Coloque  a trait ```Select2Easy```
    * crie o nomeDoMetodoEstaticoDaModel passando o term e page
    ex:
>    
``` php

<?php
    use Gsferro\Select2Easy\Http\Traits\Select2Easy
    
    #model
    
    use Select2Easy;
    public static function sl2Name( $term, $page ) // nome usado na view
    {
        // required
        $select2Search = [
            "name",
        ];

        // required
        $select2Text = "name";

        // opc
        $limitPage = 10; // default 6
        // opc
        $extraScopes = ["active"] // scope previously declared 

        return self::select2easy( $term, $page, $select2Search, $select2Text, $limitPage, $extraScopes );
    }
```    