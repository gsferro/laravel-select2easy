<?php

namespace Gsferro\Select2Easy\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class Select2EasyController extends Controller
{
    public function __invoke( Request $request )
    {
        // encapsulamento
        $dados = array_map( 'trim', $request->all() );

        #################
        # validações request
        // pegando o termo
        $term = $dados[ "term" ];
        if( blank( $term ) ) return $this->sendError( "Pesquisa obrigatório!" );

        ################### verifica paramentros
        $model = false;

        # Obrigação de enviar a model
        // encrypt
        $dataHash = $dados[ "hash" ];
        if( filled( $dataHash ) ) $model = Crypt::decryptString( $dataHash ); // pegando a model enviada e decodificando

        // string
        $dataModel = $dados[ "model" ];
        if( filled( $dataModel ) ) $model = $dataModel; // pegando a model enviada e decodificando

        // verifica se veio de algum meio
        if( blank( $model ) ) return $this->sendError( "Hash obrigatório!" );

        # validações model
        // se a classe existe
        if( !class_exists( $model ) ) return $this->sendError( "Hash inválido!" );
        // ve se não tem no array de class_uses o nome da Traits Select2Ajax
        if( !preg_grep( '/Select2Easy/', array_keys( class_uses( $model ) ) ) ) return $this->sendError( "Select2 não autorizado!" );

        // para o metodo da model
        $dataMethod = $dados[ "method" ];
        if( blank( $dataMethod ) ) return $this->sendError( "metodo obrigatório!" );
        if( !method_exists( $model, $dataMethod ) ) return $this->sendError( "Metodo inválido!" );
        #################

        # run
        return $this->sendSuccess( $model::$dataMethod( $term, $dados[ "page" ] ?? 1 ) );
    }

    private function sendError( $error, array $data = [], int $code = 404 )
    {
        $res = [
            'success' => false,
            'message' => $error,
        ];

        if( !empty( $data ) ) $res[ 'data' ] = $data;

        return $this->send( $res, $code );
    }

    private function sendSuccess( $result, $message = "" )
    {
        return $this->send([
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ] );
    }

    private function send( array $res, int $code = 200 )
    {
        return response()->json( $res, $code );
    }
}
