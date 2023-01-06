<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    // Course Enrollment API - POST
    public function courseEnrollment(Request $request){
        $request->validate([
            "title"         => "required",
            "description"   => "required",
            "total_videos"  => "required"
        ]);

        $course = new Course();

        $course->user_id = auth()->user()->id;
        $course->title = $request->title;
        $course->description = $request->description;
        $course->total_videos = $request->total_videos;

        $course->save();

        return response()->json([
            "status"    => 1,
            "message"   => "Course Enrolled Successfully"
        ], 201);
    }

    // Total Course API - GET
    public function totalCourses(){
        
        $id = auth()->user()->id;
        // print($id);
       
        $courses = User::find($id)->courses;
    
        return response()->json([
            "status"    => 1,
            "message"   => "Total Courses Enrolled",
            "data"      => $courses,
            "count"     =>$courses->count()
        ]);
    }

    // Delete Course API - GET
    public function deleteCourse($id){
        $user_id = auth()->user()->id;
        if(course::where([
            "id"        => $id,
            "user_id"   => $user_id
        ])->exists()){
            $course = Course::find($id);
            $course->delete();
            return response()->json([
                "status"    => 1,
                "message"   => "Course Deleted"
            ]);
        }else{
            return response()->json([
                "status"    => 0,
                "message"   => "Course Not Found"
            ]);
        }
    }

    function showMe(){
        $user_id = auth()->user()->id;
        $cont = DB::table('users')
        ->join('courses', 'user_id', "=",'courses.user_id')
        ->where(['courses.user_id'=> $user_id])
        ->get(['users.name', 'users.phone_no', 'courses.title', 'courses.description', 'courses.total_videos', 'courses.user_id']);
        $number = $cont->count();
        return response()->json([
            "data"          => $cont,
            "total-number"  => $number
        ]);
    }
}
