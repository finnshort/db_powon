<?php

namespace Powon\Dao;

use Powon\Dao\Implementation\GroupDaoImpl;
use Powon\Dao\Implementation\IsGroupMemberDAOImpl;
use Powon\Dao\Implementation\MemberDaoImpl;
use Powon\Dao\Implementation\PostDAOImpl;
use Powon\Dao\Implementation\SessionDAOImpl;

class DAOFactory
{
    /**
     * @var \PDO instance
     */
    private $conn;

    public function __construct(\PDO $pdo)
    {
        $this->conn = $pdo;
    }

    /**
     * @return MemberDAO
     */
    public function getMemberDAO() {
       return new MemberDaoImpl($this->conn);
    }

    /**
     * @return MemberPageDAO
     */
    public function getMemberPageDAO() {
       return new MemberPageDaoImpl($this->conn);
    }

    /**
     * @return SessionDAO
     */
    public function getSessionDAO() {
        return new SessionDAOImpl($this->conn);
    }

    /**
     * @return GroupDAO
     */
    public function getGroupDAO(){
        return new GroupDAOImpl($this->conn);
    }

    /**
     * @return IsGroupMemberDAO
     */
    public function getIsGroupMemberDAO(){
        return new IsGroupMemberDAOImpl($this->conn);
    }

    /**
     * @return PostDAO
     */
    public function getPostDAO() {
        return new PostDAOImpl($this->conn);
    }
}
