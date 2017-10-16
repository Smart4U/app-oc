<?php

namespace MyApp\Models;

use App\Core\Database\Database;
use App\Core\Database\QueryBuilder;
use App\Core\Model\Model;
use App\Core\Validator\Validator;

/**
 * Class Post
 * @package MyApp\Models
 */
class Post extends Model
{

    /**
     * @var array
     */
    public $errors = [];

    /**
     * @var string
     */
    protected $table = 'posts';

    /**
     * @var QueryBuilder
     */
    protected $QB;

    /**
     * @var Database
     */
    protected $database;

    /**
     * @var array
     */
    protected $guarded = [
        'online',
        'author',
        'slug',
        'title',
        'subtitle',
        'content',
        'createdAt',
        'updatedAt'
    ];


    /**
     * @var array
     */
    protected $transactionData = [];

    /**
     * @var int
     */
    private $id;
    /**
     * @var int
     */
    private $online = 0;
    /**
     * @var string
     */
    private $author;
    /**
     * @var string
     */
    private $slug;
    /**
     * @var string
     */
    private $title;
    /**
     * @var string
     */
    private $subtitle;
    /**
     * @var string
     */
    private $content;
    /**
     * @var string
     */
    private $createdAt;
    /**
     * @var string
     */
    private $updatedAt;

    /**
     * Post constructor.
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->database = $database->getConnection();

        $this->QB = new QueryBuilder($this->database);
    }

    /**
     * @param Validator $validator
     * @param array $params
     * @return array|null
     */
    public function create(Validator $validator, array $params = []) {

        $errors = $validator->notEmpty('author', 'slug', 'title', 'subtitle', 'content')
            ->length('author',5, 50)
            ->length('title',5, 100)
            ->length('slug', 5, 100)
            ->slug('slug')
            ->getErrors();

        if($errors){
            return $errors;
        }

        $online = $params['online'] ?? 0;
        $this->setCreatedAt();
        $this->setUpdatedAt();

        $this->hydrate($params);

        $this->QB->insertInto(
            $this->table,
            $this->getTransactionData(
                array_merge($params, ['online' => $online, 'created_at' => $this->getCreatedAt(), 'updated_at' => $this->getUpdatedAt()])
            )
        )->execute();
        return null;

    }

    /**
     * @param Validator $validator
     * @param array $params
     * @return array|null
     */
    public function update(Validator $validator, array $params = []) {

        $errors = $validator->notEmpty('author', 'slug', 'title', 'subtitle', 'content')
            ->length('author',5, 50)
            ->length('title',5, 100)
            ->length('slug', 5, 100)
            ->slug('slug')
            ->getErrors();

        if($errors){
            return $errors;
        }

        $online = $params['online'] ?? 0;
        $this->setUpdatedAt();

        $this->hydrate($params);

        $this->QB->update($this->table)->where('id', $params['id'])->set($this->getTransactionData(
            array_merge($params, ['online' => $online, 'updated_at' => $this->getUpdatedAt()])
        ))->execute();
        return null;
    }



    /**
     * @return mixed
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }


    /**
     * @return mixed
     */
    public function getOnline(): int
    {
        return ($this->online === 1) ? 1 : 0;
    }


    /**
     * @param int $online
     */
    public function setOnline(int $online = 0): void
    {
        $this->online = (int)$online;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }


    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * @param string $subtitle
     */
    public function setSubtitle(string $subtitle): void
    {
        $this->subtitle = $subtitle;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }


    public function setCreatedAt(): void
    {
        $this->createdAt = $this->getCurrentDateTime();
    }


    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(): void
    {
        $this->updatedAt = $this->getCurrentDateTime();
    }

    /**
     * @param int $maxPerPage
     * @param int $currentPage
     * @return \Pagerfanta\Pagerfanta
     */
    public function pagination(int $maxPerPage, int $currentPage, string $order = 'ORDER BY id DESC')
    {
        return $this->QB->paginate($this->table, $maxPerPage, $currentPage, $order);
    }

    /**
     * @param $limit
     * @return array
     */
    public function getLast($limit) {
        return $this->QB->from($this->table)->limit($limit)->orderBy('updated_at')->fetchAll();
    }


}