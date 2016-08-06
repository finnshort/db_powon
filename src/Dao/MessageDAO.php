<?php

namespace Powon\Dao;

use Powon\Entity\Member;
use Powon\Entity\Message;

interface MessageDAO {

    /**
    * @param member Member
    * @return array of messages
    */
    public function getMessagesForMember(Member $member);

    /**
    * @param member Member
    * @return array of messages
    */
    public function getMessagesSentByMember(Member $member);

    /**
    * @param msg: a Message object
    */
    public function sendMessage(Message $msg);

    /**
    * Mark message as read
    * @param to Member
    * @param msg Message
    */
    public function readMessage(Member $to, Message $msg);

    /**
    * Mark message as deleted in member's inbox
    * @param to Member
    * @param msg Message
    */
    public function deleteMessage(Member $to, Message $msg);

}
