<?php

namespace Nikunjkabariya\Faq;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model {

    protected $table = 'faqs';
    //public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    /* protected $fillable = [
      'slug', 'question', 'answer', 'status', 'faq_topic_id'
      ]; */

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
    ];
    
    //Define a query scope called status = Active
    public function scopeActive($query) {
        return $query->where('status', 'Active');
    }

    /**
     * Get the faqTopic that owns the faq record
     */
    public function faqTopic() {
        return $this->belongsTo(FaqTopic::class);
    }

    /**
     * Create FAQ record
     * @param array $request
     * @return type
     */
    protected static function createRecord($request) {
        // generate slug
        $request['slug'] = str_slug($request['question']);
        return self::create($request);
    }
    
    /**
     * Update FAQ record
     * @param array $request
     * @param type $id
     * @return type
     */
    protected static function updateRecord($request, $id) {
        // generate slug
        $request['slug'] = str_slug($request['question']);
        return self::where(['id' => $id])->update($request);
    }

}
