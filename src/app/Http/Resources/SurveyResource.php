<?php

namespace AidynMakhataev\LaravelSurveyJs\app\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SurveyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            =>  $this->id,
            'name'          =>  $this->name,
            'slug'          =>  $this->slug,
            'json'          =>  $this->json,
//            'start_date'    =>  $this->start_date->formatLocalized('%A %d %B %Y'),
            'start_date'    =>  date_format(date_create($this->create_date),"D d F Y"),
            'start_time'    =>  $this->start_time,
//            'end_date'    =>  $this->end_date->formatLocalized('%A %d %B %Y'),
            'end_date'    =>  date_format(date_create($this->end_date),"D d F Y"),
            'end_time'    =>  $this->end_time,
            'created_at'    =>  $this->created_at->formatLocalized('%A %d %B %Y'),
        ];
    }
}
