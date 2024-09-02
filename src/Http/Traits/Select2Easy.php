<?php

namespace Gsferro\Select2Easy\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait Select2Easy
{
    /**
     * Executes a select2easy search on the given query with the provided parameters.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query  The query to search on.
     * @param  string  $term  The search term to look for.
     * @param  int  $page  The page number of the results to fetch.
     * @param  array  $select2Search  An array of fields to search on.
     * @param  string  $select2Text  The field to display as the text in the select2 dropdown.
     * @param  int  $limitPage  The maximum number of results to fetch per page. Default is 6.
     * @param  array  $extraScopes  An array of extra scopes to apply to the query. Default is an empty array.
     * @param  string|null  $prefix  A prefix to add to the result IDs. Default is null.
     * @param  array  $scopeParentAndId  An array of parent scopes and their IDs to apply to the query. Default is an
     *     empty array.
     * @param  string|null  $suffix  A suffix to add to the result IDs. Default is null.
     * @param  array  $markups  An array of markups to apply to the results. Default is an empty array.
     *
     * @return mixed The result set of the select2easy search.
     */
    public function scopeSelect2easy(
        $query,
        string $term,
        int $page,
        array $select2Search,
        string $select2Text,
        int $limitPage = 6,
        array $extraScopes = [],
        string $prefix = null,
        array $scopeParentAndId = [],
        string $suffix = null,
        array $markups = [],
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

        // add scope parent with id na query
        if (!empty($scopeParentAndId)) {
            foreach ($scopeParentAndId as $scope => $id) {
                $query = $query->$scope($id);
            }
        }

        // seta a posição
        $offset = ($page - 1) * $limitPage;

        // busca a pagina
        $search = $query->skip($offset)->take($limitPage)->get();

        // envia pro processamento e devolve pro plugin
        return $this->select2EasyResultSet($search, $select2Text, $limitPage, $prefix, $suffix, $markups) ;
    }

    /**
     * Generates a result set for the select2Easy function.
     *
     * @param  Collection  $search  The collection of items to search.
     * @param  string  $select2Text  The field to use for the text display.
     * @param  int  $limitPage  The maximum number of items per page.
     * @param  string|null  $prefix  The prefix to add to the text display.
     * @param  string|null  $suffix  The suffix to add to the text display.
     * @param  array  $markups  An array of markups to apply to the results. Default is an empty array.
     * @return array The result set containing the items and the next page flag.
     */
    protected function select2EasyResultSet(
        Collection $search,
        string $select2Text,
        int $limitPage,
        string $prefix = null,
        string $suffix = null,
        array $markups = [],
    ) {
        // set array return
        $resultSet = [];
        $textPrefix = '';

        // pega os itens
        foreach ($search as $elem) {
            // default
            $text = $elem->{$select2Text};

            // with relation
            if (strpos($select2Text, '.')) {
                list($rel, $field) = explode('.', $select2Text);
                $text = $elem->$rel->$field;
            }

            // with prefix
            if (!is_null($prefix)) {
                $prefixParts = explode('.', $prefix, 2);
                $prefixValue = count($prefixParts) == 1 ? $elem->$prefix : $elem->{$prefixParts[0]}->{$prefixParts[1]};
                $text = $prefixValue.' - '.$text;
            }

            // with sufix
            if (!is_null($suffix)) {
                $sufixParts = explode('.', $suffix, 2);
                $suffixValue = count($sufixParts) == 1 ? $elem->$suffix : $elem->{$sufixParts[0]}->{$sufixParts[1]};
                $text = $text.' - '.$suffixValue;
            }

            // with markups
            $htmlFunc = $markups['html'] ?? null;
            $textFunc = $markups['text'] ?? null;

            // runner functions
            $viewText = !isset($textFunc) ? $text : $this->$textFunc($text, $elem);
            $viewHtml = !isset($htmlFunc) ? $text : $this->$htmlFunc($text, $elem);

            // result set
            $resultSet["itens"][] = [
                'id' => $elem->{$this->primaryKey},
                'text' => $viewText,
                'html' => $viewHtml,
                'title' => $text,
            ];
        }

        // proxima pagina bool
        $resultSet["prox"] = count($search) == $limitPage;

        return $resultSet;
    }
}
