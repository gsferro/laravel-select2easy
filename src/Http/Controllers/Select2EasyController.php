<?php

namespace Gsferro\Select2Easy\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class Select2EasyController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function __invoke(Request $request)
    {
        // encapsulamento
        $dados = array_map('trim', $request->all());

        #################
        # validações request
        // pegando o termo
        $term = $dados[ "term" ];
//        if (blank($term)) {
//            return $this->sendError("Pesquisa obrigatório!");
//        }

        ################### verifica paramentros
        $model = false;

        # Obrigação de enviar a model
        // encrypt
        $dataHash = $dados[ "hash" ];
        if (filled($dataHash)) {
            // pegando a model enviada e decodificando
            $model = Crypt::decryptString($dataHash);
        }

        // string
        $dataModel = $dados[ "model" ];
        if (filled($dataModel)) {
            // pegando a model enviada e decodificando
            $model = $dataModel;
        }

        // verifica se veio de algum meio
        if (blank($model)) {
            return $this->sendError("Hash obrigatório!");
        }

        # validações model
        // se a classe existe
        if (!class_exists($model)) {
            return $this->sendError("Hash/Model inválido!");
        }

        // ve se não tem no array de class_uses o nome da Traits Select2Easy
        if (!method_exists((new $model), 'scopeSelect2easy')) {
            return $this->sendError("Select2 não autorizado!");
        }

        // para o metodo da model
        $dataMethod = $dados[ "method" ];
        if (blank($dataMethod)) {
            return $this->sendError("metodo obrigatório!");
        }
        if (!method_exists($model, $dataMethod)) {
            return $this->sendError("Metodo inválido!");
        }
        #################

        # run
        return $this->sendSuccess($model::$dataMethod($term, $dados[ "page" ] ?? 1));
    }

    /**
     * @param $error
     * @param array $data
     * @param int $code
     * @return mixed
     */
    private function sendError($error, array $data = [], int $code = 404)
    {
        $res = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($data)) {
            $res[ 'data' ] = $data;
        }

        return $this->send($res, $code);
    }

    /**
     * @param $result
     * @param string $message
     * @return mixed
     */
    private function sendSuccess($result, $message = "")
    {
        return $this->send([
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ]);
    }

    /**
     * @param array $res
     * @param int $code
     * @return mixed
     */
    private function send(array $res, int $code = 200)
    {
        return response()->json($res, $code);
    }
}
