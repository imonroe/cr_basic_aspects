<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBasicAspects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!AspectType::where('aspect_name', '=', 'File Upload')->exists()){
            $aspect_type = new AspectType;
            $aspect_type->aspect_name = 'File Upload';
            $aspect_type->aspect_description = 'An uploaded file';
            $aspect_type->is_viewable = 1;
            $aspect_type->save();
        }

        if (!AspectType::where('aspect_name', '=', 'Formatted Text')->exists()){
            $aspect_type = new AspectType;
            $aspect_type->aspect_name = 'Formatted Text';
            $aspect_type->aspect_description = 'HTML-formatted text';
            $aspect_type->is_viewable = 1;
            $aspect_type->save();
        }

        if (!AspectType::where('aspect_name', '=', 'Image')->exists()){
            $aspect_type = new AspectType;
            $aspect_type->aspect_name = 'Image';
            $aspect_type->aspect_description = 'An image file';
            $aspect_type->is_viewable = 1;
            $aspect_type->save();
        }

        if (!AspectType::where('aspect_name', '=', 'Lambda Function')->exists()){
            $aspect_type = new AspectType;
            $aspect_type->aspect_name = 'Lambda Function';
            $aspect_type->aspect_description = 'An aspect that performs some sort of calculation or function, rather than storing data';
            $aspect_type->is_viewable = 0;
            $aspect_type->save();
        }

        if (!AspectType::where('aspect_name', '=', 'Markdown Text')->exists()){
            $aspect_type = new AspectType;
            $aspect_type->aspect_name = 'Markdown Text';
            $aspect_type->aspect_description = 'Markdown-formatted text';
            $aspect_type->is_viewable = 1;
            $aspect_type->save();
        }

        if (!AspectType::where('aspect_name', '=', 'Relationship')->exists()){
            $aspect_type = new AspectType;
            $aspect_type->aspect_name = 'Relationship';
            $aspect_type->aspect_description = 'Describes a relationship between two Subjects';
            $aspect_type->is_viewable = 1;
            $aspect_type->save();
        }

        if (!AspectType::where('aspect_name', '=', 'Unformatted Text')->exists()){
            $aspect_type = new AspectType;
            $aspect_type->aspect_name = 'Unformatted Text';
            $aspect_type->aspect_description = 'An unformatted blob of text. Good for embedded content, iframes, etc.';
            $aspect_type->is_viewable = 1;
            $aspect_type->save();
        }
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