<?php


use Phinx\Seed\AbstractSeed;

class FillDatabase extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run(): void
    {
        $faker = Faker\Factory::create('fr_FR');
        $users = [];
        for ($i = 0; $i < 100; $i++) {
            $users[] = [
                'email' => $faker->email,
                'username' => $faker->userName,
                'password' => password_hash($faker->password, PASSWORD_BCRYPT),
                'first' => $faker->firstName,
                'last' => $faker->lastName,
                'picture' => $faker->boolean(90)? $faker->imageUrl(640, 480, 'USER', false) : null,
                'role' => 'user',
                'validated_email' => $faker->boolean(90),
                'created_at' => $faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d H:i:s'),
            ];
        }
        $this->table('users')->insert($users)->save();
        var_dump("100 users added");
        
        $categories = [];
        for ($i = 0; $i < 5; $i++) {
            $categories[] = [
                'name' => $faker->word,
                'created_at' => $faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d H:i:s'),
            ];
        }
        $this->table('categories')->insert($categories)->save();
        var_dump("5 categories added");

        $posts = [];
        for ($i = 0; $i < 100; $i++) {
            $posts[] = [
                'category' => $faker->numberBetween(1, 5),
                'title' => $faker->title,
                'chapo' => $faker->sentence,
                'content' => $faker->paragraph,
                'picture' => $faker->boolean(90)? $faker->imageUrl(640, 480, 'POST', false) : null,
                'url' => $faker->boolean(90)? $faker->url() : null,
                'created_at' => $faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d H:i:s'),
            ];
        }
        $this->table('posts')->insert($posts)->save();
        var_dump("100 posts added");

        $comments = [];
        for ($i = 0; $i < 5000; $i++) {
            $comments[] = [
                'post' => $faker->numberBetween(1, 100),
                'author' => $faker->numberBetween(1, 100),
                'comment' => $faker->paragraph,
                'created_at' => $faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d H:i:s'),
                'moderate' => $faker->numberBetween(0, 2),
                'deleted' => $faker->boolean(5)
            ];
        }
        $this->table('comments')->insert($comments)->save();
        var_dump("5000 comments added");
    }
}
