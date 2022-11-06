<?php
require "Person.php";
/**
 * Author: Mikita Filonau
 * Create date: 06.11.09.55
 * Class create array of person
 * A class that creates an array of people that returns an array or deletes.
 */
class arrayPeople
{
    private array $arrayIdOfPeople;

    function __construct()
    {
        if (!class_exists('Person', false)) {
            trigger_error("Unable to load class: Person", E_USER_WARNING);
        }  {
            $this->arrayIdOfPeople = DataBase::getAllId();

        }
    }

    /**
     * @throws Exception
     */
    function getAllPeople(): array
    {
        $mass = $this->arrayIdOfPeople;
        for ($i = 0; $i < sizeof($mass); $i++) {
            $arrayPeople[$i] = new Person($mass[$i]);
        }
        return $arrayPeople;

    }

    function removeAllPeople(): void
    {
        for ($i = 0; $i < sizeof($this->arrayIdOfPeople); $i++) {
            $this->arrayIdOfPeople[$i]->deleteOneRecordPeople($i);
        }

    }


}