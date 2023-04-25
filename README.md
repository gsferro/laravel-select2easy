![Logo](logo.png)

### Instalação:

```composer 
composer require gsferro/select2easy -W
```

### Pacotes Dependências:
Package | Versão
--------|-----------
jquery | ^3.*
Select2 | ^4.0.13

### Publish
```composer 
php artisan vendor:publish --provider="Gsferro\Select2Easy\Providers\Select2EasyServiceProvider" --force
```

### Config
- Diretrivas blade, Coloque as no seu arquivo de layout    
    
``` text
    @select2easyCss()
    @select2easyJs()
``` 
    
### Uso

- Você pode implementar quantos metodos quiser, para chamar a modelo em varias ocaciões dentro do projeto
    Ex: Model User.
    
    - `sl2Nome`: usando o nome como busca
    - `sl2Email`: usando o nome como busca
    - `sl2Login`: usando o nome como login

- Implemente na model a trait Select2Easy
- Crie uma stact function `Sl2<NomeMetodo>` que sera chamado na implementação em `data-sl2_method` ou `{ sl2_method : ""}`
    

- Na View:
    - no select coloque a class select2Easy (required)
    * coloque o attributo data-sl2_method = "nomeDoMetodoEstaticoDaModel" 
    * coloque o attributo data-sl2_model = 'caminho\para\Model'
    *  ou coloque o attributo data-sl2_hash = "{{ Crypt::encryptString('caminho\para\Model') }}"
    - Exemplo:
    ```html
    <label for="select2easy">Select2 Easy:</label>
    <select id="select2easy" name="select2easy" class="form-control select2easy"
        data-sl2_method="sl2"
        data-sl2_hash="{{ Crypt::encryptString('App\Models\Teams') }}" <!-- recommend -->
        <!-- ou -->
        data-sl2_model="App\Models\Teams"
  
        data-minimumInputLength=2
        data-delay=1000
    >
    </select>
    ```
    
- Instancie o plugin no select2easy

    ``` javascript    
    <script type="text/javascript">
        $( function() {
            $( '#select2easy' ).select2easy( {
                // select2
                // minimumInputLength : 2 ,
    
                // ajax
                // delay : 1000 ,
    
                // select2eay server side
                // sl2_method : 'string Method' ,
                // sl2_hash   : 'Crypt::encryptString('App\Models\Teams')' , // recommend
                
                // ou
                // sl2_model : 'App\Models\Teams' ,
            } );
        } )
    </script>
    ```    
- Model        
    * import usando: ```use Gsferro\Select2Easy\Http\Traits\Select2Easy```
    * Coloque  a trait ```Select2Easy```
    * crie o `nomeDoMetodoEstaticoDaModel` passando o term e page
    - Exemplo:

    ``` php
    <?php
        use Gsferro\Select2Easy\Http\Traits\Select2Easy
        
        class Teams extends Model
        {
            use Select2Easy;
      
            public static function sl2Name( $term, $page ) // nome usado na view
            {
                /*
                |---------------------------------------------------
                | Required
                |---------------------------------------------------
                |
                | $select2Search - colum from search
                | $select2Text - colum from write selectbox
                |
                */
                $select2Search = [
                    "name",
                    // with relation
                    'relation.title'
                ];
        
                // required
                $select2Text = "name";
                
                /*
                |---------------------------------------------------
                | Optional exemple
                |---------------------------------------------------
                |
                | $limitPage - limit view selectbox, default 6
                | $extraScopes - array with scopes
                | $prefix - prefix for before $select2Text
                |
                */
                $limitPage   = 10; // default 6
                $extraScopes = ["active"] // scope previously declared 
                $prefix      = 'otherRelation.description';
                
                return self::select2easy($term, $page, $select2Search, $select2Text, $limitPage, $extraScopes, $prefix);
            }
        }
    ```    

### Selected

- Links do plugin
    - https://select2.org/data-sources/ajax#default-pre-selected-values
    - https://select2.org/programmatic-control/add-select-clear-items

- melhor opção:
```html
<select id="select2easy" name="select2easy" class="form-control select2easy"
      data-sl2_method="sl2"
      data-sl2_hash="{{ Crypt::encryptString('App\Models\Teams') }}" <!-- recommend -->
>
    <option value="{{ $model->teams_id }}" selected>{{ \App\Models\Teams::find($model->teams_id)->name }}</option>
    <!-- ou usar via relacionamento (se não for 1xN ou NxN -->
    <option value="{{ $model->teams->id }}" selected>{{ $model->teams->name }}</option>
    <!-- prefix -->
    <option value="{{ $model->teams->id }}" selected>{{ $model->teams->id }} - {{ $model->teams->name }}</option>
</select>
```

### License
Laravel Localization is an open-sourced laravel package licensed under the MIT license