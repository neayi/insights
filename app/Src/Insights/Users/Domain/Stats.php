<?php


namespace App\Src\Insights\Users\Domain;


class Stats
{
    private $stats;

    private $fields = [
        'number_contributions',
        'number_questions',
        'number_answers',
        'number_votes',
        'number_validations',
        'number_wiki_edit',
        'number_contributions_last_30_days'
    ];

    public function __construct(array $data)
    {
        foreach ($this->fields as $field){
            $this->stats[$field] = $this->dataOrZero($field, $data);
        }
    }

    private function dataOrZero(string $key, array $data):int
    {
        return isset($data[$key]) ? $data[$key] : 0;
    }

    public function toArray():array
    {
        return $this->stats;
    }
}
