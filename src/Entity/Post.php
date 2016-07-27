<?php

namespace Powon\Entity;

class Post
{
    const PERMISSION_VIEW_ONLY = 'V';
    const PERMISSION_COMMENT = 'C';
    const PERMISSION_ADD_CONTENT = 'A';
    const PERMISSION_TAILORED = 'T';
    const PERMISSION_DENIED = 'D';

    private $post_id;
    private $post_date_created;
    private $post_type;
    private $path_to_resource;
    private $post_body;
    private $comment_permission;
    private $page_id;
    private $author_id;

    /**
     * @var Member full entity is useful for a post.
     */
    private $author;

    // not needed at the application level for now.
    //private $parent_post;


    /**
     * Accept an array of data matching properties of this class
     * and create the class
     *
     * @param array $data The data to use to create
     */
    public function __construct(array $data) {
        // no id if we're creating
        if(isset($data['post_id'])) {
            $this->post_id = (int)$data['post_id'];
        }
        $this->post_date_created = $data['post_date_created'];
        $this->post_type = $data['post_type'];
        $this->path_to_resource = $data['path_to_resource'];
        $this->post_body = $data['post_body'];
        $this->comment_permission = $data['comment_permission'];
        $this->page_id = $data['page_id'];
        $this->author_id = $data['author_id'];
        $this->author = null;
    }

    /**
     * @return int
     */
    public function getPostId() {
        return $this->post_id;
    }

    /**
     * @return string (?) post_date_created is datetime in sql
     */
    public function getPostDateCreated() {
        return $this->post_date_created;
    }

    /**
     * @return string
     */
    public function getPostType() {
        return $this->post_type;
    }

    /**
     * @return string
     */
    public function getPathToResource() {
        return $this->path_to_resource;
    }

    /**
     * @return string
     */
    public function getPostBody() {
        return $this->post_body;
    }

    /**
     * @return string (no char in php)
     */
    public function getCommentPermission()
    {
        return $this->comment_permission;
    }

    /**
     * @return int (could be null)

    public function getParentPost() {
        return $this->parent_post;
    }
    */
    /**
     * @return int
     */
    public function getPageId() {
        return $this->page_id;
    }

    /**
     * @return int
     */
    public function getAuthorId() {
        return $this->author_id;
    }

    /**
     * @param Member $member the author of this post.
     */
    public function setAuthor(Member $member) {
        $this->author = $member;
    }

    /**
     * @return Member|null
     */
    public function getAuthor() {
        return $this->author;
    }

    ///

    /**
     * @return array post entity in php array format
     */
    public function toObject() {
        $obj = array();
        if (isset($this->post_id)) {
            $obj['post_id'] = $this->post_id;
        }

      $obj['post_date_created'] = $this->post_date_created;
      $obj['path_to_resource'] = $this->path_to_resource;
      $obj['post_body'] = $this->post_body;
      $obj['comment_permission'] = $this->comment_permission;
      $obj['page_id'] = $this->page_id;
      $obj['author_id'] = $this->author_id;
        if ($this->author) {
            $obj['author'] = $this->author->toObject();
        }

        return $obj;
    }

    /**
     * @return string the post entity in json format
     */
    public function toJson() {
       return json_encode($this->toObject());
    }

}
