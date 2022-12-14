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
        echo "\nDatabase filling :".PHP_EOL;
        $faker = Faker\Factory::create('fr_FR');
        $users = [];
        $users[] = [
            'email'  => 'user@yopmail.fr',
            'username' => 'user',
            'password' => "2cccdc2687174d99b72adf3e151cdc42d77fd16d4de993972261d0cadf002406efeca3991a8bcb2d265d44c4e460a60c9eb357008733d9ba10ba52bce99e45ef",
            'first' => "User_first",
            'last' => "User_last",
            'role' => "user",
            'validated_email' => 1,
        ];
        for ($i = 0; $i < 98; $i++) {
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
        echo "100 users added".PHP_EOL;

        $categories = [];
        for ($i = 0; $i < 5; $i++) {
            $categories[] = [
                'name' => $faker->word,
                'created_at' => $faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d H:i:s'),
            ];
        }
        $this->table('categories')->insert($categories)->save();
        echo "5 categories added".PHP_EOL;

        $posts = [];
        for ($i = 0; $i < 100; $i++) {
            $posts[] = [
                'category' => $faker->numberBetween(1, 5),
                'title' => $faker->words(3, true),
                'chapo' => $faker->sentence,
                'content' => $faker->paragraphs(3, true),
                'picture' => $faker->boolean(90)? $faker->imageUrl(640, 480, 'POST', false) : null,
                'url' => $faker->boolean(90)? $faker->url() : null,
                'created_at' => $faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d H:i:s'),
            ];
        }
        $this->table('posts')->insert($posts)->save();
        echo "100 posts added".PHP_EOL;

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
        echo "5000 comments added".PHP_EOL;
        echo "Filled database".PHP_EOL;
    }
}
