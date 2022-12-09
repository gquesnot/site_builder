### has One
User:id  -> Phone:id,user_id
- User::phone => $user->hasOne(Phone::class, 'user_id', 'id');
- Phone::user => $phone->belongsTo(User::class, 'user_id', 'id');

### One to Many
Post:id  -> Comment:id,post_id
- Post::comments => $post->hasMany(Comment::class, 'post_id', 'id');
- Comment::post => $comment->belongsTo(Post::class, 'post_id', 'id');


### Has One Through
Mechanic:id -> Car:id,mechanic_id -> Owner:id,car_id
- Mechanic::owner => $mechanic->hasOneThrough(Owner::class, Car::class, 'mechanic_id', 'car_id', 'id', 'id');
- Owner::mechanic => $owner->hasOneThrough(Mechanic::class, Car::class, 'car_id', 'mechanic_id', 'id', 'id');

### Has Many Through
Project:id -> Environment:id,project_id -> Deployment:id,environment_id
-Project::deployments => $project->hasManyThrough(Deployment::class, Environment::class, 'project_id', 'environment_id', 'id', 'id');
-Deployment::project => $deployment->hasManyThrough(Project::class, Environment::class, 'environment_id', 'project_id', 'id', 'id');

### Belongs To Many
User:id ->  RoleUser:user_id,role_id -> Role:id
- User::roles => $user->belongsToMany(Role::class)->using(RoleUser::class)->withPivot('role_id', 'user_id');
- Role::users => $role->belongsToMany(User::class)->using(RoleUser::class)->withPivot('role_id', 'user_id');
