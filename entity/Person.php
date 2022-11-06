<?php
require "dataBase/DataBase.php";
/**
 * Author: Mikita Filonau
 * Create date: 06.11.09.55
 * Class create  person
 * TODO:
 * [+] Class for data output.
 * A class that creates an person that works with it.
 * The constructor initializes and creates an entry in the database
 * or takes it from the database by id use class DataBase.
 * This class has conversion methods for fields gender and birthOfDate
 */
class Person
{
    private int $id;
    private string $name;
    private string$secondName;
    private $birthday;
    private int $gender;
    private string $birthOfPlace;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $arguments = func_get_args();

        $dataBase = new DataBase();

        switch (sizeof(func_get_args())) {
            case 1:
                $person = $dataBase::getRow("SELECT * FROM `Person` WHERE `id` = ?", [$arguments[0]]);
                $this->id = $person['id'];
                $this->name = $person['name'];
                $this->secondName = $person['secondName'];
                $this->birthday = $person['birthday'];
                $this->gender = $person['gender'];
                $this->birthOfPlace = $person['birthOfPlace'];
                break;
            case 6:
                $this->id = $arguments[0];
                $this->name = $arguments[1];
                $this->secondName = $arguments[2];
                $this->birthday = $arguments[3];
                $this->gender = $arguments[4];
                $this->birthOfPlace = $arguments[5];

                $this->savingPeopleInDataBase($this->id, $this->name, $this->secondName,
                    $this->birthday, $this->gender, $this->birthOfPlace);
                break;
            default:
                break;
        }
    }

    /**
     * @throws Exception
     */
    private function savingPeopleInDataBase($id, $name, $secondName,
                                    $birthday, $gender, $birthOfPlace
    ): void
    {
        $args = ['id' => $id,
                 'name' => $name,
                 'secondName' => $secondName,
                 'birthday' => $birthday,
                 'gender' => $gender,
                 'birthOfPlace' => $birthOfPlace];
        $dataBase = new DataBase();
        $dataBase::addPeople($args);
    }

    /**
     * @throws Exception
     */
    public function removeOneRecordPeople($id): void
    {
        $dataBase = new DataBase();
        $dataBase::sql("DELETE FROM `Person` WHERE `id` = ?", [$id]);
    }

    /**
     * Formatting a person with age and (or) gender conversion
     * depending on the parameters (returns a new instance
     * stdClass with all fields of the original class)
     * @return stdClass
     */
     public function formattingPeople(): stdClass
    {
        $arguments = func_get_args();
        $obj = new stdClass;
        $obj->id = $this->id;
        $obj->name = $this->name;
        $obj->secondName = $this->secondName;
        $obj->birthOfPlace = $this->birthOfPlace;

        switch ($arguments[0]) {
            case 0:
                $obj->birthday = self::conversionAge(date_parse($this->birthday) ['day'],
                                               date_parse($this->birthday) ['month'],
                                               date_parse($this->birthday) ['year']);
                $obj->gender = $this->gender;
                break;
            case 1:
                $obj->birthday = $this->birthday;
                $obj->gender = self::conversionGender($this->gender);
                break;

            case 2:
                $obj->birthday = self::conversionAge(date_parse($this->birthday) ['day'],
                                               date_parse($this->birthday) ['month'],
                                               date_parse($this->birthday) ['year']);
                $obj->gender = self::conversionGender($this->gender);
                break;
            default:
                break;

        }

        return $obj;

    }

    private static function conversionAge($dob_day, $dob_month, $dob_year): int
    {
        $year = gmdate('Y');
        $month = gmdate('m');
        $day = gmdate('d');
        $days_in_between = (mktime(0, 0, 0, $month, $day, $year)
                - mktime(0, 0, 0, $dob_month, $dob_day, $dob_year)) / 86400;
        $age_float = $days_in_between / 365.242199;

        return (int)($age_float);
    }

    private static function conversionGender($gender): string
    {
        if ($gender == 0) {
            return "women";
        } {
        return "man";
    }
    }

}
