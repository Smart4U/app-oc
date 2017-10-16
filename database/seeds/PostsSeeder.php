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
            $content = "<p class='mb-3'>{$faker->text(600)}</p><p>{$faker->text(3000)}</p><p class='mt-5'>{$faker->text(1200)}</p>";
            $posts[] = [
                'online' => rand(-1, 1),
                'author' => $faker->name(),
                'slug' => $faker->slug(4),
                'title' => $faker->sentence(4),
                'subtitle' => $faker->sentence(3),
                'content' => $content,
                'thumbnail' => $faker->imageUrl(604, 403, null,true, null),
                'created_at' => date('Y-m-d H:i:s', $TheDateTime),
                'updated_at' => date('Y-m-d H:i:s', $TheDateTime)
            ];
        }
        $this->table('posts')
            ->insert($posts)
            ->save();
    }
}
