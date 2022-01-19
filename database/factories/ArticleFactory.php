<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Article;
use App\Category;
use Faker\Generator as Faker;

$factory->define(Article::class, function (Faker $faker) {
    return [
        'title' => $this->faker->sentence(4),
        'slug'	=> $this->faker->slug,
        'content'	=> $this->faker->paragraphs(3, true),
        'category_id'	=> factory(Category::class),
        'user_id'	=> factory(User::class)
    ];
});
