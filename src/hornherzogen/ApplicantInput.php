<?php
declare(strict_types = 1);
namespace hornherzogen;

/**
 * Class ApplicantInput
 * holds any validation errors when processing user data extracted from $_POST[].
 * @package hornherzogen
 */
final class ApplicantInput extends Applicant
{
    private $errors = array();
    private $success = array();
    // To prevent double click attacks we only send out mails if not sent before
    private $mailSent;

    // this field is only visible in the form and is not persisted anywhere
    private $emailcheck;

    function __construct()
    {
        parent::__construct();
        $this->formHelper = new FormHelper();
        $this->mailSent = false;
        // set default country to Germany
        $this->setCountry('DE');
    }

    /**
     * @return bool true iff no parsing happened and neither errors nor successes are reported.
     */
    public function hasParsingHappened()
    {
        return 0 != sizeof($this->errors) && 0 != sizeof($this->success);
    }

    /**
     * @return bool
     */
    public function isMailSent()
    {
        return $this->mailSent;
    }

    /**
     * @param bool $mailSent
     * @return ApplicantInput
     */
    public function setMailSent($mailSent)
    {
        $this->mailSent = $mailSent;
        return $this;
    }

    /**
     * Extracts data from $_POST[], parses it and resets internal error/success counter.
     */
    public function parse()
    {
        // reset internal state
        $this->errors = array();
        $this->success = array();

        if ($this->formHelper->isSetAndNotEmpty("week")) {
            $this->setWeek($this->getFromPost("week"));
            $this->addSuccess("week");
        } else {
            $this->addError("week");
        }

        if ($this->formHelper->isSetAndNotEmpty("flexible")) {
            $this->setFlexible($this->getFromPost("flexible"));
            $this->addSuccess("flexible");
        } else {
            $this->addError("flexible");
        }

        if ($this->formHelper->isSetAndNotEmpty("gender")) {
            $this->setGender($this->getFromPost("gender"));
            $this->addSuccess("gender");
        } else {
            $this->addError("gender");
        }

        if ($this->formHelper->isSetAndNotEmpty("vorname")) {
            $this->setFirstname($this->formHelper->filterUserInput($_POST["vorname"]));
            $this->addSuccess("vorname");
        } else {
            $this->addError("vorname");
        }

        if ($this->formHelper->isSetAndNotEmpty("nachname")) {
            $this->setLastname($this->formHelper->filterUserInput($_POST["nachname"]));
            $this->addSuccess("nachname");
        } else {
            $this->addError("nachname");
        }

        // do this again when flushing to the database
        $this->setFullName('');

        if ($this->formHelper->isSetAndNotEmpty("street")) {
            $this->setStreet($this->getFromPost("street"));
            $this->addSuccess("street");
        } else {
            $this->addError("street");
        }

        if ($this->formHelper->isSetAndNotEmpty("houseno")) {
            $this->setHouseNumber($this->getFromPost("houseno"));
            $this->addSuccess("houseno");
        } else {
            $this->addError("houseno");
        }

        if ($this->formHelper->isSetAndNotEmpty("plz")) {
            $this->setZipCode($this->getFromPost("plz"));
            $this->addSuccess("plz");
        } else {
            $this->addError("plz");
        }

        if ($this->formHelper->isSetAndNotEmpty("city")) {
            $this->setCity($this->getFromPost("city"));
            $this->addSuccess("city");
        } else {
            $this->addError("city");
        }

        if ($this->formHelper->isSetAndNotEmpty("country")) {
            $this->setCountry($this->getFromPost("country"));
            $this->addSuccess("country");
        } else {
            $this->addError("country");
        }

        if ($this->formHelper->isSetAndNotEmpty("email")) {
            $this->setEmail($this->getFromPost("email"));
            $this->addSuccess("email");
        } else {
            $this->addError("email");
        }

        if ($this->formHelper->isSetAndNotEmpty("emailcheck")) {
            $this->setEmailcheck($this->getFromPost("emailcheck"));
            $this->addSuccess("emailcheck");
        } else {
            $this->addError("emailcheck");
        }

        // definitely switch to error state if values are not equal
        if (!$this->areEmailAddressesValid()) {
            $this->addError("email");
            $this->addError("emailcheck");
        }

        if ($this->formHelper->isSetAndNotEmpty("dojo")) {
            $this->setDojo($this->getFromPost("dojo"));
            $this->addSuccess("dojo");
        } else {
            $this->addError("dojo");
        }

        if ($this->formHelper->isSetAndNotEmpty("twano")) {
            $this->setTwaNumber($this->getFromPost("twano"));
            $this->addSuccess("twano");
        }

        if ($this->formHelper->isSetAndNotEmpty("grad")) {
            $this->setGrading($this->getFromPost("grad"));
            $this->addSuccess("grad");
        } else {
            $this->addError("grad");
        }

        if ($this->formHelper->isSetAndNotEmpty("gsince")) {
            $this->setDateOfLastGrading($this->getFromPost("gsince"));
            $this->addSuccess("gsince");
        } else {
            $this->addError("gsince");
        }

        if ($this->formHelper->isSetAndNotEmpty("room")) {
            $this->setRoom($this->getFromPost("room"));
            $this->addSuccess("room");
        } else {
            $this->addError("room");
        }

        if ($this->formHelper->isSetAndNotEmpty("together1")) {
            $this->setPartnerOne($this->getFromPost("together1"));
            $this->addSuccess("together1");
        }

        if ($this->formHelper->isSetAndNotEmpty("together2")) {
            $this->setPartnerTwo($this->getFromPost("together2"));
            $this->addSuccess("together2");
        }

        if ($this->formHelper->isSetAndNotEmpty("essen")) {
            $this->setFoodCategory($this->getFromPost("essen"));
            $this->addSuccess("essen");
        } else {
            $this->addError("essen");
        }

        if ($this->formHelper->isSetAndNotEmpty("additionals")) {
            $this->setRemarks($this->getFromPost("additionals"));
            $this->addSuccess("additionals");
        }

        return $this;
    }

