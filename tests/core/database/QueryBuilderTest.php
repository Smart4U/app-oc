<?php

namespace Tests\Core\Database;


use App\Core\Database\QueryBuilder;

use Tests\DatabaseTestCase;

class QueryBuilderTest extends DatabaseTestCase
{

    public function testQuerySelect() {
        $query = (string)(new QueryBuilder($this->getPDO()))->select()->from('test');

        $this->assertEquals('SELECT * FROM test', $query);
    }


    public function testQuerySelectWithAttributes() {
        $query = (string)(new QueryBuilder($this->getPDO()))->select('id', 'slug')->from('test');

        $this->assertEquals('SELECT id, slug FROM test', $query);
    }

    public function testQuerySelectWithAttributesShuffle() {
        $query = (string)(new QueryBuilder($this->getPDO()))->from('test')->select('slug', 'id');

        $this->assertEquals('SELECT slug, id FROM test', $query);
    }

    public function testQuerySelectWithAlias() {
        $query = (string)(new QueryBuilder($this->getPDO()))->select()->from('test', 't');

        $this->assertEquals('SELECT * FROM test AS t', $query);
    }

    public function testQuerySelectWithAliasAndTermsShuffle() {
        $query = (string)(new QueryBuilder($this->getPDO()))->where('online > 0')->select('title')->from('test', 't');

        $this->assertEquals('SELECT title FROM test AS t WHERE (online > 0)', $query);
    }

    public function testQuerySelectWithAliasAndTerms() {
        $query = (string)(new QueryBuilder($this->getPDO()))->select()->from('test')->where('online > 0', 'id = :id', 'slug = :slug');

        $this->assertEquals('SELECT * FROM test WHERE (online > 0) AND (id = :id) AND (slug = :slug)', $query);
    }

    public function testQuerySelectWithAliasAndAnotherTerms() {
        $query = (string)(new QueryBuilder($this->getPDO()))->select()->from('test')->where('online > 0 OR created_at > now()');

        $this->assertEquals('SELECT * FROM test WHERE (online > 0 OR created_at > now())', $query);
    }

    public function testCount() {
        $pdo = $this->getPDO();
        $pdo->exec('CREATE TABLE test ( id INTEGER PRIMARY KEY AUTOINCREMENT, online SMALLINT, slug VARCHAR(255), title VARCHAR (255) )');
        $pdo->exec("INSERT INTO test (slug, title) VALUES ('slug-1', 'title 1')");
        $pdo->exec("INSERT INTO test (slug, title) VALUES ('slug-2', 'title 2')");
        $pdo->exec("INSERT INTO test (slug, title) VALUES ('slug-3', 'title 3')");

        $item = (new QueryBuilder($pdo))->from('test')->count();
        $this->assertEquals(3, $item);

        $item = (new QueryBuilder($pdo))->from('test', 't')->where('t.id > 2')->count();
        $this->assertEquals(1, $item);
    }

    // SELECT
    public function testBasicSelectQuery() {

        $pdo = $this->getPDO();
        $queryBuilder = new QueryBuilder($pdo);

        $pdo->exec('CREATE TABLE test ( id INTEGER PRIMARY KEY AUTOINCREMENT, online SMALLINT, slug VARCHAR(255), title VARCHAR (255) )');
        $pdo->exec("INSERT INTO test (online, slug, title) VALUES (1, 'slug-1', 'title 1')");
        $pdo->exec("INSERT INTO test (online, slug, title) VALUES (2, 'slug-1', 'title 1')");

        $post = $queryBuilder->select()->from('test')->execute()->fetchAll();
        $this->assertCount(2, $post);
    }

    public function testAllSelectQuery() {
        $query = (new QueryBuilder())
            ->select()
            ->from('posts');

        $this->assertEquals('SELECT * FROM posts', (string)$query);
    }



    public function testAllSelectQueryShuffle() {
        $query = (new QueryBuilder())
            ->from('posts')
            ->select();

        $this->assertEquals('SELECT * FROM posts', (string)$query);
    }


