<?php

namespace Gsferro\Select2Easy\Http\Traits;

use Illuminate\Support\Collection;

trait Select2Easy
{
    /**
     * @param $query
     * @param string $term
     * @param int $page
     * @param array $select2Search
     * @param string $select2Text
     * @param int $limitPage     minimo de 6 para paginação pelo plugin select2
     * @param array $extraScopes nome do scope
     * @param string|null $prefix
     * @return array
     */
    public function scopeSelect2easy(
        $query,
        string $term,
        int $page,
        array $select2Search,
        string $select2Text,
        int $limitPage = 6,
        array $extraScopes = [],
        string $prefix = null
    ) {
        // varre os campos que podem ser pesquisados
        foreach ($select2Search as $field) {
            // with relation
            if (strpos($field, '.')) {
                list($rel, $row) = explode('.', $field);
                $query->whereHas($rel, function ($q) use ($row, $term) {
                    return empty($term) ? $q : $q->where($row, 'LIKE', "%{$term}%");
                });

                continue;
            }

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
        return $this->select2EasyResultSet($search, $select2Text, $limitPage, $prefix);
    }

    /**
     * @param Collection $search
     * @param string $select2Text
     * @param int $limitPage
     * @param string|null $prefix
     * @return array
     */
    protected function select2EasyResultSet(
        Collection $search,
        string $select2Text,
        int $limitPage,
        string $prefix = null
    ) {
        // set array return
        $resultSet  = [];
        $textPrefix = '';

        // pega os itens
        foreach ($search as $elem) {
            // todo v2.0 - cria o array repo para uso de markup
            //$resultSet[ "repo" ][] = $elem;

            // default
            $text = $elem->{$select2Text};

            // with relation
            if (strpos($select2Text, '.')) {
                list($rel, $field) = explode('.', $select2Text);
                $text = $elem->$rel->$field;
            }

            // with prefix
            if (!is_null($prefix) && strpos($prefix, '.')) {
                list($relPrefix, $fieldPrefix) = explode('.', $prefix);
                $text = $elem->$relPrefix->$fieldPrefix . ' - ' . $text;
            }

            // item de exibição padrão
            $resultSet[ "itens" ][] = [
                'id'   => $elem->{$this->primaryKey},
                'text' => $text,
            ];
        }

        // proxima pagina bool
        $resultSet[ "prox" ] = count($search) == $limitPage;

        return $resultSet;
    }
}