    /**
     * You need to check its existence first!
     * @param $key
     * @return string
     */
    private function getFromPost($key)
    {
        return $this->formHelper->filterUserInput($_POST[$key]);
    }

    public function addSuccess($field)
    {
        if (!in_array($field, $this->success)) {
            array_push($this->success, $field);
        }
    }

    public function addError($field)
    {
        if (!in_array($field, $this->errors)) {
            array_push($this->errors, $field);
        }
    }

    function areEmailAddressesValid()
    {
        if ($this->formHelper->isSetAndNotEmpty("email") && $this->formHelper->isSetAndNotEmpty("emailcheck")) {
            $mail = $this->getFromPost("email");
            $mailCheck = $this->getFromPost("emailcheck");
            return $mail === $mailCheck && $this->formHelper->isValidEmail($mail);
        }
        return false;
    }

    public function __toString()
    {
        // var_dump ruins the formatting
        $msg = "ERROR: " . var_dump($this->errors);
        $msg .= "- SUCCESS: " . var_dump($this->success);
        $msg .= " hasParseErrors? " . boolval($this->hasParseErrors());
        $msg .= " hasErrors? " . boolval($this->hasErrors());
        return $msg;
    }

    /**
     * @return bool true iff errors is empty and no required fields are missing.
     */
    public function hasParseErrors()
    {
        foreach ($this->errors as $value) {
            if (in_array($value, self::getRequiredFields())) {
                return true;
            }
        }

        return 0 != sizeof($this->errors);
    }

    static function getRequiredFields()
    {
        /**
         * These fields are required from the UI perspective and need to be set.
         * @var array
         */
        $required = array();
        $required[] = "week";
        $required[] = "flexible";
        $required[] = "gender";
        $required[] = "firstname";
        $required[] = "lastname";
        $required[] = "street";
        $required[] = "houseno";
        $required[] = "plz";
        $required[] = "city";
        $required[] = "country";
        $required[] = "email";
        $required[] = "dojo";
        $required[] = "grad";
        $required[] = "gsince";
        $required[] = "room";
        $required[] = "essen";
        return $required;
    }

    public
    function hasErrors()
    {
        return empty($this->getWeek()) || $this->getFlexible() || empty($this->getGender()) //
            || empty($this->getFirstname()) || empty($this->getLastname()) || empty($this->getStreet()) //
            || empty($this->getHouseNumber()) || empty($this->getZipCode()) || empty($this->getCity()) //
            || empty($this->getCountry()) || empty($this->getEmail()) || empty($this->getDojo()) //
            || empty($this->getGrading()) || empty($this->getDateOfLastGrading()) //
            || empty($this->getRoom()) || empty($this->getFoodCategory());
    }

    public
    function getErrorCount()
    {
        return sizeof($this->errors);
    }

    public
    function getUIResponse($field)
    {
        if (!empty($this->showHasError($field))) {
            return $this->showHasError($field);
        }
        return $this->showIsSuccess($field);
    }

    public
    function showHasError($field)
    {
        if (in_array($field, $this->errors)) {
            return ' has-error has-feedback';
        }
        return '';
    }

    public
    function showIsSuccess($field)
    {
        if (in_array($field, $this->success)) {
            return ' has-success has-feedback';
        }
        return '';
    }

    public
    function showSymbolIfFeedback($field)
    {
        // http://getbootstrap.com/css/#forms-control-validation
        if (in_array($field, $this->errors)) {
            return
                '<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>';
        }

        if (in_array($field, $this->success)) {
            return
                '<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>';
        }
        return '';
    }

    /**
     * @return mixed
     */
    public
    function getEmailcheck()
    {
        return $this->emailcheck;
    }

    /**
     * @param mixed $emailcheck
     */
    public
    function setEmailcheck($emailcheck)
    {
        $this->emailcheck = $emailcheck;
    }

}
