![Logo](logo.png)

### Instalação:

```composer 
composer require gsferro/select2easy -W
```

### Pacotes Dependências:
Package | Versão
--------|-----------
jquery | 3.*

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
                // required
                $select2Search = [
                    "name",
                ];
        
                // required
                $select2Text = "name";
        
                // opc
                $limitPage = 10; // default 6
                // opc
                $extraScopes = ["active"]; // scope previously declared 
        
                return self::select2easy( $term, $page, $select2Search, $select2Text, $limitPage, $extraScopes );
            }
        }
    ```