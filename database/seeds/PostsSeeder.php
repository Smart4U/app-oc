<?php

use Phinx\Seed\AbstractSeed;

class PostsSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {

        $posts = [];
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 100; $i++) {
            $TheDateTime = $faker->unixTime('now');

            $posts[] = [
                'online' => rand(-1, 1),
                'author' => $faker->name(),
                'slug' => $faker->slug(5),
                'title' => $faker->sentence(5),
                'subtitle' => $faker->sentence(7),
                'content' => $faker->text(1000),
                'created_at' => date('Y-m-d H:i:s', $TheDateTime),
                'updated_at' => date('Y-m-d H:i:s', $TheDateTime)
            ];
        }
        $this->table('posts')
            ->insert($posts)
            ->save();
    }
}
