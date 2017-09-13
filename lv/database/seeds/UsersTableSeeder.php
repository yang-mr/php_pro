<?php

use Illuminate\Database\Seeder;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $request = new Request;
         User::create([
                'name' => 'admin',
                'password' => Hash::make('111111'),
                'is_admin' => '1',
                'email' => '88888@qq.com',
            ]);
         User::create([
    	        'name' => '杨小',
    	        'email' => '88888@qq.com',
    	        'password' => Hash::make('111111'),
                'phone' => '1520225454',
                'description' => '描述，汉语词语，出自闻一多《兽·人·鬼》：“刽子手们这次杰作，我们不忍再描述了。”形象地叙述；描写叙述；运用各种修辞手法对事物进行形象化的阐述。[describe] 描写叙述，运用各种修辞手法对事物进行形象化的阐述。他生动地~了那件事的经过l 作品朴实地~了农民的生活。郭小川 《钢铁是怎样炼成的》诗：“有个美妙的故事，必须向你们简略地描述。艺术的真正生命正在对于个别特殊事物的掌握和描述。希腊债务状况远不像惯常描述的那样令人担忧,希腊需要的就是回归增长。',
                'requist' => '描述，汉语词语，出自闻一多《兽·人·鬼》：“刽子手们这次杰作，我们不忍再描述了。”形象地叙述；描写叙述；运用各种修辞手法对事物进行形象化的阐述。[describe] 描写叙述，运用各种修辞手法对事物进行形象化的阐述。他生动地~了那件事的经过l 作品朴实地~了农民的生活。郭小川 《钢铁是怎样炼成的》诗：“有个美妙的故事，必须向你们简略地描述。艺术。',
                'work' => '1',
                'education' => '3',
                'birthday' => Carbon::now(),
                'hourse_car' => '3',
                'minzu' => '3',
                'weight' => 50,
                'visitor_ip' => $request->ip(),
                'height' => 170,
                'nation' => '中国',
                'province' => '上海',
                'city' => '上海',
                'area' => '长宁',
                'will_childs' => 2,
                'now_status' => 2,
	        ]);

           User::create([
                'name' => '杨小',
                'email' => '88888@qq.com',
                'password' => Hash::make('111111'),
                'phone' => '1520225454',
                'description' => '描述，汉语词语，出自闻一多《兽·人·鬼》：“刽子手们这次杰作，我们不忍再描述了。”形象地叙述；描写叙述；运用各种修辞手法对事物进行形象化的阐述。[describe] 描写叙述，运用各种修辞手法对事物进行形象化的阐述。他生动地~了那件事的经过l 作品朴实地~了农民的生活。郭小川 《钢铁是怎样炼成的》诗：“有个美妙的故事，必须向你们简略地描述。艺术的真正生命正在对于个别特殊事物的掌握和描述。希腊债务状况远不像惯常描述的那样令人担忧,希腊需要的就是回归增长。',
                'requist' => '描述，汉语词语，出自闻一多《兽·人·鬼》：“刽子手们这次杰作，我们不忍再描述了。”形象地叙述；描写叙述；运用各种修辞手法对事物进行形象化的阐述。[describe] 描写叙述，运用各种修辞手法对事物进行形象化的阐述。他生动地~了那件事的经过l 作品朴实地~了农民的生活。郭小川 《钢铁是怎样炼成的》诗：“有个美妙的故事，必须向你们简略地描述。艺术。',
                'work' => '1',
                'education' => '3',
                'birthday' => Carbon::now(),
                'hourse_car' => '3',
                'minzu' => '3',
                'weight' => 50,
                'visitor_ip' => $request->ip(),
                'height' => 170,
                'nation' => '中国',
                'province' => '上海',
                'city' => '上海',
                'area' => '长宁',
                'will_childs' => 2,
                'now_status' => 2,
            ]);

             User::create([
                'name' => '杨小',
                'email' => '88888@qq.com',
                'password' => Hash::make('111111'),
                'phone' => '1520225454',
                'description' => '描述，汉语词语，出自闻一多《兽·人·鬼》：“刽子手们这次杰作，我们不忍再描述了。”形象地叙述；描写叙述；运用各种修辞手法对事物进行形象化的阐述。[describe] 描写叙述，运用各种修辞手法对事物进行形象化的阐述。他生动地~了那件事的经过l 作品朴实地~了农民的生活。郭小川 《钢铁是怎样炼成的》诗：“有个美妙的故事，必须向你们简略地描述。艺术的真正生命正在对于个别特殊事物的掌握和描述。希腊债务状况远不像惯常描述的那样令人担忧,希腊需要的就是回归增长。',
                'requist' => '描述，汉语词语，出自闻一多《兽·人·鬼》：“刽子手们这次杰作，我们不忍再描述了。”形象地叙述；描写叙述；运用各种修辞手法对事物进行形象化的阐述。[describe] 描写叙述，运用各种修辞手法对事物进行形象化的阐述。他生动地~了那件事的经过l 作品朴实地~了农民的生活。郭小川 《钢铁是怎样炼成的》诗：“有个美妙的故事，必须向你们简略地描述。艺术。',
                'work' => '1',
                'education' => '3',
                'birthday' => Carbon::now(),
                'hourse_car' => '3',
                'minzu' => '3',
                'weight' => 50,
                'visitor_ip' => $request->ip(),
                'height' => 170,
                'nation' => '中国',
                'province' => '上海',
                'city' => '上海',
                'area' => '长宁',
                'will_childs' => 2,
                'now_status' => 2,
            ]);

               User::create([
                'name' => '杨小',
                'email' => '88888@qq.com',
                'password' => Hash::make('111111'),
                'phone' => '1520225454',
                'description' => '描述，汉语词语，出自闻一多《兽·人·鬼》：“刽子手们这次杰作，我们不忍再描述了。”形象地叙述；描写叙述；运用各种修辞手法对事物进行形象化的阐述。[describe] 描写叙述，运用各种修辞手法对事物进行形象化的阐述。他生动地~了那件事的经过l 作品朴实地~了农民的生活。郭小川 《钢铁是怎样炼成的》诗：“有个美妙的故事，必须向你们简略地描述。艺术的真正生命正在对于个别特殊事物的掌握和描述。希腊债务状况远不像惯常描述的那样令人担忧,希腊需要的就是回归增长。',
                'requist' => '描述，汉语词语，出自闻一多《兽·人·鬼》：“刽子手们这次杰作，我们不忍再描述了。”形象地叙述；描写叙述；运用各种修辞手法对事物进行形象化的阐述。[describe] 描写叙述，运用各种修辞手法对事物进行形象化的阐述。他生动地~了那件事的经过l 作品朴实地~了农民的生活。郭小川 《钢铁是怎样炼成的》诗：“有个美妙的故事，必须向你们简略地描述。艺术。',
                'work' => '1',
                'education' => '3',
                'birthday' => Carbon::now(),
                'hourse_car' => '3',
                'minzu' => '3',
                'weight' => 50,
                'visitor_ip' => $request->ip(),
                'height' => 170,
                'nation' => '中国',
                'province' => '上海',
                'city' => '上海',
                'area' => '长宁',
                'will_childs' => 2,
                'now_status' => 2,
            ]);

                 User::create([
                'name' => '杨小',
                'email' => '88888@qq.com',
                'password' => Hash::make('111111'),
                'phone' => '1520225454',
                'description' => '描述，汉语词语，出自闻一多《兽·人·鬼》：“刽子手们这次杰作，我们不忍再描述了。”形象地叙述；描写叙述；运用各种修辞手法对事物进行形象化的阐述。[describe] 描写叙述，运用各种修辞手法对事物进行形象化的阐述。他生动地~了那件事的经过l 作品朴实地~了农民的生活。郭小川 《钢铁是怎样炼成的》诗：“有个美妙的故事，必须向你们简略地描述。艺术的真正生命正在对于个别特殊事物的掌握和描述。希腊债务状况远不像惯常描述的那样令人担忧,希腊需要的就是回归增长。',
                'requist' => '描述，汉语词语，出自闻一多《兽·人·鬼》：“刽子手们这次杰作，我们不忍再描述了。”形象地叙述；描写叙述；运用各种修辞手法对事物进行形象化的阐述。[describe] 描写叙述，运用各种修辞手法对事物进行形象化的阐述。他生动地~了那件事的经过l 作品朴实地~了农民的生活。郭小川 《钢铁是怎样炼成的》诗：“有个美妙的故事，必须向你们简略地描述。艺术。',
                'work' => '1',
                'education' => '3',
                'birthday' => Carbon::now(),
                'hourse_car' => '3',
                'minzu' => '3',
                'weight' => 50,
                'visitor_ip' => $request->ip(),
                'height' => 170,
                'nation' => '中国',
                'province' => '上海',
                'city' => '上海',
                'area' => '长宁',
                'will_childs' => 2,
                'now_status' => 2,
            ]);

                   User::create([
                'name' => '杨小',
                'email' => '88888@qq.com',
                'password' => Hash::make('111111'),
                'phone' => '1520225454',
                'description' => '描述，汉语词语，出自闻一多《兽·人·鬼》：“刽子手们这次杰作，我们不忍再描述了。”形象地叙述；描写叙述；运用各种修辞手法对事物进行形象化的阐述。[describe] 描写叙述，运用各种修辞手法对事物进行形象化的阐述。他生动地~了那件事的经过l 作品朴实地~了农民的生活。郭小川 《钢铁是怎样炼成的》诗：“有个美妙的故事，必须向你们简略地描述。艺术的真正生命正在对于个别特殊事物的掌握和描述。希腊债务状况远不像惯常描述的那样令人担忧,希腊需要的就是回归增长。',
                'requist' => '描述，汉语词语，出自闻一多《兽·人·鬼》：“刽子手们这次杰作，我们不忍再描述了。”形象地叙述；描写叙述；运用各种修辞手法对事物进行形象化的阐述。[describe] 描写叙述，运用各种修辞手法对事物进行形象化的阐述。他生动地~了那件事的经过l 作品朴实地~了农民的生活。郭小川 《钢铁是怎样炼成的》诗：“有个美妙的故事，必须向你们简略地描述。艺术。',
                'work' => '1',
                'education' => '3',
                'birthday' => Carbon::now(),
                'hourse_car' => '3',
                'minzu' => '3',
                'weight' => 50,
                'visitor_ip' => $request->ip(),
                'height' => 170,
                'nation' => '中国',
                'province' => '上海',
                'city' => '上海',
                'area' => '长宁',
                'will_childs' => 2,
                'now_status' => 2,
            ]);

                     User::create([
                'name' => '杨小',
                'email' => '88888@qq.com',
                'password' => Hash::make('111111'),
                'phone' => '1520225454',
                'description' => '描述，汉语词语，出自闻一多《兽·人·鬼》：“刽子手们这次杰作，我们不忍再描述了。”形象地叙述；描写叙述；运用各种修辞手法对事物进行形象化的阐述。[describe] 描写叙述，运用各种修辞手法对事物进行形象化的阐述。他生动地~了那件事的经过l 作品朴实地~了农民的生活。郭小川 《钢铁是怎样炼成的》诗：“有个美妙的故事，必须向你们简略地描述。艺术的真正生命正在对于个别特殊事物的掌握和描述。希腊债务状况远不像惯常描述的那样令人担忧,希腊需要的就是回归增长。',
                'requist' => '描述，汉语词语，出自闻一多《兽·人·鬼》：“刽子手们这次杰作，我们不忍再描述了。”形象地叙述；描写叙述；运用各种修辞手法对事物进行形象化的阐述。[describe] 描写叙述，运用各种修辞手法对事物进行形象化的阐述。他生动地~了那件事的经过l 作品朴实地~了农民的生活。郭小川 《钢铁是怎样炼成的》诗：“有个美妙的故事，必须向你们简略地描述。艺术。',
                'work' => '1',
                'education' => '3',
                'birthday' => Carbon::now(),
                'hourse_car' => '3',
                'minzu' => '3',
                'weight' => 50,
                'visitor_ip' => $request->ip(),
                'height' => 170,
                'nation' => '中国',
                'province' => '上海',
                'city' => '上海',
                'area' => '长宁',
                'will_childs' => 2,
                'now_status' => 2,
            ]);

                       User::create([
                'name' => '杨小',
                'email' => '88888@qq.com',
                'password' => Hash::make('111111'),
                'phone' => '1520225454',
                'description' => '描述，汉语词语，出自闻一多《兽·人·鬼》：“刽子手们这次杰作，我们不忍再描述了。”形象地叙述；描写叙述；运用各种修辞手法对事物进行形象化的阐述。[describe] 描写叙述，运用各种修辞手法对事物进行形象化的阐述。他生动地~了那件事的经过l 作品朴实地~了农民的生活。郭小川 《钢铁是怎样炼成的》诗：“有个美妙的故事，必须向你们简略地描述。艺术的真正生命正在对于个别特殊事物的掌握和描述。希腊债务状况远不像惯常描述的那样令人担忧,希腊需要的就是回归增长。',
                'requist' => '描述，汉语词语，出自闻一多《兽·人·鬼》：“刽子手们这次杰作，我们不忍再描述了。”形象地叙述；描写叙述；运用各种修辞手法对事物进行形象化的阐述。[describe] 描写叙述，运用各种修辞手法对事物进行形象化的阐述。他生动地~了那件事的经过l 作品朴实地~了农民的生活。郭小川 《钢铁是怎样炼成的》诗：“有个美妙的故事，必须向你们简略地描述。艺术。',
                'work' => '1',
                'education' => '3',
                'birthday' => Carbon::now(),
                'hourse_car' => '3',
                'minzu' => '3',
                'weight' => 50,
                'visitor_ip' => $request->ip(),
                'height' => 170,
                'nation' => '中国',
                'province' => '上海',
                'city' => '上海',
                'area' => '长宁',
                'will_childs' => 2,
                'now_status' => 2,
            ]);


    }
}
