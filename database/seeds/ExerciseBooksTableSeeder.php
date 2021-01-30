<?php

use App\Models\ExerciseBook;
use Illuminate\Database\Seeder;

class ExerciseBooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(ExerciseBook::class, 150)->create();
    }
}