    public function testSelectQueryWithSelectAttrAndCondition() {
        $query = (new QueryBuilder())
            ->select('id', 'name')
            ->from('posts')
            ->where('category_id=5');

        $this->assertEquals('SELECT id, name FROM posts WHERE (category_id=5)', (string)$query);
    }

    public function testSelectQueryWithSelectAttrAndConditionShuffle() {
        $query = (new QueryBuilder())
            ->from('posts')
            ->where('category_id=5 AND online=1')
            ->select('id', 'name');

        $this->assertEquals('SELECT id, name FROM posts WHERE (category_id=5 AND online=1)', (string)$query);
    }

    public function testSelectQueryWithSelectAttrAndConditionOrdered() {
        $query = (new QueryBuilder())
            ->select('id', 'name')
            ->from('posts')
            ->where('category_id=5')
            ->order('ASC', 'published_at', 'online');

        $this->assertEquals('SELECT id, name FROM posts WHERE (category_id=5) ORDER BY published_at, online ASC', (string)$query);
    }

    public function testSelectQueryWithSelectAttrAndConditionOrderedShuffle() {
        $query = (new QueryBuilder())
            ->order('DESC', 'published_at')
            ->from('posts')
            ->where('category_id=5', 'online=1')
            ->select('id', 'name');

        $this->assertEquals('SELECT id, name FROM posts WHERE (category_id=5) AND (online=1) ORDER BY published_at DESC', (string)$query);
    }

    public function testSelectQueryWithSelectAttrAndConditionOrderedAndLimited() {
        $query = (new QueryBuilder())
            ->select('id', 'name')
            ->from('posts')
            ->where('category_id=5')
            ->order('ASC', 'published_at' )
            ->limit(5);

        $this->assertEquals('SELECT id, name FROM posts WHERE (category_id=5) ORDER BY published_at ASC LIMIT 5', (string)$query);
    }

     public function testSelectQueryWithSelectAttrAndConditionOrderedAndLimitedShuffle() {
         $query = (new QueryBuilder())
             ->limit(5)
             ->order('DESC', 'published_at')
             ->from('posts')
             ->where('category_id=5', 'online=1')
             ->select('id', 'name');

         $this->assertEquals('SELECT id, name FROM posts WHERE (category_id=5) AND (online=1) ORDER BY published_at DESC LIMIT 5', (string)$query);
     }

     public function testSelectQueryWithSelectAttrAndConditionOrderedAndLimitedWithOffset() {
         $query = (new QueryBuilder())
             ->select('id', 'name')
             ->from('posts')
             ->where('category_id=5')
             ->order('ASC', 'published_at')
             ->limit(5, 10);

         $this->assertEquals('SELECT id, name FROM posts WHERE (category_id=5) ORDER BY published_at ASC LIMIT 5, 10', (string)$query);
     }

     public function testSelectQueryWithSelectAttrAndConditionOrderedAndLimitedWithOffsetShuffle() {
         $query = (new QueryBuilder())
             ->limit(5, 10)
             ->order('DESC', 'published_at')
             ->from('posts')
             ->where('category_id=5 OR online=1')
             ->select('id', 'name');

         $this->assertEquals('SELECT id, name FROM posts WHERE (category_id=5 OR online=1) ORDER BY published_at DESC LIMIT 5, 10', (string)$query);
     }

    // INSERT
    public function testInsert() {
        $pdo = $this->getPDO();
        $pdo->exec('CREATE TABLE test ( id INTEGER PRIMARY KEY AUTOINCREMENT, online SMALLINT, slug VARCHAR(255), title VARCHAR (255) )');

        $QB = new QueryBuilder($pdo);
        $QB->insert('test', ['online' => 1, 'slug' => 'my-slug', 'title' => 'title']);

        $item = $QB->select()->from('test');
        $this->assertEquals(1, count($item));
    }

}