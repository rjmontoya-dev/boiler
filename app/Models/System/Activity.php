<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Models\Activity as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use HasFactory;

     /*
    |--------------------------------------------------------------------------
    | ATTRIBUTES
    |--------------------------------------------------------------------------
     */

     public function causerName(): Attribute
     {
         return Attribute::get(function () {
             if ($this->causer) {
                 return $this->causer()->first() ? $this->causer()->first()->name : null;
             }
             $properties = json_decode($this->properties);
             $attributes = property_exists($properties, 'attributes') ? $properties->attributes : null;
             if ($attributes) {
                 if (property_exists($attributes, 'owner_type') && property_exists($attributes, 'owner_id')) {
                     return $attributes->owner_type::find($attributes->owner_id)->name;
                 }
             }

             return 'System';

         });
     }

     public function subjectName(): Attribute
     {
         return Attribute::get(function () {
             if ($this->subject_type) {
                 $query = in_array(SoftDeletes::class, class_uses_recursive($this->subject_type))
                 ? $this->subject_type::withTrashed()
                 : $this->subject_type::query();

                 if ($subject = $query->find($this->subject_id)) {

                     $subjectArr = explode('\\', $this->subject_type);
                     $class = $subjectArr[count($subjectArr) - 1];
                     $genericName = "{$class} #{$this->subject_id}";

                     $name = method_exists($this->subject_type, 'renderName')
                     ? $subject->renderName()
                     : $subject->name;

                     return empty($name) ? $genericName : $name;
                 }
             }

             return '--';
         });
     }
}
