<?php

namespace App\Imports;

use App\Studentlist;
use App\User;
use App\Campus;
use App\Strand;
use Carbon\Carbon;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;


class StudentlistImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        $campus = \App\Campus::where('name','LIKE','%'.$row[10].'%')->first();
        $password = \Hash::make(preg_replace('/\s+/', '',strtolower($row[0])));        
        $gradelevel = \App\Gradelevel::where('name','LIKE','%'.$row[11].'%')->first();
        $strand = \App\Strand::where('name','LIKE','%'.$row[12].'%')->first();
        if($strand != NULL){
            $st = $strand->id;
        }else{
            $st = NULL;
        }
        
        $student_id = Studentlist::updateOrCreate([
            'lrn'                   =>      $row[0],
        ],[
            'lastname'              =>      $row[1],            
            'firstname'             =>      $row[2],
            'middlename'            =>      $row[3],
            'extname'               =>      $row[4],
            'gender'                =>      $row[5],
            'email'                 =>      $row[6],
            'address'               =>      $row[7],
            'birthdate'             =>      \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[8]),
            'campus_id'             =>      $campus->id,
            'gradelevel_id'         =>      $gradelevel->id,
            'strand_id'             =>      $st,
        ])->id;

        User::updateOrCreate([
            'student_id'            =>      $student_id,
        ],[
            'name'                  =>      $row[0], 
            'email'                 =>      $row[6],           
            'password'              =>      $password,
            'role'                  =>      $row[9],
        ]);
    }
}
