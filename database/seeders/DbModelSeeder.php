<?php

namespace Database\Seeders;

use App\Datas\PropertyOptions;
use App\Enums\CastType;
use App\Enums\DataObjectType;
use App\Enums\DtoPropertyType;
use App\Enums\PropertyType;
use App\Enums\RelationType;
use App\Models\BaseCast;
use App\Models\DataObjectProperty;
use App\Models\DbDataObject;
use App\Models\DbEnum;
use App\Models\DbModel;
use App\Models\DbModelPrimary;
use App\Models\DbPage;
use App\Models\DbProperty;
use App\Models\DbRelation;
use App\Models\EnumCase;
use Illuminate\Database\Seeder;

class DbModelSeeder extends Seeder
{
    public function run()
    {

        // truncate all tables
//        DbModel::truncate();
//        DbProperty::truncate();
//        DbModelPrimary::truncate();

        //dd('stop');
//        DbEnum::truncate();
//        EnumCase::truncate();
//        DataObjectProperty::truncate();
//        DbDataObject::truncate();
//        BaseCast::truncate();
//        DbRelation::truncate();




        [$post, $post_primary] = $this->create_model("Post");
        [$user, $user_primary] = $this->create_model("User");
        [$comment, $comment_primary] = $this->create_model("Comment");
        [$tag, $tag_primary] = $this->create_model("Tag");
        [$category, $category_primary] = $this->create_model("Category");
        [$post_tag, $post_tag_primary] = $this->create_model("PostTag");
        [$post_category, $post_category_primary] = $this->create_model("PostCategory");


        $user_name_property = $this->create_property($user, PropertyType::STRING, "name");
        $user_email_property = $this->create_property($user, PropertyType::STRING, "email");
        $user_type_property = $this->create_property($user, PropertyType::ENUM, "type");
        $user_options_property = $this->create_property($user, PropertyType::JSON, "options");



        $cast_user__type  = $user_type_property->cast()->create([
            "type" => CastType::ENUM,
        ]);
        $user_type = DbEnum::create([
            "name" => "UserType",
        ]);
        $cast_user__type->castable()->associate($user_type)->save();
        $user_type->cases()->createMany([
            ["name" => "ADMIN"],
            ["name" => "GUEST"],
            ["name" => "USER"],
        ]);

        $cast_user_options = $user_options_property->cast()->create([
            "type" => CastType::DATA_OBJECT,
        ]);
        $user_options = DbDataObject::create([
            "name" => "UserOptions",
        ]);
        $cast_user_options->castable()->associate($user_options)->save();
        $user_options->properties()->createMany([
            [
                "name" => "is_admin",
                "type" => DtoPropertyType::BOOLEAN,
                "is_nullable" => false,
                "default" => false,
            ],
            [
                "name" => "is_guest",
                "type" => DtoPropertyType::BOOLEAN,
                "is_nullable" => false,
                "default" => false,
            ],
            [
                "name" => "is_user",
                "type" => DtoPropertyType::BOOLEAN,
                "is_nullable" => false,
                "default" => false,
            ],
            [
                "name" => "test",
                "type" => DtoPropertyType::STRING,
                "is_nullable" => false,
                "default" => false,
            ],
        ]);



        $post_title_property = $this->create_property($post, PropertyType::STRING, "name");
        $post_body_property = $this->create_property($post, PropertyType::TEXT, "body");
        $post_user_property = $this->create_property($post, PropertyType::FOREIGN_FOR, "user_id", $post->id);

        $comment_message_property = $this->create_property($comment, PropertyType::TEXT, "message");
        $comment_user_property = $this->create_property($comment, PropertyType::FOREIGN_FOR, "user_id", $user->id);
        $comment_post_property = $this->create_property($comment, PropertyType::FOREIGN_FOR, "post_id", $post->id);

        $tag_name_property = $this->create_property($tag, PropertyType::STRING, "name");
        $category_name_property = $this->create_property($category, PropertyType::STRING, "name");

        $post_tag_post_property = $this->create_property($post_tag, PropertyType::FOREIGN_FOR, "post_id", $post->id);
        $post_tag_tag_property = $this->create_property($post_tag, PropertyType::FOREIGN_FOR, "tag_id", $tag->id);

        $post_category_post_property = $this->create_property($post_category, PropertyType::FOREIGN_FOR, "post_id", $post->id);
        $post_category_category_property = $this->create_property($post_category, PropertyType::FOREIGN_FOR, "category_id", $category->id);


        [$user_posts, $post_user] = $this->create_relation(
            "posts",
            "user",
            $user,
            $post,
            RelationType::ONE_TO_MANY,
            $user_primary->id,
            $post_user_property->id
        );

        [$post_comments, $comment_post] = $this->create_relation(
            "comments",
            "posts",
            $post,
            $comment,
            RelationType::ONE_TO_MANY,
            $post_primary->id,
            $comment_post_property->id
        );

        [$post_tags, $post_tag_post] = $this->create_relation(
            "tags",
            "posts",
            $post,
            $tag,
            RelationType::MANY_TO_MANY,
            $post_primary->id,
            $tag_primary->id,
            $post_tag,
            $post_tag_tag_property->id,
            $post_tag_post_property->id
        );

        [$post_categories, $post_category_post] = $this->create_relation(
            "categories",
            "posts",
            $post,
            $category,
            RelationType::MANY_TO_MANY,
            $post_primary->id,
            $category->id,
            $post_category,
            $post_category_category_property->id,
            $post_category_post_property->id
        );


        [$user_comments, $comment_user] = $this->create_relation(
            "comments",
            "user",
            $user,
            $comment,
            RelationType::ONE_TO_MANY,
            $user_primary->id,
            $comment_user_property->id
        );


        $page_posts = DbPage::create([
            "name" => "post",
            "slug" => "post",
            "model_id" => $post->id,
        ]);

        $page_comments = DbPage::create([
            "name" => "comment",
            "slug" => "comment",
            "model_id" => $comment->id,
        ]);



    }

