<?php

namespace Powon\Services\Implementation;

use Powon\Dao\InterestDAO;
use Powon\Entity\Member;
use Psr\Log\LoggerInterface;
use Powon\Services\MemberService;
use Powon\Dao\MemberDAO;
use Powon\Utils\DateTimeHelper;
use Powon\Dao\RegionDAO;
use Powon\Dao\ProfessionDAO;

class MemberServiceImpl implements MemberService
{
    /**
     * @var MemberDAO
     */
    private $memberDAO;

    /**
     * @var LoggerInterface
     */
    private $log;

    /**
     * @var InterestDAO
     */
    private $interestDAO;
    
    private $professionDAO;
    private $regionDAO;
    
    
    public function __construct(LoggerInterface $logger, MemberDAO $dao, InterestDAO $interestDAO, ProfessionDAO $professionDAO, RegionDAO $resionDAO)
    {
        $this->memberDAO = $dao;
        $this->log = $logger;
        $this->interestDAO = $interestDAO;
        $this->professionDAO = $professionDAO;
        $this->regionDAO = $resionDAO;
    }

    /**
     * @return Member[] All the members
     */
    public function getAllMembers() {
        try {
            return $this->memberDAO->getAllMembers();
        } catch (\PDOException $ex) {
            $this->log->error("A pdo exception occurred: $ex->getMessage()");
            return [];
        }
    }


    /**
     * @param $username string
     * @param $user_email string
     * @param $password string
     * @param $date_of_birth string
     * @param $first_name string
     * @param $last_name string
     * @return mixed array('success': bool, 'message':string)
     */
    public function registerNewMember($username,
                                      $user_email,
                                      $password,
                                      $date_of_birth,
                                      $first_name,
                                      $last_name)
    {
        if ($this->memberDAO->getMemberByUsername($username)) {
            $this->log->debug("Username $username exists");
            return array('success'=> false, 'message' =>'Username exists.');
        }
        if ($this->memberDAO->getMemberByEmail($user_email)) {
            $this->log->debug("Email $user_email already exists in the system");
            return array('success'=> false, 'message' =>'Email exists.');
        }
        if (!DateTimeHelper::validateDateFormat($date_of_birth)) {
            $this->log->debug("Invalid format for date: $date_of_birth");
            return array('success'=> false, 'message' =>'Date format must be YYYY-MM-DD.');
        }
        $data = array(
            'username' => $username,
            'user_email' => $user_email,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'date_of_birth' => $date_of_birth
        );
        $newMember = new Member($data);
        $pwd_hash = password_hash($password, PASSWORD_BCRYPT);
        try {
            if ($this->memberDAO->createNewMember($newMember, $pwd_hash)) {
                $this->log->info('Registered new member',
                    ['username' => $username, 'email' => $user_email]);
                return array('success' => true,
                    'message' => "New member $username was registered.");
            }
        } catch (\PDOException $ex) {
            $this->log->error("A pdo exception occurred when registering a new user: $ex->getMessage()");
        }
        return array(
            'success' => false,
            'message' => 'Something went wrong!'
        );
    }

    /**
     * Updates the provided member entity with the correct interests
     * @param $member Member
     * @return bool
     */
    public function populateInterestsForMember($member)
    {
        $interests = [];
        try {
            $interests = $this->interestDAO->getInterestsForMember($member->getMemberId());
        } catch (\PDOException $ex) {
            $this->log->error("PDO Exception $ex->getMessage().");
            return false;
        }
        $member->setInterests($interests);
        return true;
    }
}
