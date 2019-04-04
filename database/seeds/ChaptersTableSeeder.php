<?php

use App\Modules\Chapter\Chapter;
use App\Modules\Lock\Lock;
use App\Modules\Revision\Revision;
use App\User;
use Illuminate\Database\Seeder;

class ChaptersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $text = '<p>This will initialize the editor with a piece of code already in it, and explicitly tell it to use the JavaScript mode (which is useful when multiple modes are loaded). See below for a full discussion of the configura<del class="ice-cts ice-del" data-changedata="" data-cid="2" data-last-change-time="1544681123825" data-time="1544681123825" data-userid="" data-username="">tion options that CodeMirror accepts. In cases where you don&#39;t want to append the editor to an element, and need more control over the way it is </del>inserted, the first argument to the CodeMirror function can also be a function that, when given a DOM element, inserts it into the document somewhere. This could be used to, for example, replace a textarea with a real editor: This will initialize the editor with a piece of code already in it, and explicitly tell it to use the JavaScript mode (which is useful when multiple modes are loaded). See b<del class="ice-cts ice-del" data-changedata="" data-cid="8" data-last-change-time="1544681127811" data-time="1544681127811" data-userid="" data-username="">elow for a full disc</del>ussi<del class="ice-cts ice-del" data-changedata="" data-cid="7" data-last-change-time="1544681126935" data-time="1544681126935" data-userid="" data-username="">on of the configu</del>ra<del class="ice-cts ice-del" data-changedata="" data-cid="6" data-last-change-time="1544681126235" data-time="1544681126235" data-userid="" data-username="">tion o</del>ptions that CodeMirror a<del class="ice-cts ice-del" data-changedata="" data-cid="3" data-last-change-time="1544681124896" data-time="1544681124896" data-userid="" data-username="">cce</del>pts. In cases where you don&#39;t want to append the editor to an element, and need more control over the way it is inserted, the first argument to the CodeMirror function can also be a function that, when given a DOM element, inserts it into the document somewhere. This could be used to, for example, replace a textarea with a real editor:This will initialize the editor with a piece of code already in it, and explicitly tell it to use the JavaScript mode (which is useful when multiple modes are loaded). See below for a full discussion of the configuration options that CodeMirror accepts.</p>

<p>In cases where you don&#39;t want to append the editor to an element, and need more control over the way it is inserted, the first argument to the CodeMirror function can also be a function that, when given a DOM element, inserts it into the document somewhere. This could be used to, for example, replace a textarea with a real editor:</p>';

        $chapter3text = '<p>In cases where you don&#39;t want to append the editor to an element, and need more control over the way it is inserted, the first argument to the CodeMirror function can also be a function that, when given a DOM element, inserts it into the document somewhere. This could be used to, for example, replace a textarea with a real editor:</p>';

        $user1 = User::create([
            'name' => 'Contributor 1',
            'email' => 'test1@gmail.com',
            'password' => bcrypt('password'),
        ])->assignRole('contributor');

        $user2 = User::create([
            'name' => 'Contributor 2',
            'email' => 'test2@gmail.com',
            'password' => bcrypt('password'),
        ])->assignRole('contributor');

        $cid1 = Chapter::create([
            'name' => 'ORMs vs. Query Builders: Database portability',
            'text' => $text,
        ]);

        $cid2 = Chapter::create([
            'name' => 'Laravel Eloquent: Ordering results',
            'text' => $text,
        ]);

        $cid3 = Chapter::create([
            'name' => 'Drupal 8 migrations',
            'text' => $chapter3text,
        ]);

        Lock::create([
            'cid' => $cid1->id,
            'uid' => $user1->id,
            'expires_on' => 1544869315,
        ]);

        Lock::create([
            'cid' => $cid2->id,
            'uid' => $user2->id,
            'expires_on' => 1544523715,
        ]);

        Revision::create([
            'cid' => $cid1->id,
            'uid' => $user1->id,
            'text' => $text,            
            'status' => 'Edited',
        ]);

        Revision::create([
            'cid' => $cid2->id,
            'uid' => $user2->id,
            'text' => $text,
            'status' => 'Edited',
        ]);

        Revision::create([
            'cid' => $cid3->id,
            'uid' => $user2->id,
            'text' => $chapter3text,
            'status' => 'Published',
        ]);

    }
}