    public function create_relation(
        string       $name,
        string       $other_name,
        DbModel      $model,
        DbModel      $other_model,
        RelationType $type,
        int          $property_id,
        int          $other_property_id,
        ?DbModel     $pivot_model = null,
        ?int         $pivot_property_id = null,
        ?int         $pivot_other_property_id = null,
    ): array
    {
        $relation = $model->relations()->create([
            "name" => $name,
            "model_id" => $model->id,
            "property_id" => $property_id,
            "type" => $type,
            "other_model_id" => $other_model->id,
            "other_property_id" => $other_property_id,
            'pivot_model_id' => $pivot_model?->id,
            'pivot_property_id' => $pivot_property_id,
            'pivot_other_property_id' => $pivot_other_property_id,

        ]);
        $reverse_relation = $other_model->relations()->create([
            "name" => $other_name,
            "model_id" => $other_model->id,
            "property_id" => $other_property_id,
            "type" => $type->reverse(),
            "other_model_id" => $model->id,
            "other_property_id" => $property_id,
            'pivot_model_id' => $pivot_model?->id,
            'pivot_property_id' => $pivot_other_property_id,
            'pivot_other_property_id' => $pivot_property_id,

        ]);
        $relation->reverse()->associate($reverse_relation);
        $reverse_relation->reverse()->associate($relation);
        $relation->save();
        $reverse_relation->save();
        return [$relation, $reverse_relation];

    }


    public function create_model($name): array
    {
        $model = DbModel::create([
            "name" => $name,
        ]);
        $primary = $model->properties()->create([
            "name" => "id",
            "options" => PropertyOptions::withoutMagicalCreationFrom(PropertyOptions::values_from_type("id")),
            "type" => PropertyType::ID_PRIMARY,
        ]);

        $model->properties_primary()->attach($primary);
        return [$model, $primary];

    }

    private function create_property(DbModel $model, PropertyType $type, string $name, $foreign_model_id = null): DbProperty
    {
        $data = [
            "name" => $name,
            "type" => $type,
            "options" => PropertyOptions::withoutMagicalCreationFrom(PropertyOptions::values_from_type($type->value)),
        ];
        if ($foreign_model_id) {
            $data["foreign_model_id"] = $foreign_model_id;
        }
        return $model->properties()->create($data);
    }
}
