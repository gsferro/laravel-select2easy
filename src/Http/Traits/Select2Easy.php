<?php

/**
 * @author  Guilherme Ferro
 * @version 1.0
 * @action  Facilitar o uso de forma generica para uso do plugn select2 ajax
 */

namespace Gsferro\Select2Easy\Http\Traits;

use Illuminate\Support\Collection;

trait Select2Easy
{
    // minimo de 6 para paginação pelo plugin select2
    /**
     * @param $query
     * @param string $term
     * @param int $page
     * @param array $select2Search
     * @param string $select2Text
     * @param int $limitPage
     * @param array $extraScopes nome do scope
     * @return array
     */
    public function scopeSelect2easy(
        $query,
        string $term,
        int $page,
        array $select2Search,
        string $select2Text,
        int $limitPage = 6,
        array $extraScopes = []
    ) {
        // varre os campos que podem ser pesquisados
        foreach ($select2Search as $field) {
            $query->when($field, function ($q) use ($field, $term) {
                return $q->orWhere($field, 'LIKE', "%{$term}%");
            });
        }

        // add extra scope na query
        if (!empty($extraScopes)) {
            foreach ($extraScopes as $extraScope) {
                $query = $query->$extraScope();
            }
        }

        // seta a posição
        $offset = ($page - 1) * $limitPage;
        // busca a pagina
        $search = $query->skip($offset)->take($limitPage)->get();

        // envia pro processamento e devolve pro plugin
        return $this->select2EasyResultSet($search, $select2Text, $limitPage);
    }

    /**
     * @param Collection $search
     * @param $select2Text
     * @param $limitPage
     * @return array
     */
    private function select2EasyResultSet(Collection $search, $select2Text, $limitPage)
    {
        // set array return
        $resultSet = [];

        // pega os itens
        foreach ($search as $elem) {
            // todo v2.0 - cria o array repo para uso de markup
            //$resultSet[ "repo" ][] = $elem;

            // item de exibição padrão
            $resultSet[ "itens" ][] = [
                'id'   => $elem->{$this->primaryKey},
                'text' => $elem->{$select2Text},
            ];
        }

        // proxima pagina bool
        $resultSet[ "prox" ] = count($search) == $limitPage;

        return $resultSet;
    }
}
