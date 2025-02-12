<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTermsAndPrivacyPages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Page::create([
            'slug' => 'terms',
            'name' => 'Terms & Conditions',
            'description' => 'Terms & Conditions page',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut feugiat dui non velit feugiat, nec fringilla ligula blandit. Sed in nisi et ex feugiat rutrum ac a orci. Sed vitae est ligula. Aliquam at libero ante. Nunc vehicula orci sapien, ut fringilla elit placerat sed. Donec tincidunt facilisis felis, vel fermentum dui tincidunt ut. Nullam sit amet porttitor ex. Ut scelerisque malesuada enim quis auctor. Vestibulum suscipit mi at elit tempus blandit. Nullam vel lectus vitae neque posuere mattis. In enim dolor, vulputate non diam nec, egestas fermentum felis. Sed sed mattis nisl. Mauris fermentum ex non ligula varius, pulvinar ultricies purus egestas. Proin interdum nibh felis, at luctus libero venenatis ac.',
            'is_active' => false,
            'order' => 0,
            'is_deletable' => false,
        ]);

        Page::create([
            'slug' => 'privacy',
            'name' => 'Privacy Policy',
            'description' => 'Privacy Policy page',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut feugiat dui non velit feugiat, nec fringilla ligula blandit. Sed in nisi et ex feugiat rutrum ac a orci. Sed vitae est ligula. Aliquam at libero ante. Nunc vehicula orci sapien, ut fringilla elit placerat sed. Donec tincidunt facilisis felis, vel fermentum dui tincidunt ut. Nullam sit amet porttitor ex. Ut scelerisque malesuada enim quis auctor. Vestibulum suscipit mi at elit tempus blandit. Nullam vel lectus vitae neque posuere mattis. In enim dolor, vulputate non diam nec, egestas fermentum felis. Sed sed mattis nisl. Mauris fermentum ex non ligula varius, pulvinar ultricies purus egestas. Proin interdum nibh felis, at luctus libero venenatis ac.',
            'is_active' => false,
            'order' => 0,
            'is_deletable' => false,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
